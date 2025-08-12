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

$newname = $_POST['requireddjname'];
$djpass1 = $_POST['requireddjpassword'];
$djpass2 = $_POST['requireddjpassword2'];
$newaddress = $_POST['newaddress'];
$newaim = $_POST['newaim'];
$newmsn = $_POST['newmsn'];
$newyim = $_POST['newyim'];
$newicq = $_POST['newicq'];
$als1 = $_POST['requiredalias1'];
$als2 = $_POST['requiredalias2'];
$als3 = $_POST['requiredalias3'];

if ((isset($_POST['requireddjname']) == FALSE) or ($djpass1 !== $djpass2) or (isset($_POST['requireddjpassword']) == FALSE) or (isset($_POST['requireddjpassword2']) == FALSE) or (isset($_POST['requiredalias1']) == FALSE) or (isset($_POST['requiredalias2']) == FALSE) or (isset($_POST['requiredalias3']) == FALSE)) {
if (isset($_POST['requireddjname']) == FALSE) {
echo "<font color=\"red\"><strong>Please enter a name.</strong></font><br>";
}
if ($_POST['requireddjpassword'] !== $_POST['requireddjpassword2']) {
echo "<font color=\"red\"><strong>Passwords do not match.</strong></font><br>";
}
if (isset($_POST['requireddjpassword']) == FALSE) {
echo "<font color=\"red\"><strong>Please enter a password.</strong></font><br>";
}
if (isset($_POST['requireddjpassword2']) == FALSE) {
echo "<font color=\"red\"><strong>Please input your password in the \"password confirm\" box.</strong></font><br>";
}
if (isset($_POST['requiredalias1']) == FALSE) {
echo "<font color=\"red\"><strong>Please enter Alias 1.</strong></font><br>";
}
if (isset($_POST['requiredalias2']) == FALSE) {
echo "<font color=\"red\"><strong>Please enter Alias 2.</strong></font><br>";
}
if (isset($_POST['requiredalias3']) == FALSE) {
echo "<font color=\"red\"><strong>Please enter Alias 3.</strong></font>";
}
} else {
	if(!$db = @mysql_connect("$dbhost", "$dbuser", "$dbpass"))
	die('<font size=+1>An Error Occurred</font><hr>Unable to connect to the database. <BR>Check $dbhost, $dbuser, and $dbpass in config.php.');

	if(!@mysql_select_db("$dbname",$db))
	die("<font size=+1>An Error Occurred</font><hr>Unable to find the database <b>$dbname</b> on your MySQL server.");
	
	
$resultID = mysql_query("UPDATE currentdj SET name = '$newname', password = '$djpass1', address = '$newaddress', aim = '$newaim', msn = '$newmsn', yim = '$newyim', icq = '$newicq', alias1 = '$als1', alias2 = '$als2', alias3 = '$als3' WHERE dj = '$edj'") or die(mysql_error());
	if ($resultID == TRUE) {
		print "DJ $newname has been successfully edited!";
	} else {
		print "Sorry, but DJ $newname was not edited. Please check your settings and try again.";
	}

}
?>
<br><br><a href="main.php?pass=<?php echo "$pass"; ?>">Main</a>
<?php

///////////////

}
include ("footer.inc");
 ?>