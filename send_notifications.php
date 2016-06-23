<?php

// this checks the AWS DB and sends notifications
// this is intended to replace the custom functions_posting.php for distance based notif's only
// new activity/forum-following notifs will continue to come from the phpbb functionality

// include forum config file for DB info
include "settings.php";
include ($configPath);
include "xport_functions.php";
require 'vendor/autoload.php';
include "email_trip_notif_template.php";

echo "Environment: $environment"; 
newline();

// show IP for now in dev, just so we know when it changes to manage teh AWS firewall
// showIP();

function showIP() {
  $ch = curl_init('http://ifconfig.me/ip');
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
  $myIp = curl_exec($ch);
  echo "Server IP: $myIp";
  newLine(); 
}

// define the prefix of each log message
$logType = '[send notif]'; 

// get DB creds from forum config, AWS creds are in config as well but we don't rename them
// TODO we might not need to hit the phpbb DB, just AWS, clean up if so
$f_username=$dbuser;
$f_password=$dbpasswd;
$f_database=$dbname;
$f_server=$dbhost;

// define tables, we could use phpbb's constants.php but unsure how that will work with upgrade
// TODO might want to make these actually constants instead of vars
$tableTopics = 'phpbb_topics'; 
$tableAWSTopics = 'pnp_topics' ; // table in AWS that holds replicated topic data, but only columns we need
$tableNotif = 'pnp_trip_notif_status' ; // table that knows if we sent a notif to a user for a topic yet
$forum_id = '5' ; // we only care about the trip request forum

// email contents constants (say that five times fast)
define("topicUrlPrefix","http://pilotsnpaws.org/forum/viewtopic.php?t=");
define("mapUrlPrefix","http://www.pilotsnpaws.org/maps/maps_single_trip.php?topic="); //add topicId to end of this to show map
define("forumUcpUrl","http://www.pilotsnpaws.org/forum/ucp.php?i=164");
define("forumTechUrl","http://www.pilotsnpaws.org/forum/viewforum.php?f=17");


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


$topicId = getNextTopic();

$queryGetTopicDetails = "select t.topic_id, t.topic_title, t.pnp_sendZip, t.pnp_recZip,
    ROUND((ST_distance_sphere(t.send_location_point, t.rec_location_point) / 1609),0) as trip_dist
from pnp_topics t
where t.topic_id = $topicId";

$result = $aws_mysqli->query($queryGetTopicDetails);
$rowsReturned = $result->num_rows; 
if($rowsReturned == 0) {
  echo logEvent("Topic details query returned no rows.");
  newLine();
}
  elseif($rowsReturned == 1) {
    while($row = $result->fetch_assoc()){
      $topicId          = $row['topic_id'];
      $topicTitle       = $row['topic_title'];
      $sendZip          = $row['pnp_sendZip'];
      $recZip           = $row['pnp_recZip'];
      $topicDistance    = $row['trip_dist'];
    }
  }
  else { 
     echo logEvent("Topic details query returned > 1 row. Something is wrong. ");

}
 

// magic is called from here
$topicFromToText = getFromToText($sendZip, $recZip);
$mail = buildEmails($topicId, $topicFromToText);
// end magic


// TODO figure out what topics haven't been sent
function getNextTopic() {
  //get next topic to send out notifications
  $nextTopicId = "41343";
  return $nextTopicId;
}

function getFromToText($sendZip, $recZip) {
  $sendText = cityStateByZip($sendZip);
  $recText = cityStateByZip($recZip);
  
  $fromToText = "$sendText to $recText";
  return $fromToText;
}

function cityStateByZip($zipCode) {
	$lookupCall = file_get_contents('http://ziptasticapi.com/' . $zipCode);
	
	$output = json_decode($lookupCall);
	$city = ucwords(strToLower($output->city));
	$state = $output->state;
	return "$city, $state";
}

