<?php

// this checks sendgrid for unsubscribed emails, and sets the user's flying distance to 0
// todo: decide to deactivate account when unsub'd

include "settings.php";
include ($configPath);
include "xport_functions.php";
include "pnp_db.php";

// define tables, we could use phpbb's constants.php but unsure how that will work with upgrade
$table_users = 'phpbb_users'; 
$table_profile = 'phpbb_profile_fields_data';

$logType = '[unsubs]'; 

global $sgApiKey ; 
$apiKey = $sgApiKey;
echo "Sendgrid API Key: $apiKey";
newLine();
newLine();

require 'vendor/autoload.php';
$sg = new \SendGrid($apiKey);

//  get unsubscribes from sendgrid
newLine();
$startTime = time() - 86400; //86400 seconds = 1 day
$query_params = json_decode('{"start_time": ' . $startTime . '}');  //, "end_time": 1489456410
$response = $sg->client->suppression()->unsubscribes()->get(null, $query_params);
$sgStatusCode = $response->statusCode();
echo "Unsubscribes status code: $sgStatusCode" ;
newLine();

// todo: turn this into function, as we use it 3x
if ($sgStatusCode != '200') {
  echo logEvent("Error. Sendgrid returned non-200 status code for unsubscribes query. Returned $sgStatusCode. Check API key setup.");
  exit();
}

$sgResponse = $response->body();

$jsonDataUnsub = json_decode($sgResponse, TRUE);
$unsubCount = count($jsonDataUnsub);
echo "Unsubscribes returned: " . $unsubCount;
newLine();

// add type to array
foreach( $jsonDataUnsub as &$row) {
    $row["type"] = 'unsub';
}

//  get spam reports from sendgrid
newLine();
$query_params = json_decode('{"start_time": ' . $startTime . '}');  //, "end_time": 1489456410
$responseSpam = $sg->client->suppression()->spam_reports()->get(null, $query_params);
$sgStatusCode = $responseSpam->statusCode();
echo "Spam status code: $sgStatusCode" ;
newLine();

if ($sgStatusCode != '200') {
  echo logEvent("Error. Sendgrid returned non-200 status code for spam query. Returned $sgStatusCode. Check API key setup.");
  exit();
}

$sgResponseSpam = $responseSpam->body();

$jsonDataSpam = json_decode($sgResponseSpam, TRUE);
$spamCount = count($jsonDataSpam);
echo "Spam returned: " . $spamCount;
newLine();

// add type to array
foreach( $jsonDataSpam as &$row) {
    $row["type"] = 'spam';
}

//  get bounces from sendgrid
newLine();
$query_params = json_decode('{"start_time": ' . $startTime . '}');  //, "end_time": 1489456410
$responseBounce = $sg->client->suppression()->bounces()->get(null, $query_params);
$sgStatusCode = $responseBounce->statusCode();
echo "Bounce status code: $sgStatusCode" ;
newLine();

if ($sgStatusCode != '200') {
  echo logEvent("Error. Sendgrid returned non-200 status code for bounce query. Returned $sgStatusCode. Check API key setup.");
  exit();
}

$sgResponseBounce = $responseBounce->body();

$jsonDataBounce = json_decode($sgResponseBounce, TRUE);
$BounceCount = count($jsonDataBounce);
echo "Bounces returned: " . $BounceCount;
newLine();

// add type to array
foreach( $jsonDataBounce as &$row) {
    $row["type"] = 'bounce';
}

$jsonData = array_merge($jsonDataUnsub, $jsonDataSpam, $jsonDataBounce);
$totalCount = count($jsonData);

newLine();
$i = 0;

while ($i < $totalCount) { // we can use < instead of <= because the array starts count at 0, not 1
  $createdTS = $jsonData[$i]["created"] ;
  $unsubEmail = $jsonData[$i]["email"];
  $sgType = $jsonData[$i]["type"];
  echo "Email: $unsubEmail was reported $sgType at " . date('Y-m-d h:i:s',$createdTS);
  newLine();
  //print_r($jsonData[$i]);
  // lookup the phpbb userID for the email address
  $userId = getForumUserIdByEmail($unsubEmail);
  // todo: ensure only one user ID returned per email, or handle somehow

  // update phpbb user profile to 0 flying distance, only if we get a user ID for email
  if (!is_null($userId)) {
    if (getFlyingDistanceByUserId($userId) > 0) {
      setFlyingDistanceByUserId($userId,0,$sgType);  
    } else {
      echo "User ID $userId flying distance already 0, no need to update.";
      newLine();
      newLine();
    }    
  } else {
    echo "Nothing to do with this email. ";
    newLine();
    newLine();
  }
  // todo: update phpbb user profile to not allow direct emails
  // later todo: put feedback entry and/or PM to user for tracking
  $i++;
}


function getForumUserIdByEmail($userEmail)
{
	global $table_users, $f_database, $f_mysqli;
	$queryIdByEmail = "SELECT user_id, FROM_UNIXTIME(user_lastvisit) as last_visit from $table_users where user_email = '$userEmail';" ;
	echo $queryIdByEmail;
	newLine();
	$result = $f_mysqli->query($queryIdByEmail) ; //or die ($f_mysqli->error);

	if(!$result) {
		echo logEvent("Error $f_mysqli->error to get user ID from email from forum, exiting. Query: $queryIdByEmail");
  } elseif($result->num_rows == 0) {
    echo logEvent("Warning. No user ID found for $userEmail - meaning email was changed or Sendgrid problem");
    newLine();
  } else {
		while($row = $result->fetch_assoc()){ 
			$user_id = $row['user_id'];
      $last_visit = $row['last_visit'];
			echo logEvent("User ID for email $userEmail: $user_id. Last visit $last_visit");
			newLine();
		}
			return $user_id;		
	}
} // end getForumUserIdByEmail


function getFlyingDistanceByUserId($userId)
{
	global $table_profile, $f_database, $f_mysqli;
  $queryGetFlyingDistance = "SELECT pf_flying_radius FROM $table_profile WHERE user_id = $userId";
  echo $queryGetFlyingDistance;
  newLine();
  $result = $f_mysqli->query($queryGetFlyingDistance); 
  
  if(!$result) {
		echo logEvent("Error $f_mysqli->error to get flying distance from forum, exiting. Query: $queryGetFlyingDistance");
	} else {
		while($row = $result->fetch_assoc()){ 
			$flyingDistance = $row['pf_flying_radius'];
			echo logEvent("Current flying distance for user ID $userId: $flyingDistance");
			newLine();
		}
			return $flyingDistance;		
	}
} // end getFlyingDistanceByUserId


function setFlyingDistanceByUserId($userId, $flyingDistance, $reason)
{
	global $table_profile, $f_database, $f_mysqli;
  $queryUpdateFlyingDistance = "UPDATE $table_profile SET pf_flying_radius = $flyingDistance, pf_email_validation = '$reason' WHERE user_id = $userId";
  echo $queryUpdateFlyingDistance;
  newLine();
  $result = $f_mysqli->query($queryUpdateFlyingDistance); 
  
  if(!$result) {
		echo logEvent("Error $f_mysqli->error to update flying distance from forum, exiting. Query: $queryGetFlyingDistance");
	  } else {
			echo logEvent("Updated flying distance for user ID $userId: $flyingDistance");
			newLine();
      newLine();
	    } 
  
} // end setFlyingDistanceByUserId

