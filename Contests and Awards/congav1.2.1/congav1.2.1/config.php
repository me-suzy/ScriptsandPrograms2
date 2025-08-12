<?php
////////////////////////////////////////////////////////
// Conga Line Script v1.2.1
// ©2005 Nathan Bolender www.nathanbolender.com
// Licensed under the Creative Commons Attribution-NonCommercial-NoDerivs License
// located at http://creativecommons.org/licenses/by-nc-nd/2.0/
////////////////////////////////////////////////////////


///////////////////////////////////////////
// Configuration //////////////////////////
///////////////////////////////////////////

$dbhost = "";		 // MySQL Server, Usually localhost
$dbname = ""; // MySQL database name
$dbuser = ""; 		 // MySQL username
$dbpass = ""; 		 // MySQL password
$adminpass = "";		 // Password for the administration panel

///////////////////////////////////////////
// Do not edit below this line! ///////////
///////////////////////////////////////////

if(!$db = @mysql_connect("$dbhost", "$dbuser", "$dbpass"))
	die('<font size=+1>An Error Occurred</font><hr>Unable to connect to the database. <BR>Check $dbhost, $dbuser, and $dbpass in config.php.');

	if(!@mysql_select_db("$dbname",$db))
	die("<font size=+1>An Error Occurred</font><hr>Unable to find the database <b>$dbname</b> on your MySQL server.");

$query="SELECT * FROM conga_settings WHERE id = '1'";
	$result=@mysql_query($query);
	while($row = @mysql_fetch_array($result)) {
		  $name = $row['name'];
		  $info = $row['info'];
}
	?>