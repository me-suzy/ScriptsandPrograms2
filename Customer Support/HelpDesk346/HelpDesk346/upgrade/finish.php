<?php
	session_start();
	mysql_connect($_SESSION['dbhost'], $_SESSION['dbuser'], $_SESSION['dbpass']);
	mysql_select_db($_SESSION['dbname']);
	
	// the columns are those that differ from the 336 version
	include_once "./files/process6.php";
	
	// update the passwords - md5 hashing sequence
	$q = "select id, pass from " . $_SESSION['prefix'] . "_accounts";
	$s = mysql_query($q) or die(mysql_error());
	while ( $r = mysql_fetch_assoc( $s ) )
	{
		$cmd = "update " . $_SESSION['prefix'] . "_accounts set pass = '" . md5( $r['pass'] ) . "' where id = " . $r['id'];
		mysql_query($cmd) or die($cmd . " " . mysql_error());	
	}
	
	// now we need to make sure that all the columns are present in the table
	
?>
<html>
 	<head>
 		<title>Upgrade Complete - Thank You</title>
 	</head>
 	
 	<body>
 		Your done
 	</body>
 </html>