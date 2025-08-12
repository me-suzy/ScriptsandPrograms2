<?php
//////////////////////////////////////////////////////////////////////////////
// DJ Status v1.8.2															//
// ©2005 Nathan Bolender www.nathanbolender.com								//
// Free to use on any website												//
//////////////////////////////////////////////////////////////////////////////

include ("../header.inc");
include ("../../config.php");

if(!$db = @mysql_connect("$dbhost", "$dbuser", "$dbpass"))
	die('<font size=+1>An Error Occurred</font><hr>Unable to connect to the database. <BR>Check $dbhost, $dbuser, and $dbpass in config.php.');
	if(!@mysql_select_db("$dbname",$db))
	die("<font size=+1>An Error Occurred</font><hr>Unable to find the database <b>$dbname</b> on your MySQL server.");

$result4 = mysql_query("INSERT INTO `currentdj_settings` VALUES (2, 'Display how DJ was set', '0'); ");


if ($result4 == TRUE) {

echo "Congratulations! The script was successfully upgraded!<br><b>Make sure you delete the admin/install folder before continuing to use the script.</b><br>Please click continue to enter the administration panel and add your DJs. <br><a href=\"../index.php\">Continue...</a>";

} else {

	echo "Sorry but the tables could not be created, please try again.";
}
include ("../footer.inc");
	?>