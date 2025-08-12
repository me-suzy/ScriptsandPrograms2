<?
include "./config.php";

if ($create){
	$sql = "CREATE TABLE $table (
	  id int(11) NOT NULL auto_increment,
	  email varchar(255) NOT NULL default '',
	  password varchar(255) NOT NULL default '',
	  sitetitle varchar(255) NOT NULL default '',
	  siteurl varchar(255) NOT NULL default '',
	  headtext text NOT NULL,
	  bgcolor varchar(255) NOT NULL default '',
	  textcolor varchar(255) NOT NULL default '',
	  linkcolor varchar(255) NOT NULL default '',
	  uniquehits int(11) NOT NULL default '0',
	  pageviews int(11) NOT NULL default '0',
	  entries int(11) NOT NULL default '0',
	  created datetime NOT NULL default '0000-00-00 00:00:00',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql");

	$sql = "CREATE TABLE $adstable (
	  id int(11) NOT NULL auto_increment,
	  html text NOT NULL,
	  PRIMARY KEY  (id)
	) TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql");

	$sql = "CREATE TABLE $msgstable (
	  id int(11) NOT NULL auto_increment,
	  owner varchar(11) NOT NULL default '',
	  poster varchar(255) NOT NULL default '',
	  email varchar(255) NOT NULL default '',
	  message longtext NOT NULL,
	  ip varchar(20) NOT NULL default '',
	  dt datetime NOT NULL default '0000-00-00 00:00:00',
	  PRIMARY KEY  (id)
	)TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql");
	$sql = "insert into $adstable values('', '<a href=\"http://www.snipples.com\" target=\"_new\">Find anything on the web!</a>')";
	$result = mysql_query($sql) or die("Failed: $sql");
	$sql = "insert into $adstable values('', '<a href=\"http://www.loveprofiles.com\" target=\"_new\">Looking for friends, love or more? Create your own free photo profile!</a>')";
	$result = mysql_query($sql) or die("Failed: $sql");
	$sql = "insert into $adstable values('', '<a href=\"http://www.nukedweb.com\" target=\"_new\">NukedWeb.com</a> - Anything and everything for webmasters!')";
	$result = mysql_query($sql) or die("Failed: $sql");
	$sql = "insert into $adstable values('', '<a href=\"http://www.nukedweb.com/board/\" target=\"_new\">The Webmasters Board</a>')";
	$result = mysql_query($sql) or die("Failed: $sql");



	print "The MySQL tables <b>$table</b>, <b>$msgstable</b>, and <b>$adstable</b> have been created and are ready for use! I highly suggest that you now delete <b>create_tables.php</b> before you continue. Some default ads have been inserted, and can be changed through <b>admin.php</b>. It's highly recommended you leave at least one banner ad in place until you insert your own.";
	exit;
}
?>

<p>This script will automatically create the MySQL tables for GuestBookHost. 
  You MUST edit config.php before this script can be run. The 'Create Tables' 
  button will appear below if you've successfully given all the info needed to 
  do this. Please review the info below just to be sure:</p>
<p>Database Host: <? print $sqlhost; ?><br>
  Database Login: <? print $sqllogin; ?><br>
  Database Password: <? print $sqlpass; ?><br>
  Database Name: <? print $sqldb; ?><br>
  Websites Table: <? print $table; ?><br>
  Popup Ads Table: <? print $msgstable; ?><br>
  Popunder URLs Table: <? print $adstable; ?><br>
  <br>
  If this is all correct, click the 'Create Table' button below. If the button 
  does not show below, it's because one or more of these fields are empty.</p>
<?
if ($sqlhost && $sqllogin && $sqldb && $table && $adstable && $msgstable) print "<form name='form1' method='post' action='create_tables.php'>
  <div align='center'>
    <input type='hidden' name='create' value='1'>
    <input type='submit' value='Create Tables!'>
  </div><br><br><b>Note: This script will not create the \"$sqldb\" database. It only creates the tables in the database. You may need to create the database yourself if \"$sqldb\" does not exist.</b>
</form>";
?>
