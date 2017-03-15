<?php

include "stathat.php";
include "settings.php";

function logStathat($stathatAccount, $statName, $statValue, $statType, $environment) {
	$statName = 'pnp.' . $environment . '.' . $statName ; 
	// echo("Account $stathatAccount Name $statName Type $statType");
	newline();
	if ($statType == 'value') {
		echo("Log value to stathat");
		stathat_ez_value($stathatAccount, $statName, $statValue);
	}
	elseif ($statType == 'count') {
		echo("Log count to stathat");
		stathat_ez_count($stathatAccount, $statName, $statValue);
	}
	else {
		echo logEvent('Error. Invalid stathat statType. Must be value or count.');
	}
}

function logStathat2($statName, $statValue, $statType) {
	global $environment, $stathatAccount;
	$statName = 'pnp.' . $environment . '.' . $statName ; 
	// echo("Account $stathatAccount Name $statName Type $statType");
	newline();
	if ($statType == 'value') {
		stathat_ez_value($stathatAccount, $statName, $statValue);
		echo("Logged value: $statValue to stathat: $statName");
	}
	elseif ($statType == 'count') {
		stathat_ez_count($stathatAccount, $statName, $statValue);
		echo("Logged count: $statValue to stathat: $statName");
	}
	else {
		echo logEvent('Error. Invalid stathat statType. Must be value or count.');
	}
}


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

// take a zipcode and return the city and state
//  cityStateByZip(32507) -> "Pensacola, FL" 
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
