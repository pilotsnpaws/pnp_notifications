<?php

$environment = 'dev' ;
$configPath  = 'ca_dev/local_config.php' ;
$logPath	= '/dev/null' ; 
$sgApiKey = 'x';
$sendMailFlag = true; // send mail for real?
$notificationEmailSendGridCategory = 'local test'; // category for sendgrid tracking
$sendHoursBack = '60'; // this is how many hours back the send notification will pull topics. it is a safe guard in case the logsend fails

?>
