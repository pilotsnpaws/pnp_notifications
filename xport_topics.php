<?php

// this checks AWS DB for the latest topic, then gets the next higher numbered topic_id in the PNP DB
// then, it inserts into the AWS DB (just a few fields we need)

// include forum config file for DB info
include "settings.php";
include ($configPath);
include "xport_functions.php";
include "pnp_db.php";

echo "Environment: $environment"; 
newline();

// define the prefix of each log message
$logType = '[topic xport]'; 

// define tables, we could use phpbb's constants.php but unsure how that will work with upgrade
$tableTopics = 'phpbb_topics'; 
$tablePosts = 'phpbb_posts' ; 
$tableAWSTopics = 'pnp_topics' ; // table in AWS that holds replicated topic data, but only columns we need
$tableNotif = 'pnp_trip_notif_status' ; // table that knows if we sent a notif to a user for a topic yet
$forum_id = '5' ; // we only care about the trip request forum

// moved DB to pnp_db.php



$maxAWSTopicId = getMaxAWS();
$nextForumTopicId = getNextForum($maxAWSTopicId);

if($nextForumTopicId > $maxAWSTopicId) {
	echo nl2br ("forum has new topic\n\n");
	$topicDetails = getNextTopicDetails($nextForumTopicId); 
} else { 
		echo logEvent("Forum has no newer topics. Exiting.");
		newLine();
		exit();
	}

function getMaxAWS()
{
	global $forum_id, $aws_mysqli, $tableAWSTopics, $f_database;
	// get max topic_id from AWS topics - make sure its the same DB (xpilotspaws-forum, for ex)
	$query_get_max_aws_topic = "SELECT max(topic_id) as max_topic_id " .
		" FROM $tableAWSTopics " . 
		" WHERE forum_id = $forum_id and source_database = '$f_database'" .
		" HAVING max_topic_id IS NOT NULL" ; 
	echo nl2br ("AWS max topic query: $query_get_max_aws_topic \n" ) ;
	$result = $aws_mysqli->query($query_get_max_aws_topic) ; // or die ($aws_mysqli->error);

	if(!$result) { 
			echo logEvent("Error $aws_mysqli->error to get max topic ID from AWS, exiting. Query: $query_get_max_aws_topic");
			exit();
		} else {
			$rowsReturned = $result->num_rows; 
			echo nl2br ("Rows returned: $rowsReturned \n") ; 

			if($rowsReturned == 0) {
				echo logEvent("AWS has no topics, starting from 0");
				newLine();
				$topicId = 0;
			}
				else {
				while($row = $result->fetch_assoc()){
					$topicId = $row['max_topic_id'];
					echo logEvent("Max topic_id from AWS: $topicId");
					newline();
				}
			}

			return $topicId;
	}
}

function getNextForum($maxTopic)
{	
	global $forum_id, $f_mysqli, $f_database, $tableTopics;
	$topic_id = '';
	
	// from forum db, get the next higher # topic 
	$query_get_next_topic = "SELECT min(topic_id) as min_topic_id, max(topic_id) as max_topic_id " .
		" FROM $tableTopics " .
		" WHERE forum_id = $forum_id " .
		" AND topic_id > $maxTopic" . 
		" HAVING max_topic_id IS NOT NULL " ;
	echo nl2br("Forum query: $query_get_next_topic\n");

	$result = $f_mysqli->query($query_get_next_topic) ; // or die ($f_mysqli->error);

	if(!$result) { 
		echo logEvent("Error $f_mysqli->error to get next topic id from forum, exiting. Query: $query_get_next_topic");
		exit();
	} else {
			$rowsReturned = $result->num_rows; 
			echo nl2br ("Forum rows returned: $rowsReturned \n") ; 

			if($rowsReturned == 0) {
				// log and exit is handled in if logic check, rather than exit here
			}
				else {
					while($row = $result->fetch_assoc()){
						// echo $row['min_topic_id'];
						echo logEvent("Next topic_id in forum DB: " . $row['min_topic_id']);
						$topic_id = $row['min_topic_id'];
						newLine();
						echo logEvent("Greatest topic_id in forum DB: " . $row['max_topic_id']);
						newline();
					}
				}

			return $topic_id;
		} // end else
} // end getNextForum

