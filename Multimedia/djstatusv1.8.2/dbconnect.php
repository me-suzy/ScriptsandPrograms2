<?php
//////////////////////////////////////////////////////////////////////////////
// DJ Status v1.8.2															//
// ©2005 Nathan Bolender www.nathanbolender.com								//
// Free to use on any website												//
//////////////////////////////////////////////////////////////////////////////

if (file_exists($filename))  {
echo "<strong>Please delete the 'install' folder, or if you haven't installed the script yet, please do that <a href=\"install\">now</a>. Thank you.</strong>";
} else {
	if(!$db = @mysql_connect("$dbhost", "$dbuser", "$dbpass"))
	die('<font size=+1>An Error Occurred</font><hr>Unable to connect to the database. <BR>Check $dbhost, $dbuser, and $dbpass in config.php.');

	if(!@mysql_select_db("$dbname",$db))
	die("<font size=+1>An Error Occurred</font><hr>Unable to find the database <b>$dbname</b> on your MySQL server.");
	
// Mode setting	
	$query="SELECT * FROM currentdj_settings WHERE id = '1'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		  $mode = $row['setting'];
}

// Show what set $dj setting
	$query="SELECT * FROM currentdj_settings WHERE id = '2'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		  $showsetby = $row['setting'];
}

if ($mode == 0) {
include ("djdetect.php");
} else {

if (isset($dj)) {
$dj = NULL;
}

	$query="SELECT * FROM currentdj WHERE active = '1'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		  $dj = $row['dj'];
}
$setby = "MySQL 'Active' Entry (In Manual Mode)";
}

////////////////////////////////////////////////////////////////////////////
if (isset($dj)) {

	
	if(!$db = @mysql_connect("$dbhost", "$dbuser", "$dbpass"))
	die('<font size=+1>An Error Occurred</font><hr>Unable to connect to the database. <BR>Check $dbhost, $dbuser, and $dbpass in config.php.');

	if(!@mysql_select_db("$dbname",$db))
	die("<font size=+1>An Error Occurred</font><hr>Unable to find the database <b>$dbname</b> on your MySQL server.");
	
	$query="SELECT * FROM currentdj WHERE dj = '$dj'";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
		  $name = $row['name'];
		  $address = $row['address'];
		  $aimdb = $row['aim'];
		  $msn = $row['msn'];
		  $yim = $row['yim'];
		  $icqdb = $row['icq'];
}


}
}
?>