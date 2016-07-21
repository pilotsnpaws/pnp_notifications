<?php

// include forum config file for DB info - dont need these here as they're included in each other file
// include "settings.php";
// include ($configPath);

// get DB creds from forum config, AWS creds are in config as well but we don't rename them
$f_username=$dbuser;
$f_password=$dbpasswd;
$f_database=$dbname;
$f_server=$dbhost;

// define forum mysqli connection
// TODO Move this to SSL 
$f_mysqli = new mysqli($f_server, $f_username, $f_password, $f_database);
 // Check forum connection
if (mysqli_connect_errno($f_mysqli))
  {
		echo logEvent("Failed to connect to forum MySQL $f_server/$f_database: " . mysqli_connect_error());
		exit();
  } else {
		echo nl2br ("Connected to forum database: $f_server/$f_database \n" ) ; 
	} ;

// AWS connection - diff from forum as we want to SSL this traffic
$aws_mysqli = mysqli_init();
if (!$aws_mysqli) {
    exit('mysqli_init failed');
}

//if (!$aws_mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5)) {
//    die('Setting MYSQLI_OPT_CONNECT_TIMEOUT failed');
//}

// set SSL using AWS CA
$aws_mysqli->ssl_set(null,null,'rds-combined-ca-bundle.pem',null,null);

if (!$aws_mysqli->real_connect($aws_server, $aws_username, $aws_password, $aws_database)) {
  echo logEvent("Failed to connect to AWS MySQL $aws_server/$aws_database: " . mysqli_connect_error());
	exit("Failed to connect to AWS MySQL $aws_server/$aws_database: " . mysqli_connect_error());
} else {
		echo nl2br ("Connected to AWS database: $aws_server/$aws_database \n" ) ; 
}

$res = $aws_mysqli->query("SHOW STATUS LIKE 'Ssl_cipher';");
while($row = $res->fetch_array()) {
	$sslCipher = $row['Value'];
	$sslExpected = 'AES256-SHA';
	if($sslCipher != $sslExpected) { 
		echo logEvent("Error. SSL cipher incorrect or missing, expected $sslExpected, got: $sslCipher. Exiting."); 
		exit();
	} else {
		echo("Using SSL: $sslCipher");
		newLine();
	}
}
// end new AWS conn

?>