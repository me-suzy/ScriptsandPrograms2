<?
include "./config.php";

if ($create){
	$sql = "CREATE TABLE `$tableusers` (
	  id int(20) NOT NULL auto_increment,
	  email varchar(255) NOT NULL default '',
	  password varchar(255) NOT NULL default '',
	  totalviews int(20) NOT NULL default '0',
	  lastupload datetime NOT NULL default '0000-00-00 00:00:00',
	  exempt_views int(1) NOT NULL default '0',
	  exempt_date int(1) NOT NULL default '0',
	  exempt_overlay int(1) NOT NULL default '0',
	  created datetime NOT NULL default '0000-00-00 00:00:00',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());

	$sql = "CREATE TABLE `$tablepics` (
	  id int(20) NOT NULL auto_increment,
	  owner int(20) NOT NULL default '0',
	  filename varchar(255) NOT NULL default '',
	  filesize int(20) NOT NULL default '0',
	  views int(20) NOT NULL default '0',
	  uploaded datetime NOT NULL default '0000-00-00 00:00:00',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());

	print "The MySQL tables <b>$tableusers</b> and <b>$tablepics</b> have been created and FreeImageHost is ready for use! I highly suggest that you now delete <b>create_tables.php</b> before you continue. :)";
	exit;
}
?>

<p>This script will automatically create the MySQL tables for FreeImageHost. <b>You 
  MUST edit config.php before this script can be run</b>. The 'Create Tables' button 
  will appear below if you've successfully given all the info needed to do this. 
  Please review the info below just to be sure:</p>
<p>Database Host: <? print $sqlhost; ?><br>
  Database Login: <? print $sqllogin; ?><br>
  Database Password: <? print $sqlpass; ?><br>
  Database Name: <? print $sqldb; ?><br>
  'Users' MySQL Table Name: <? print $tableusers; ?>
  <br>
  'Images' MySQL Table Name: <? print $tablepics; ?>
  <br>
  If this is all correct, click the 'Create Tables' button below. If the button 
  does not show below, it's because one of these fields are empty.</p>
<?
if ($sqlhost && $sqllogin && $sqlpass && $sqldb && $tableusers && $tablepics)print "<form name='form1' method='post' action='create_tables.php'>
  <div align='center'>
    <input type='hidden' name='create' value='1'>
    <input type='submit' value='Create Tables!'>
  </div>
</form>";
?>
