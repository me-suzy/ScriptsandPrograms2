<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// INSTALL.PHP > 03-11-2005

function menu() {
	echo "somery installation";
}
$skindir = "admin/skins/default";

include("config.php");
include("$skindir/header.php");
extract($_POST);
extract($_GET);
if (!$step) {
?>

<form method="post" action="install.php"><input type="hidden" name="step" value="1">
<b>Step 1: Entering your information</b><br />
Please enter your information here - these settings cannot be changed afterwards, unless you wish to dive into the database.<br /><br />
<table>
	<tr>
		<td>login</td>
		<td><input type="text" name="login"></td>
	</tr>
	<tr>
		<td>pass</td>
		<td><input type="text" name="pass"></td>
	</tr>
	<tr>
		<td>email</td>
		<td><input type="text" name="email"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" value="proceed"></td>
	</tr>
</table>
</form>

<?
} 
if ($step == 1) {

if (!$pass || !$login || !$email) { echo "<strong>MISSING INFORMATION</strong> - <a href='install.php'>go back</a>"; exit; }

echo "<b>Step 2: Creating and Populating the database</b><br />
This is an automatic process - do not browse away or disrupt this process in another way.<br /><br />";

mysql_connect($sqlhost, $sqluser, $sqlpass);
mysql_select_db($sqldb);

$query = "CREATE TABLE ".$prefix."articles (
  aid bigint(20) NOT NULL auto_increment,
  username varchar(16) NOT NULL default '',
  title varchar(100) NOT NULL default '',
  body text NOT NULL,
  more text NOT NULL,
  category int(11) NOT NULL default '0',
  date varchar(8) NOT NULL default '',
  time varchar(4) NOT NULL default '',
  status tinyint(4) NOT NULL default '0',
  show_comments tinyint(4) NOT NULL default '0',
  show_body tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (aid),
  UNIQUE KEY aid (aid),
  KEY aid_2 (aid)
) TYPE=MyISAM;";
echo "Creating table <b>articles</b>... ";
$q = mysql_query($query) or die ("<font face=cc0000>Failed!</font>");
echo "<font color=00A400>Succeeded!</font><br>";

$query = "CREATE TABLE ".$prefix."categories (
  cid int(11) NOT NULL auto_increment,
  category varchar(32) NOT NULL default '',
  PRIMARY KEY  (cid),
  UNIQUE KEY cid (cid)
) TYPE=MyISAM;";
echo "Creating table <b>categories</b>... ";
$q = mysql_query($query) or die ("<font face=cc0000>Failed!</font>");
echo "<font color=00A400>Succeeded!</font><br>";

$query = "CREATE TABLE ".$prefix."comments (
  coid int(11) NOT NULL auto_increment,
  parentid int(11) NOT NULL default '0',
  author varchar(32) NOT NULL default '',
  email varchar(32) NOT NULL default '',
  url varchar(32) NOT NULL default '',
  comment text NOT NULL,
  date varchar(8) NOT NULL default '',
  time varchar(4) NOT NULL default '',
  PRIMARY KEY  (coid),
  UNIQUE KEY coid (coid),
  KEY coid_2 (coid)
) TYPE=MyISAM;";
echo "Creating table <b>comments</b>... ";
$q = mysql_query($query) or die ("<font face=cc0000>Failed!</font>");
echo "<font color=00A400>Succeeded!</font><br>";

$query = "CREATE TABLE ".$prefix."profile (
  username varchar(100) NOT NULL default '',
  nickname varchar(100) NOT NULL default '',
  firstname varchar(100) NOT NULL default '',
  lastname varchar(100) NOT NULL default '',
  gender tinyint(4) NOT NULL default '0',
  dob varchar(8) NOT NULL default '',
  country varchar(100) NOT NULL default '',
  city varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  icq varchar(20) NOT NULL default '',
  msn varchar(100) NOT NULL default '',
  aim varchar(100) NOT NULL default '',
  yim varchar(100) NOT NULL default '',
  avatar varchar(100) NOT NULL default '',
  signature text NOT NULL,
  PRIMARY KEY  (username),
  UNIQUE KEY username (username),
  KEY username_2 (username)
) TYPE=MyISAM;";
echo "Creating table <b>profile</b>... ";
$q = mysql_query($query) or die ("<font face=cc0000>Failed!</font>");
echo "<font color=00A400>Succeeded!</font><br>";