function buildEmails($topicId, $topicFromToText) {
	global $aws_server, $aws_database, $aws_mysqli, $emailHead, $emailBody;
		// TODO figure out what users get that topic's notif based on their settings
		$queryUsersToNotify = "select DISTINCT t.topic_id, 
				u.user_id, u.user_email, u.username, u.pf_flying_radius, u.apt_id, 
				ROUND((ST_distance_sphere(u.location_point, t.send_location_point) / 1609),0) as send_dist,
				ROUND((ST_distance_sphere(u.location_point, t.rec_location_point) / 1609),0) as rec_dist, t.topic_title, 
				ROUND((ST_distance_sphere(t.send_location_point, t.rec_location_point) / 1609),0) as trip_dist,
				u.location_point, t.send_location_point, t.rec_location_point,
				ST_buffer(u.location_point, pf_flying_radius * 0.01455581689886) as flying_circle,
				topic_linestring,
				ST_Intersects(ST_buffer(u.location_point, pf_flying_radius * 0.01455581689886), topic_linestring) as intersects
		from pnp_topics t JOIN 
			pnp_users u on t.source_server = u.source_server and t.source_database = u.source_database
		where 1=1
			and t.topic_id = $topicId
			and pf_flying_radius > 0 
				and pf_pilot_yn = 1
				and ST_Intersects(ST_buffer(u.location_point, pf_flying_radius * 0.01455581689886), topic_linestring) = 1
				and t.source_server = 'mysql.pilotsnpaws.org' -- '$aws_server' 
				and t.source_database = 'xpilotsnpaws-forum' -- '$aws_database'
		order by t.topic_id, u.user_id limit 2;" ;

		echo $queryUsersToNotify;
		newLine();

		$result = $aws_mysqli->query($queryUsersToNotify);

			$rowsReturned = $result->num_rows; 
			echo nl2br ("Rows returned: $rowsReturned \n");

			if($rowsReturned == 0) {
				echo logEvent("No results");
				newLine();
			}
				else {  // start iterating thru users, one per row
				while($row = $result->fetch_assoc()){
					$userId						= $row['user_id'];
					$userEmail        = $row['user_email'];
					$userName         = $row['username'];
					$userFlyingDistance = $row['pf_flying_radius'];
					$userHomeAirport  = strToUpper($row['apt_id']);
					$userDistSend     = $row['send_dist'];
					$userDistRec      = $row['rec_dist'];
					$topicId          = $row['topic_id'];
					$topicTitle       = $row['topic_title'];
					$topicDistance    = $row['trip_dist'];

					// build HTML content from template email_trip_notif_template.php
					$emailHTMLContent = "$emailHead $emailBody </html>" ;

					// replace the placeholders with real data
					$emailHTMLContent = str_replace("{notif_userEmail}", $userEmail, $emailHTMLContent) ;
					$emailHTMLContent = str_replace("{notif_userName}", $userName, $emailHTMLContent) ;
					$emailHTMLContent = str_replace("{notif_userFlyingDistance}", $userFlyingDistance, $emailHTMLContent) ;
					$emailHTMLContent = str_replace("{notif_userHomeAirport}", $userHomeAirport, $emailHTMLContent) ;
					$emailHTMLContent = str_replace("{notif_userDistSend}", $userDistSend, $emailHTMLContent) ;
					$emailHTMLContent = str_replace("{notif_userDistRec}", $userDistRec, $emailHTMLContent) ;
					$emailHTMLContent = str_replace("{notif_topicId}", $topicId, $emailHTMLContent) ;
					$emailHTMLContent = str_replace("{notif_topicTitle}", $topicTitle, $emailHTMLContent) ;
					$emailHTMLContent = str_replace("{notif_topicFromToText}", $topicFromToText, $emailHTMLContent) ;
					$emailHTMLContent = str_replace("{notif_topicDistance}", $topicDistance, $emailHTMLContent) ;
					$emailHTMLContent = str_replace("{notif_topicUrlPrefix}", topicUrlPrefix, $emailHTMLContent) ;
					$emailHTMLContent = str_replace("{notif_mapUrlPrefix}", mapUrlPrefix, $emailHTMLContent) ;
					$emailHTMLContent = str_replace("{notif_forumUcpUrl}", forumUcpUrl, $emailHTMLContent) ;
					$emailHTMLContent = str_replace("{notif_forumTechUrl}", forumTechUrl, $emailHTMLContent) ;
					$emailHTMLContent = str_replace("{notif_UserTotalDist}", $userDistSend + $userDistRec + $topicDistance , $emailHTMLContent) ;

					// show email 
					echo $emailHTMLContent;

					$mail = new SendGrid\Mail();

					// TODO plain text email? do we even need plain text anymore?
					$content = new SendGrid\Content("text/plain", "some text here");
					$mail->addContent($content);

					$content = new SendGrid\Content("text/html", $emailHTMLContent);
					$mail->addContent($content);

					$from = new SendGrid\Email("Pilots N Paws forum", "forum@pilotsnpaws.org");
					$mail->setFrom($from);
					$mail->setSubject("[TEST] PNP New Trip: $topicFromToText");

					$personalization = new SendGrid\Personalization();
					$email = new SendGrid\Email("Mike", "nekbet@gmail.com");
					// $email = new SendGrid\Email($userName, $userEmail);
					$personalization->addTo($email);
					$mail->addPersonalization($personalization);


					// categories
					// TODO add more data as we can for tracking in emails
					$mail->addCategory("Local test");
					$tracking_settings = new SendGrid\TrackingSettings();
					// google analytics
					$ganalytics = new SendGrid\Ganalytics();
					$ganalytics->setEnable(true);
					$ganalytics->setCampaignSource("trip-notification");
					// // $ganalytics->setCampaignTerm("unused");
					$ganalytics->setCampaignContent("distance-notification"); // TODO this should be set by which notif fires the email, home airport, dist, etc
					// // $ganalytics->setCampaignName("unused");
					$ganalytics->setCampaignMedium("email");
					$tracking_settings->setGanalytics($ganalytics);
					$mail->setTrackingSettings($tracking_settings);

					// flag for dev - false = no email sent
					$sendMailFlag = false; 
					echo "Send mail for real? $sendMailFlag";
					newLine();
						// send the email if we desire
					if($sendMailFlag) {
							$sendResult = sendMail($mail);
							logSend($topicId, $userId, $sendResult, $aws_server, $aws_database);
								}
					else { // if false we mock a 202 response
						$sendResult = '202';
						logSend($topicId, $userId, $sendResult, $aws_server, $aws_database);
					}

	
				} // end of iterate thru db
			} // end of else


} // end buildEmails function

