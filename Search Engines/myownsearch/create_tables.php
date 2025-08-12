<?
include "./config.php";

if ($create){
	include "./mysql.php";
	$sql = "CREATE TABLE $adstable (
	  id int(15) NOT NULL auto_increment,
	  keyword varchar(255) NOT NULL default '',
	  ad varchar(255) NOT NULL default '',
	  url varchar(255) NOT NULL default '',
	  clicks int(15) NOT NULL default '0',
	  impressions int(15) NOT NULL default '0',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql");

	$sql = "CREATE TABLE $table (
	  id int(11) NOT NULL auto_increment,
	  url varchar(255) NOT NULL default '0',
	  title varchar(255) NOT NULL default '0',
	  descr varchar(255) NOT NULL default '0',
	  clicks int(11) NOT NULL default '0',
	  dateentered datetime NOT NULL default '0000-00-00 00:00:00',
	  PRIMARY KEY  (id),
	  UNIQUE KEY url (url)
	) TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql");

	$sql = "CREATE TABLE $enginestable (
	  engorder int(5) NOT NULL default '0',
	  engine varchar(255) NOT NULL default '',
	  cache int(1) NOT NULL default '0'
	) TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql");
	$sql = "insert into $enginestable values('1', 'dmoz', '1')";
	$result = mysql_query($sql);
	$sql = "insert into $enginestable values('2', 'msn', '1')";
	$result = mysql_query($sql);

	print "The MySQL tables <b>$table</b>, <b>$adstable</b>, and <b>$enginestable</b> have been created and are ready for use! I highly suggest that you now delete <b>create_tables.php</b> before you continue.";
	exit;
}
?>

<p>This script will automatically create the MySQL tables for your search engine. 
  You MUST edit config.php before this script can be run. The 'Create Tables' 
  button will appear below if you've successfully given all the info needed to 
  do this. Please review the info below just to be sure:</p>
<p>Database Host: <? print $sqlhost; ?><br>
  Database Login: <? print $sqllogin; ?><br>
  Database Password: <? print $sqlpass; ?><br>
  Database Name: <? print $sqldb; ?><br>
  Websites Table: <? print $table; ?><br>
  Advertisements Table: <? print $adstable; ?><br>
  Meta Engines Table: <? print $enginestable; ?><br>
  <br>
  If this is all correct, click the 'Create Table' button below. If the button 
  does not show below, it's because one of these fields are empty.</p>
<?
if ($sqlhost && $sqllogin && $sqldb && $table && $adstable && $enginestable) print "<form name='form1' method='post' action='create_tables.php'>
  <div align='center'>
    <input type='hidden' name='create' value='1'>
    <input type='submit' value='Create Tables!'>
  </div>
</form>";
?>
