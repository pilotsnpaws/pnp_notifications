<?php

$environment = 'dev' ;
$configPath  = '../config_local.php' ;
$logPath	= '/dev/null' ; 
$sgApiKey = 'x';
$sendMailFlag = false; // send mail for real?
$sendMailRecipients = false; // send mail to real recipients or just to Mike
$notificationEmailSendGridCategory = 'local test'; // category for sendgrid tracking
$sendHoursBack = '60'; // this is how many hours back the send notification will pull topics. it is a safe guard in case the logsend fails
$stathatAccount = 'ezkeyhere' ; // stathat ez key, see https://www.stathat.com/manual/start

?>