$query = "CREATE TABLE ".$prefix."settings (
  skindir varchar(100) NOT NULL default '',
  startlevel int(11) NOT NULL default '0',
  startstatus tinyint(4) NOT NULL default '1',
  registration tinyint(4) NOT NULL default '0',
  comments tinyint(4) NOT NULL default '0',
  noposts int(11) NOT NULL default '0',
  archive int(11) NOT NULL default '0',
  gmt int(11) NOT NULL default '0',
  more varchar(32) NOT NULL default '',
  nocomments varchar(32) NOT NULL default ''
) TYPE=MyISAM;";
echo "Creating table <b>settings</b>... ";
$q = mysql_query($query) or die ("<font face=cc0000>Failed!</font>");
echo "<font color=00A400>Succeeded!</font><br>";

$query = "CREATE TABLE ".$prefix."users (
  uid bigint(20) NOT NULL auto_increment,
  username varchar(16) NOT NULL default '',
  password varchar(35) NOT NULL default '',
  level int(11) NOT NULL default '0',
  PRIMARY KEY  (uid),
  UNIQUE KEY uid (uid,username),
  KEY uid_2 (uid)
) TYPE=MyISAM;";
echo "Creating table <b>users</b>... ";
$q = mysql_query($query) or die ("<font face=cc0000>Failed!</font>");
echo "<font color=00A400>Succeeded!</font><br /><br />";

$query = "INSERT INTO ".$prefix."categories VALUES ('1','general');";
echo "Entering general category into table <b>categories</b>... ";
$q = mysql_query($query) or die ("<font face=cc0000>Failed!</font>");
echo "<font color=00A400>Succeeded!</font><br>";

$pass2 = md5($pass);

$query = "INSERT INTO ".$prefix."users VALUES ('1','$login', '$pass2', 4);";
echo "Entering admin account into table <b>users</b>... ";
$q = mysql_query($query) or die ("<font face=cc0000>Failed!</font>");
echo "<font color=00A400>Succeeded!</font><br>";

$query = "INSERT INTO ".$prefix."profile VALUES ('$login', '$login', '', '', 0, '', '', '', '$email', '', '', '', '', '', '', '');";
echo "Entering admin account profile into table <b>profile</b>... ";
$q = mysql_query($query) or die ("<font face=cc0000>Failed!</font>");
echo "<font color=00A400>Succeeded!</font><br>";

$query = "INSERT INTO ".$prefix."settings VALUES ('default', 0, 1, 0, 1, 10, 0, 0, '[more]', 'comments not allowed');";
echo "Entering settings into table <b>settings</b>... ";
$q = mysql_query($query) or die ("<font face=cc0000>Failed!</font>");
echo "<font color=00A400>Succeeded!</font><br /><br />

<strong>Your database has been correctly initialized.</strong><br /><br />
<form method='post' action='install.php'><input type='hidden' name='step' value='2'>
<input type='submit' value='proceed'>";

} elseif ($step == 2) {
echo "Congratulations, you've just installed Somery without any problems.<br /><br />
Don't forget to remove the install script to make certain nobody overwrites any information.<br /><br />";

$directory = ereg_replace("install.php", "", $_SERVER["PHP_SELF"]);
$host = "http://".$_SERVER["HTTP_HOST"].$directory;

echo "Also, to help me see how many times and where Somery is installed, <a href='http://somery.danwa.net/register.php?host=$host'>click here</a> to register your somery install. This is completely optional.<br /><br /><a href='admin/index.php'>Now log in</a> with $login/$pass and start posting!";
}
include("$skindir/footer.php");
?>