function sendMail($mail) {
	global $sgApiKey ; 
	$apiKey = $sgApiKey;
	$sg = new \SendGrid($apiKey);
	$response = $sg->client->mail()->send()->post($mail);
	
	$httpStatusCode = $response->statusCode();
	
	echo $response->statusCode();
	newline();
	echo $response->headers();
	newline();
	echo $response->body();
	newline();
	
	return $httpStatusCode;
}
  
// TODO make sure we log when sent

function logSend($topicId, $userId, $statusCode, $serverName, $databaseName) {
	global $aws_mysqli, $tableNotif;
	
	if($statusCode == '202') {
			$notifyStatus = "Success";
		} else {
			$notifyStatus = "Fail";	
		}
	echo ("Sendgrid result for topic $topicId for user $userId was $statusCode");
	newLine();
	// save to db so we don't send to them again
	$logQuery = "INSERT INTO $tableNotif (topic_id, user_id, notify_status, status_code, created_ts, source_server, source_database) " .
					" VALUES ('$topicId', '$userId', '$notifyStatus', '$statusCode', CURRENT_TIMESTAMP, '$serverName', '$databaseName'  );";
	$logQueryResult = $aws_mysqli->query($logQuery);
	$rowsInserted = $aws_mysqli->affected_rows; 
	echo nl2br ("Rows inserted: $rowsInserted \n");

	if($rowsInserted == 0) {
		echo logEvent("Logging send failed insert: $logQuery");
		newLine();
		// TODO notify here if we didn't insert anything, would be a problem
	}

}

// close connections
$f_mysqli->close();
$aws_mysqli->close();



?>