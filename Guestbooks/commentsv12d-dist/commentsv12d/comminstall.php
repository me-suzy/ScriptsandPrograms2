<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Comment-Guestbook Installer</title>
<!-- This is the installer for the comments-guestbook script version 1.2
	 released July 16 2005 by Bryan H - bryhal@rogers.com - This is part of a 
	 package and not useful by itself. Visit bry.kicks-ass.org or 
	 www.bryancentral.com for the complete distribution    -->
	 
<style type="text/css">
<!--
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	margin: 40px;
	color: #000000;
	background-color: #CCCCCC;
	width: 600px;
}
h1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	color: #990000;
}
ol {
	line-height: 16px;
	font-size: 14px;
}
li {
	padding-bottom: 8px;
}
-->
</style>
</head>

<body>
<?php error_reporting(0); ?>
<?php if (isset($_POST['createtable'])): ?>
<?php
$scriptname = ($_POST['scriptname']);
include $scriptname;
$dbcnx = @mysql_connect("$dbserver","$dbuser","$dbpassword");
if (!$dbcnx) {
die( '<h1>Unable to connect to the database</h1><h1>-Please re-check the database variables you entered</h1><h1>-Make sure that a database exists</h1><h1>-Check that the name of your script was entered correctly</h1><h1>-Be sure the script is in the same directory as this installer</h1> ' );
}
if (! @mysql_select_db($dbname) ) {
die( '<h1>Unable to locate the database.</h1><h1>-Please re-check the database variables you entered</h1><h1>-Make sure that a database exists</h1><h1>-Check that the name of your script was entered correctly</h1><h1>-Be sure the script is in the same directory as this installer</h1>
	<p>The mysql server error was: '.mysql_error().'</p>' );
}
$maketable = "CREATE TABLE $tablename (
  `id` int(10) NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `name` varchar(40) default NULL,
  `comment` varchar(255) default NULL,
  `browser` varchar(64) default NULL,
  `ip` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id`)
)";
if (@mysql_query($maketable)){
echo ('<h1>Table successfully created.</h1>
	 <p>You should delete the commentinstaller.php script from your server before using the main script.</p>
	 <p>Read the READ ME section in the main script for usage instructions, or visit <a href="http://bry.kicks-ass.org" target="_blank">my website</a> for usage ideas</p>
	 <h1><a href="'.$scriptname.'">See your script now</h1>');
} else {
echo ('<h1>There was a problem creating the table.</h1>
	<p>The mysql server error message was: ' . mysql_error() . '</p>');
}

?>
<?php else: ?>
<h1>Comment-Guestbook Installer for Version 1.2 July 2005</h1>
<p>This script will setup the table needed to run the Comment-Guestbook script on your website.</p>
<p>Complete these steps before attempting to run this installer: </p>
<ol>
  <li>Please open the main script, 'comments.php', in a text editor, and read the READ ME section. Be sure to use a text editor that does NOT add formatting codes (like Word) as these will wreck the script. Notepad works fine.</li>
  <li>Find the '--SET THE VARIABLES BELOW--' section, and substitute your values. Consult your webhosts help pages or your web hosting control panel for these values, I can't tell you what they are. </li>
  <li>Upload your modified 'comments.php' (or a re-named copy of it) and this script (comminstall.php) to your webserver. You can put them in a subdirectory, but both files must be in the same directory. Also upload the 'commadmin.php' script to the same directory.</li>
  <li>If you have changed the name of 'comments.php' script, enter the new name below, then press 'Create Table':</li>
</ol>
<form name="installer" method="post" action="<?=$_SERVER['PHP_SELF']?>">
  <div align="center">
 Script Name:    <input type="text" name="scriptname" value="comments.php"><p>
	<input type="submit" name="createtable" value="Create Table"></p>
  </div>
</form>
<?php endif; ?>
</body>
</html>
