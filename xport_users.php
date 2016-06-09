<?php

//  this updates users from the forum into the AWS database so we can send distance notifications

// include forum config file for DB info
include "settings.php";
include ($configPath);
include "xport_functions.php";

echo "Environment: $environment"; 
newline();

// define the prefix of each log message
$logType = '[user xport]'; 

// get DB creds from forum config
$f_username=$dbuser;
$f_password=$dbpasswd;
$f_database=$dbname;
$f_server=$dbhost;

// hardcode creds for AWS DB
// TODO: move these to config file
// $aws_username
// $aws_password 
// $aws_database
// $aws_server


// define tables, we could use phpbb's constants.php but unsure how that will work with upgrade
$table_users = 'phpbb_users'; 
$table_users_details = 'vw_volunteers';  // view with location data
$table_aws_users = 'pnp_users' ; // table in AWS that holds replicated topic data, but only columns we need
$table_notif = 'pnp_trip_notif_status' ; // table that knows if we sent a notif to a user for a topic yet


// define forum mysqli connection
$f_mysqli = new mysqli($f_server, $f_username, $f_password, $f_database);

echo nl2br ("Forum database: $f_server/$f_database \n" ) ; 

 // Check forum connection
if (mysqli_connect_errno($f_mysqli))
  {
  echo "Failed to connect to forum MySQL: " . mysqli_connect_error();
  } else { } ;

// define AWS mysqli connection
$aws_mysqli = new mysqli($aws_server, $aws_username, $aws_password, $aws_database);

echo nl2br ("AWS database: $aws_server/$aws_database \n\n" ) ; 

 // Check AWS connection
if (mysqli_connect_errno($aws_mysqli))
  {
  echo "Failed to connect to AWS MySQL: " . mysqli_connect_error();
  } else { } ;


// TODO: Get list of users from forum that have recent activity
// TODO: 

$maxUserForum = getMaxUserForum();
$maxUserAWS = getMaxUserAWS();

if($maxUserForum > $maxUserAWS) {
	echo nl2br ("Forum has newer users than AWS ");
	newLine();
	getNextUserForum($maxUserAWS); // give the max user id that AWS has as a starting point 
};

// TODO: Get highest user ID in forum
function getMaxUserForum()
{
	global $table_users, $f_database, $f_mysqli;
	$queryMaxUserForum = "SELECT max(user_id)as max_user_id from $table_users";
	echo $queryMaxUserForum;
	newLine();
	$result = $f_mysqli->query($queryMaxUserForum) or die ($f_mysqli->error);

	while($row = $result->fetch_assoc()){ 
		$user_id = $row['max_user_id'];
		echo logEvent("Max user_id from forum: $user_id");
		newLine();
	}
		return $user_id;
}


// TODO: Get highest user ID in AWS
function getMaxUserAWS()
{
	global $table_aws_users, $f_database, $aws_mysqli;
	$queryMaxUserAWS = "SELECT max(user_id) AS max_user_id " .
		" FROM $table_aws_users WHERE source_database = '$f_database'";
	echo $queryMaxUserAWS;
	newLine();
	$result = $aws_mysqli->query($queryMaxUserAWS) or die ($aws_mysqli->error);

	while($row = $result->fetch_assoc()){ 
		$user_id = $row['max_user_id'];
		echo logEvent("Max user_id from AWS: $user_id");
		newLine();
	}
		return $user_id;
}

// TODO: If forum has new user, extract and load that user forum->AWS
function getNextUserForum($maxUserAWS) 
{
	global $table_users, $table_aws_users, $f_server, $f_database, $f_mysqli, $aws_mysqli, $table_users_details;

	//get start time to see how long this takes for logging
	$startTS = microtime(true);
	echo "Start microtime: $startTS";
	newline();

	$rowsSuccessCounter = 0;

	$queryNextUserForum = "SELECT last_visit, user_id,user_email,user_regdate,username,pf_flying_radius, " .
		" pf_foster_yn, pf_pilot_yn, apt_id, apt_name, zip, COALESCE(lat,0) as lat , COALESCE(lon,0) as lon, " .
		" city, state, CURRENT_TIMESTAMP " . 
 		" FROM $table_users_details " .
 		" WHERE user_id > $maxUserAWS " .
 		" ORDER BY user_id LIMIT 1000 "; // increase once we know it won't blow up
	echo $queryNextUserForum;
	newLine();
	$result = $f_mysqli->query($queryNextUserForum) or die ($f_mysqli->error);

	while($row = $result->fetch_assoc()){ 
		$userId = $row['user_id'];
		$lastVisit = $row['last_visit'];
		$userEmail = $f_mysqli->real_escape_string($row['user_email']);
		$userRegdate = $row['user_regdate'];
		$username = $f_mysqli->real_escape_string($row['username']);
		$flyingRadius = $row['pf_flying_radius'];
		$foster = $row['pf_foster_yn'];
		$pilot = $row['pf_pilot_yn'];
		$aptId = $row['apt_id'];
		$aptName = $f_mysqli->real_escape_string($row['apt_name']);
		$zip = $row['zip'];
		$lat = $row['lat'];
		$lon = $row['lon'];
		$city = $f_mysqli->real_escape_string($row['city']);
		$state = $row['state'];
		$currentTimestamp = $row['CURRENT_TIMESTAMP'];
		echo logEvent("Next user_id from forum: $userId");
		newLine();

	// insert user into AWS 

		$insertFields = " user_id, last_visit, user_email, user_regdate, username, pf_flying_radius, " .
			" pf_foster_yn, pf_pilot_yn, apt_id, apt_name, zip, lat, lon, location_point, " .
			" city, state, updated_source_ts, source_server, source_database " ; 
		$queryInsert = " INSERT INTO $table_aws_users ($insertFields) VALUES " .
			" ( $userId, '$lastVisit', '$userEmail', '$userRegdate', '$username', '$flyingRadius', " . 
			" '$foster', '$pilot', '$aptId', '$aptName', '$zip', '$lat', '$lon', " .
			" ST_GeomFromText('POINT($lon $lat)'), '$city', '$state', '$currentTimestamp', " .
			" '$f_server', '$f_database'); ";

		$insertResult = $aws_mysqli->query($queryInsert) ; // or die ($aws_mysqli->error);

		if(!$insertResult) {
				echo logEvent("Error: $aws_mysqli->error for insert: $queryInsert");
			} else
			{
				echo logEvent("Success: $queryInsert");
				$rowsSuccessCounter = $rowsSuccessCounter + 1; 
			}

		newLine();

	}

	$endTS = microtime(true);
	echo "Ending microtime: $endTS";
	newline();
	$durationTime = $endTS - $startTS;
	echo logEvent("Duration: $durationTime seconds for $rowsSuccessCounter rows");
	newLine();
	return($durationTime);

}

// close connections
$f_mysqli->close();
$aws_mysqli->close();

?>
