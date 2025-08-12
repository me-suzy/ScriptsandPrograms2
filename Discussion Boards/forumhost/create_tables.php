<?
include "./config.php";

if ($create){
	$sql = "CREATE TABLE `$table` (
	  id int(20) NOT NULL auto_increment,
	  email varchar(255) NOT NULL default '',
	  password varchar(255) NOT NULL default '',
	  forumtitle varchar(100) NOT NULL default '',
	  forumdescr varchar(255) NOT NULL default '',
	  headhtml text NOT NULL,
	  foothtml text NOT NULL,
	  headtext text NOT NULL,
	  bottomtext text NOT NULL,
	  mybordercolor varchar(15) NOT NULL default '',
	  mybordersize int(2) NOT NULL default '0',
	  mycellspacing int(2) NOT NULL default '0',
	  mycellpadding int(2) NOT NULL default '0',
	  bannedippost longtext NOT NULL,
	  bannedipforum longtext NOT NULL,
	  enablesmilies int(1) NOT NULL default '0',
	  profanityfilter int(1) NOT NULL default '0',
	  allowimages int(1) NOT NULL default '0',
	  lastpost datetime NOT NULL default '0000-00-00 00:00:00',
	  created datetime NOT NULL default '0000-00-00 00:00:00',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());

	$sql = "CREATE TABLE `$tableposts` (
	  id int(20) NOT NULL auto_increment,
	  owner int(20) NOT NULL default '0',
	  pid int(20) NOT NULL default '0',
	  indent int(2) NOT NULL default '0',
	  author varchar(255) NOT NULL default '',
	  email varchar(255) NOT NULL default '',
	  website varchar(255) NOT NULL default '',
	  msgrname varchar(100) NOT NULL default '',
	  msgrtype varchar(5) NOT NULL default '',
	  msgicon varchar(255) NOT NULL default '0',
	  subject varchar(255) NOT NULL default '',
	  msg text NOT NULL,
	  filename varchar(255) NOT NULL default '',
	  ip varchar(15) NOT NULL default '',
	  dt datetime NOT NULL default '0000-00-00 00:00:00',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());

	$sql = "CREATE TABLE `$tableonline` (
	  owner int(20) NOT NULL default '0',
	  ip varchar(20) NOT NULL default '',
	  lastaccess datetime NOT NULL default '0000-00-00 00:00:00'
	) TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());

	print "The MySQL tables <b>$table</b>, <b>$tableposts</b> and <b>$tableonline</b> have been created and ForumHost is ready for use! I highly suggest that you now delete <b>create_tables.php</b> before you continue. :)";
	exit;
}
?>

<p>This script will automatically create the MySQL tables for ForumHost. <b>You 
  MUST edit config.php before this script can be run</b>. The 'Create Tables' button 
  will appear below if you've successfully given all the info needed to do this. 
  Please review the info below just to be sure:</p>
<p>Database Host: <? print $sqlhost; ?><br>
  Database Login: <? print $sqllogin; ?><br>
  Database Password: <? print $sqlpass; ?><br>
  Database Name: <? print $sqldb; ?><br>
  'Users' MySQL Table Name: <? print $table; ?>
  <br>
  'Posts' MySQL Table Name: <? print $tableposts; ?>
  <br>
  'Online Users' MySQL Table Name: <? print $tableonline; ?>
  <br>
  If this is all correct, click the 'Create Tables' button below. If the button 
  does not show below, it's because one of these fields are empty.</p>
<?
if ($sqlhost && $sqllogin && $sqlpass && $sqldb && $table && $tableposts && $tableonline) print "<form name='form1' method='post' action='create_tables.php'>
  <div align='center'>
    <input type='hidden' name='create' value='1'>
    <input type='submit' value='Create Tables!'>
  </div>
</form>";
?>