function getNextTopicDetails($topic_id)
{	
	global $forum_id, $f_mysqli, $tableTopics, $tablePosts;

	$startTS = microtime(true);
	echo "Start microtime: $startTS";
	newline();

	$rowsSuccessCounter = 0;

	// from forum db, get the next higher # topic 
	$queryFields = " t.topic_id, from_unixtime(t.topic_time) as topic_time_ts, t.forum_id, t.topic_title, t.topic_first_poster_name, " .
		" t.pnp_sendZip, t.pnp_recZip, " .
		" trim(substr(p.post_text, position('Breeds, weight, age:' in p.post_text)+20, position('Health condition:' in p.post_text) - (position('Breeds, weight, age:' in p.post_text) + 20) )) as breed_weight " ;
	$query_get_next_topic = " SELECT $queryFields FROM $tableTopics t join $tablePosts p on t.topic_first_post_id = p.post_id " .
		" WHERE t.forum_id = $forum_id AND t.topic_id >= $topic_id and t.pnp_sendZip is not null " .
		" ORDER BY t.topic_id LIMIT 100 ;" ;
	echo nl2br("Forum details query: $query_get_next_topic\n");

	$f_mysqli->query("SET time_zone = 'GMT';") or die ($f_mysqli->error); // TODO add error handling log here
	$result = $f_mysqli->query($query_get_next_topic) ; //or die ($f_mysqli->error);

	if(!$result){
		echo logEvent("Error $f_mysqli->error getting topic details, exiting. Query: $query_get_next_topic");
		exit();
	} else {
			$rowsReturned = $result->num_rows; 
			echo nl2br ("Rows returned: $rowsReturned \n") ; 

			while($row = $result->fetch_assoc()){
				echo logEvent("Next topic_id in forum DB: " . $row['topic_id' ]);
				newline();
				$topic_id = $row['topic_id'];
				$forum_id = $row['forum_id'];
				$topic_title = $f_mysqli->real_escape_string($row['topic_title']);
				$topic_first_poster_name = $f_mysqli->real_escape_string($row['topic_first_poster_name']);
				$pnp_sendZip = $row['pnp_sendZip'];
				$pnp_recZip = $row['pnp_recZip'];
				$topic_time_ts = $row['topic_time_ts'];
				$breed_weight = $f_mysqli->real_escape_string($row['breed_weight']);

				// insert into AWS
				insertTopic($topic_id, $forum_id, $topic_title, $topic_time_ts, $topic_first_poster_name, $breed_weight, $pnp_sendZip, $pnp_recZip);
				$rowsSuccessCounter = $rowsSuccessCounter + 1; 
			}
	}

	$endTS = microtime(true);
	echo "Ending microtime: $endTS";
	newline();
	$durationTime = $endTS - $startTS;
	echo logEvent("Duration: $durationTime seconds for $rowsSuccessCounter rows");
	newLine();

}

function insertTopic($topic_id, $forum_id, $topic_title, $topic_time_ts, $topic_first_poster_name, $breed_weight, $pnp_sendZip, $pnp_recZip)
{
	// insert this topic and details into AWS
	global $forum_id, $aws_mysqli, $tableAWSTopics, $f_server, $f_database, $f_mysqli, $rowsSuccessCounter;

// get sending zip coordinates, TODO this should be moved to its own function to just return lat/lon by any zip
	$zipQuery = "SELECT lat, lon FROM zipcodes WHERE zip = '$pnp_sendZip' LIMIT 1" ; // we should only have one entry per zip, but just in case limit
	$zipResult = $f_mysqli->query($zipQuery);
	if(!$zipResult) {
			echo logEvent("Error: $aws_mysqli->error for zip query: $zipQuery");
			newline();
		} else
		{
			echo logEvent("Success: $zipQuery");
			newLine();
		}
// TODO handle if we get no result/rows=0

	while($row = $zipResult->fetch_assoc()){
			$sendLat = $row['lat'];
			$sendLon = $row['lon'];
			// echo $sendLat;
			// echo $sendLon;
	}

// repeat for receiving zip coords, again TODO - use function on cleanup
	$zipQuery = "SELECT lat, lon FROM zipcodes WHERE zip = '$pnp_recZip' LIMIT 1" ; // we should only have one entry per zip, but just in case limit
	$zipResult = $f_mysqli->query($zipQuery);
	if(!$zipResult) {
			echo logEvent("Error: $aws_mysqli->error for zip query: $zipQuery");
			newLine();
		} else
		{
			echo logEvent("Success: $zipQuery");
			newLine();
		}
// TODO handle if we get no result/rows=0

	while($row = $zipResult->fetch_assoc()){
			$recLat = $row['lat'];
			$recLon = $row['lon'];
			// echo $sendLat;
			// echo $sendLon;
	}

// now insert into the AWS topics table
	$insertFields = " topic_id, forum_id, topic_title, topic_first_poster_name, breed_weight, pnp_sendZip, pnp_recZip, " .
		" send_lat, send_lon, send_location_point, rec_lat, rec_lon, rec_location_point, topic_linestring, source_server, source_database, topic_time_ts " ;
	$lineStringColumnValue = "LINESTRING($sendLon $sendLat, $recLon $recLat)";
	$insertQuery = "INSERT INTO $tableAWSTopics ($insertFields) VALUES ( '$topic_id', '$forum_id', '$topic_title', '$topic_first_poster_name', " .
		" '$breed_weight', '$pnp_sendZip', '$pnp_recZip', '$sendLat', '$sendLon', ST_GeomFromText('POINT($sendLon $sendLat)') , " . 
		" '$recLat', '$recLon', ST_GeomFromText('POINT($recLon $recLat)'), " .
		" ST_GeomFromText('$lineStringColumnValue'), '$f_server', '$f_database', '$topic_time_ts')"; 

	// echo nl2br ("Insert to AWS: $insertQuery");
	// newLine();

	$result = $aws_mysqli->query($insertQuery) ;
	if(!$result) {
			echo logEvent("Error: $aws_mysqli->error for insert: $insertQuery");
			newline();
		} else
		{
			echo logEvent("Success: $insertQuery");
			newline();
			$rowsSuccessCounter = $rowsSuccessCounter + 1; 
		
		}
}


// close connections
$f_mysqli->close();
$aws_mysqli->close();


?>
