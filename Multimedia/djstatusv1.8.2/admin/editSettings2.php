<?php
//////////////////////////////////////////////////////////////////////////////
// DJ Status v1.8.2															//
// ©2005 Nathan Bolender www.nathanbolender.com								//
// Free to use on any website												//
//////////////////////////////////////////////////////////////////////////////

include ("../config.php");	
include ("header.inc");
if (!empty($_GET['pass'])) {
	$pass = $_GET['pass'];
} else {
	$pass = $_POST['pass'];
}
if ($pass != $adminpass) {
echo "<strong>Incorrect password</strong>";
} else {

///////////////

$newmode = $_POST['mode'];
$newshowsetby = $_POST['showsetby'];

	if(!$db = @mysql_connect("$dbhost", "$dbuser", "$dbpass"))
	die('<font size=+1>An Error Occurred</font><hr>Unable to connect to the database. <BR>Check $dbhost, $dbuser, and $dbpass in config.php.');

	if(!@mysql_select_db("$dbname",$db))
	die("<font size=+1>An Error Occurred</font><hr>Unable to find the database <b>$dbname</b> on your MySQL server.");
	
	
$resultID1 = mysql_query("UPDATE currentdj_settings SET setting = '$newmode' WHERE id = '1'") or die(mysql_error());
	
	$resultID2 = mysql_query("UPDATE currentdj_settings SET setting = '$newshowsetby' WHERE id = '2'") or die(mysql_error());
	if (($resultID1 == TRUE) && ($resultID2 == TRUE)) {
		print "Your settings have been changed successfully!";
	} else {
		print "Sorry, but your settings could not be changed. Please check your database settings and try again.";
	}

?>
<br><br><a href="main.php?pass=<?php echo "$pass"; ?>">Main</a>
<?php

///////////////

}
include ("footer.inc");
 ?>