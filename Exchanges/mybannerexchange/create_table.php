<?
include "./config.php";

if ($create){
	$sql = "CREATE TABLE $table (
	  id int(11) NOT NULL auto_increment,
	  email varchar(255) NOT NULL default '',
	  password varchar(255) NOT NULL default '',
	  title varchar(255) NOT NULL default '',
	  url varchar(255) NOT NULL default '',
	  banner varchar(255) NOT NULL default '',
	  textad varchar(255) NOT NULL default '',
	  tablebg varchar(255) NOT NULL default '',
	  tablebdr varchar(255) NOT NULL default '',
	  tableclr varchar(255) NOT NULL default '',
	  cat varchar(255) NOT NULL default '',
	  myimpressions int(11) NOT NULL default '0',
	  myclicks int(11) NOT NULL default '0',
	  siteimpressions int(11) NOT NULL default '0',
	  siteclicks int(11) NOT NULL default '0',
	  created datetime NOT NULL default '0000-00-00 00:00:00',
	  lastclickin datetime NOT NULL default '0000-00-00 00:00:00',
	  exempt int(1) NOT NULL default '0',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql");

	print "The MySQL table <b>$table</b> has been created and are ready for use! I highly suggest that you now delete <b>create_table.php</b> before you continue.";
	exit;
}
?>

<p>This script will automatically create the MySQL table for MyBannerExchange. 
  You MUST edit config.php before this script can be run. The 'Create Table' button 
  will appear below if you've successfully given all the info needed to do this. 
  Please review the info below just to be sure:</p>
<p>Database Host: <? print $sqlhost; ?><br>
  Database Login: <? print $sqllogin; ?><br>
  Database Password: <? print $sqlpass; ?><br>
  Database Name: <? print $sqldb; ?><br>
  Table Name: 
  <? print $table; ?>
  <br>
  <br>
  <br>
  If this is all correct, click the 'Create Table' button below. If the button 
  does not show below, it's because one of these fields are empty.</p>
<?
if ($sqlhost && $sqllogin && $sqldb && $table) print "<form name='form1' method='post' action='create_table.php'>
  <div align='center'>
    <input type='hidden' name='create' value='1'>
    <input type='submit' value='Create Table!'>
  </div><br><br><b>Note: This script will not create the \"$sqldb\" database. It only creates the tables in the database. You may need to create the database yourself if \"$sqldb\" does not exist.</b>
</form>";
?>
