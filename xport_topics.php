<?php

// this checks AWS DB for the latest topic, then gets the next higher numbered topic_id in the PNP DB
// then, it inserts into the AWS DB (just a few fields we need)

// include forum config file for DB info
include "settings.php";
include ($configPath);
include "xport_functions.php";

echo "Environment: $environment"; 
newline();

// define the prefix of each log message
$logType = '[data xport]'; 

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
$table_topics = 'phpbb_topics'; 
$table_aws_topics = 'pnp_topics' ; // table in AWS that holds replicated topic data, but only columns we need
$table_notif = 'pnp_trip_notif_status' ; // table that knows if we sent a notif to a user for a topic yet
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

$max_aws_topic_id = getMaxAWS();
$next_forum_topic_id = getNextForum($max_aws_topic_id);

if($next_forum_topic_id > $max_aws_topic_id) {
	echo nl2br ("forum has new topic\n\n");
	$topicDetails = getNextTopicDetails($next_forum_topic_id); 
	insertTopic($topicDetails[0],$topicDetails[1],$topicDetails[2],$topicDetails[3],$topicDetails[4],$topicDetails[5]);
};

function getMaxAWS()
{
	global $forum_id, $aws_mysqli, $table_aws_topics, $f_database;
	// get max topic_id from AWS topics - make sure its the same DB (xpilotspaws-forum, for ex)
	$query_get_max_aws_topic = "SELECT max(topic_id) as max_topic_id from $table_aws_topics where forum_id = $forum_id and source_database = '$f_database'"; 
	echo nl2br ("AWS max topic query: $query_get_max_aws_topic \n" ) ;
	$result = $aws_mysqli->query($query_get_max_aws_topic) or die ($aws_mysqli->error);

	while($row = $result->fetch_assoc()){
		$topic_id = $row['max_topic_id'];
		echo logEvent("Max topic_id from AWS: $topic_id");
		newline();
	}

	return $topic_id;
}

function getNextForum($max_topic)
{	
	global $forum_id, $f_mysqli, $table_topics;

	// from forum db, get the next higher # topic 
	$query_get_next_topic = "SELECT min(topic_id) as min_topic_id, max(topic_id) as max_topic_id FROM $table_topics WHERE forum_id = $forum_id AND topic_id > $max_topic" ;
	echo nl2br("Forum query: $query_get_next_topic\n");

	$result = $f_mysqli->query($query_get_next_topic) or die ($f_mysqli->error);

	$rows_returned = $result->num_rows; 
	echo nl2br ("Rows returned: $rows_returned \n") ; 

	// exit if we dont get any new topics
	//if($rows_returned < 1) {
	//	echo "Nothing to do! Stopping. ";
	//	die;
	//	}

	while($row = $result->fetch_assoc()){
		echo logEvent("Next topic_id in forum DB: " . $row['min_topic_id']);
		$topic_id = $row['min_topic_id'];
		newLine();
		echo logEvent("Greatest topic_id in forum DB: " . $row['max_topic_id']);
		newline();
	}

	// echo ($topic_id - $max_aws_topic_id);

	return $topic_id;
}

function getNextTopicDetails($topic_id)
{	
	global $forum_id, $f_mysqli, $table_topics;

	// from forum db, get the next higher # topic 
	$query_fields = " topic_id, forum_id, topic_title, topic_first_poster_name, pnp_sendZip, pnp_recZip " ;
	$query_get_next_topic = "SELECT $query_fields FROM $table_topics WHERE forum_id = $forum_id AND topic_id = $topic_id ORDER BY topic_id LIMIT 1" ;
	echo nl2br("Forum details query: $query_get_next_topic\n");

	$result = $f_mysqli->query($query_get_next_topic) or die ($f_mysqli->error);

	$rows_returned = $result->num_rows; 
	echo nl2br ("Rows returned: $rows_returned \n") ; 

	while($row = $result->fetch_assoc()){
		echo logEvent("Next topic_id in forum DB: " . $row['topic_id' ]);
		newline();
		$topic_id = $row['topic_id'];
		$forum_id = $row['forum_id'];
		$topic_title = $f_mysqli->real_escape_string($row['topic_title']);
		$topic_first_poster_name = $f_mysqli->real_escape_string($row['topic_first_poster_name']);
		$pnp_sendZip = $row['pnp_sendZip'];
		$pnp_recZip = $row['pnp_recZip'];
	}

	// echo ($topic_id - $max_aws_topic_id);

	return array ($topic_id, $forum_id, $topic_title, $topic_first_poster_name, $pnp_sendZip, $pnp_recZip);
}

function insertTopic($topic_id, $forum_id, $topic_title, $topic_first_poster_name, $pnp_sendZip, $pnp_recZip)
{
	// insert this topic and details into AWS
	global $forum_id, $aws_mysqli, $table_aws_topics, $f_server, $f_database;

	$query_fields = " topic_id, forum_id, topic_title, topic_first_poster_name, pnp_sendZip, pnp_recZip, source_server, source_database " ;
	$insert_query = "INSERT INTO $table_aws_topics ($query_fields) VALUES ( '$topic_id', '$forum_id', '$topic_title', '$topic_first_poster_name', '$pnp_sendZip', '$pnp_recZip', '$f_server', '$f_database')"; 

	echo nl2br ("Insert to AWS: $insert_query \n\n ");

	$result = $aws_mysqli->query($insert_query) ;
	if(!$result) {
			echo logEvent("Error: $aws_mysqli->error for insert: $insert_query");
		} else
		{
			echo logEvent("Success: $insert_query");
		}
}


// close connections
$f_mysqli->close();
$aws_mysqli->close();


?>
