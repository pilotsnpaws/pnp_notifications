<?php

//  this updates users from the forum into the AWS database so we can send distance notifications
// xport_users.php creates them, but if they change their prefs we need to update it

// include forum config file for DB info
include "settings.php";
include ($configPath);
include "xport_functions.php";

echo "Environment: $environment"; 
newline();

// define the prefix of each log message
$logType = '[user update]'; 

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
		echo logEvent("Failed to connect to forum MySQL: " . mysqli_connect_error());
		exit();
  } else { } ;

// define AWS mysqli connection
$aws_mysqli = new mysqli($aws_server, $aws_username, $aws_password, $aws_database);

echo nl2br ("AWS database: $aws_server/$aws_database \n\n" ) ; 

 // Check AWS connection
if (mysqli_connect_errno($aws_mysqli))
  {
		echo logEvent("Failed to connect to AWS MySQL: " . mysqli_connect_error());
		exit();
  } else { } ;


// Get list of users from forum that have recent activity
// Run sql update on AWS with latest info

//get start time to see how long this takes for logging
$startTS = microtime(true);
echo "Start microtime: $startTS";
newline();

$rowsSuccessCounter = 0;

$queryRecentActiveUsersForum = "SELECT last_visit, user_id, user_email, user_regdate, username, pf_flying_radius, " .
	" pf_foster_yn, pf_pilot_yn, apt_id, apt_name, zip, COALESCE(lat,0) as lat , COALESCE(lon,0) as lon, " .
	" city, state, CURRENT_TIMESTAMP, user_inactive_reason " . 
		" FROM $table_users_details " .
		" WHERE last_visit > date_add(CURRENT_TIMESTAMP, INTERVAL -3 HOUR)" .
		" ORDER BY user_id LIMIT 500 "; // increase once we know it won't blow up
echo "queryRecentActiveUsersForum: $queryRecentActiveUsersForum" ;
newLine();
$result = $f_mysqli->query($queryRecentActiveUsersForum); //  or die ($f_mysqli->error);

if(!$result) {
		echo logEvent("Error: $f_mysqli->error for query: $queryRecentActiveUsersForum , exiting.");
		exit();
} else {
	
		$rowsReturned = $result->num_rows; 
		echo nl2br ("Rows returned: $rowsReturned \n") ; 
	
		while($row = $result->fetch_assoc()){ 
		$userId = $row['user_id'];
		$lastVisit = $row['last_visit'];
		$userEmail = $f_mysqli->real_escape_string($row['user_email']);
		$userRegdate = $row['user_regdate'];
		$username = $f_mysqli->real_escape_string($row['username']);
		$userInactiveReason = $row['user_inactive_reason'];
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

		// update user in AWS 

		$queryUpdate = " UPDATE $table_aws_users " .
			" SET last_visit = '$lastVisit', user_email = '$userEmail', user_regdate = '$userRegdate', " . 
			" username = '$username', pf_flying_radius = '$flyingRadius', " . 
			" pf_foster_yn = '$foster', pf_pilot_yn = '$pilot', apt_id = '$aptId', apt_name = '$aptName'," . 
			" zip = '$zip', lat = '$lat', lon = '$lon', " .
			" location_point = ST_GeomFromText('POINT($lon $lat)'), city = '$city', state = '$state', " . 
			" updated_source_ts = '$currentTimestamp' , user_inactive_reason = $userInactiveReason " .
			" WHERE user_id = $userId and source_server = '$f_server' and source_database = '$f_database'; ";

		$updateResult = $aws_mysqli->query($queryUpdate) ; // or die ($aws_mysqli->error);

		if(!$updateResult) {
				echo logEvent("Error: $aws_mysqli->error for update: $queryUpdate");
			} else
			{
				echo logEvent("Successful update for $username / id $userId");
				// echo logEvent("Success: $queryUpdate");
				$rowsSuccessCounter = $rowsSuccessCounter + 1; 
			}

		newLine();

	} // end while
}



$endTS = microtime(true);
echo "Ending microtime: $endTS";
newline();
$durationTime = $endTS - $startTS;
echo logEvent("Duration: $durationTime seconds for $rowsSuccessCounter rows (rows returned from forum: $rowsReturned)");
newLine();

// close connections
$f_mysqli->close();
$aws_mysqli->close();

?>
