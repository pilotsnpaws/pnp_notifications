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

// TODO define sendgrid API
$from = new SendGrid\Email(null, "forum@pilotsnpaws.org");
$to = new SendGrid\Email(null, "nekbet@gmail.com");
// content

$mail = new SendGrid\Mail();

$content = new SendGrid\Content("text/plain", "some text here");
$mail->addContent($content);

// build HTML content from template email_trip_notif_template.php

$emailHTMLContent = "$emailHead $emailBody </html>" ;
// echo $emailHTMLContent;

// TODO figure out what data we need in email and have in DB

$userEmail        = 'test@test.com';
$userName         = 'userName';
$userFlyingDistance = '333';
$userHomeAirport  = 'KDEN';
$userDistSend     = '123';
$userDistRec      = '234';
$topicId          = '40123';
$topicTitle       = 'Test topic title!';
$topicFromToText  = 'From Here 12345 to There 98282';
$topicDistance    = '543';
$topicUrlPrefix   = 'http://pilotsnpaws.org/forum/viewtopic.php?t=' ;
$mapUrlPrefix     = 'http://www.pilotsnpaws.org/maps/maps_single_trip.php?topic='; //add topicId to end of this to show map
$forumUcpUrl      = 'http://www.pilotsnpaws.org/forum/ucp.php?i=164' ;
$forumTechUrl     = 'http://www.pilotsnpaws.org/forum/viewforum.php?f=17' ;

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
$emailHTMLContent = str_replace("{notif_topicUrlPrefix}", $topicUrlPrefix, $emailHTMLContent) ;
$emailHTMLContent = str_replace("{notif_mapUrlPrefix}", $mapUrlPrefix, $emailHTMLContent) ;
$emailHTMLContent = str_replace("{notif_forumUcpUrl}", $forumUcpUrl, $emailHTMLContent) ;
$emailHTMLContent = str_replace("{notif_forumTechUrl}", $forumTechUrl, $emailHTMLContent) ;

echo $emailHTMLContent;

$content = new SendGrid\Content("text/html", $emailHTMLContent);
$mail->addContent($content);


$mail->setFrom($from);
$mail->setSubject("Forum testing");

$personalization = new SendGrid\Personalization();
$email = new SendGrid\Email("Mike", "nekbet@gmail.com");
$personalization->addTo($email);
$mail->addPersonalization($personalization);


// categories
$mail->addCategory("Local test");
// google analytics
// $ganalytics = new SendGrid\Ganalytics();
// $ganalytics->setEnable(true);
// $ganalytics->setCampaignSource("trip-notification");
// // $ganalytics->setCampaignTerm("unused");
// $ganalytics->setCampaignContent("distance-notification"); // TODO this should be set by which notif fires the email, home airport, dist, etc
// // $ganalytics->setCampaignName("unused");
// $ganalytics->setCampaignMedium("email");
// $tracking_settings->setGanalytics($ganalytics);
// $mail->setTrackingSettings($tracking_settings);

$sg = new \SendGrid($sgApiKey);


// flag for dev - false = no email sent
$sendMail = false; 

// send the email
if($sendMail) {
    $response = $sg->client->mail()->send()->post($mail);
    echo $response->statusCode();
    newline();
    echo $response->headers();
    newline();
    echo $response->body();
    newline();
    }
  

// TODO figure out what topics haven't been sent

// TODO figure out what users get that topic's notif based on their settings


// TODO send notifications
// TODO add as much data as we can for tracking in emails
// TODO make sure we log when sent


// close connections
$f_mysqli->close();
$aws_mysqli->close();


?>
