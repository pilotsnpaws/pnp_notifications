<?php

function newline() {
	echo nl2br ("\n");
};

function logEvent($message) {
	global $logPath, $logType;

    if ($message != '') {
        // Add a timestamp to the start of the $message
        $message = gmDate(DATE_ATOM) . ': ' . $logType . ' ' . $message;
        // todo: move this logic to settings.php ? 
		$fp = fopen($logPath, 'a');
        fwrite($fp, $message."\n");
        fclose($fp);
        return "$message";
    }
}

function getFromToText($sendZip, $recZip) {
  $sendText = cityStateByZip($sendZip);
  $recText = cityStateByZip($recZip);
  
  $fromToText = "$sendText to $recText";
  return $fromToText;
}

function cityStateByZip($zipCode) {
	$lookupCall = file_get_contents('http://ziptasticapi.com/' . $zipCode);
	// $lookupCall = file_get_contents('http://ziptasticapi.com/31717');
	
	$output = json_decode($lookupCall);

	// make sure city is returned, if not could be invalid zip code
	if (array_key_exists('city',$output)) {
			$city = ucwords(strToLower($output->city));
			$state = $output->state;
			return "$city, $state";
	} 
	else {
			echo logEvent("Error looking up city/state for $zipCode. Exiting.");
			newLine();
			exit();
	}
}

function showIP() {
  $ch = curl_init('http://ifconfig.me/ip');
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
  $myIp = curl_exec($ch);
  echo "Server IP: $myIp";
  newLine(); 
}

?>
