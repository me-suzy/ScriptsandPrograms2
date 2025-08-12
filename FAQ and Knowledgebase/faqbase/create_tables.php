<?
include "./faq-config.php";

if ($create){
	$sql = "CREATE TABLE faq_categories (
	  id int(20) NOT NULL auto_increment,
	  cat varchar(255) NOT NULL default '',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql");

	$sql = "CREATE TABLE faq_entries (
	  id int(20) NOT NULL auto_increment,
	  catid int(20) NOT NULL default '0',
	  question varchar(255) NOT NULL default '',
	  answer text NOT NULL,
	  added datetime NOT NULL default '0000-00-00 00:00:00',
	  PRIMARY KEY  (id)
	) TYPE=MyISAM;";
	$result = mysql_query($sql) or die("Failed: $sql");

	print "The MySQL tables <b>$table</b> and <b>$faqcats</b> have been created and are ready for use! I highly suggest that you now delete <b>create_tables.php</b> before you continue.";
	exit;
}
?>

<p>This script will automatically create the MySQL tables for FAQBase. 
  You MUST edit faq-config.php before this script can be run. The 'Create Tables' 
  button will appear below if you've successfully given all the info needed to 
  do this. Please review the info below just to be sure:</p>
<p>Database Host: <? print $sqlhost; ?><br>
  Database Login: <? print $sqllogin; ?><br>
  Database Password: <? print $sqlpass; ?><br>
  Database Name: <? print $sqldb; ?><br>
  FAQ Entries Table: <? print $table; ?><br>
  FAQ Categories Table: <? print $faqcats; ?><br>
  <br>
  If this is all correct, click the 'Create Table' button below. If the button 
  does not show below, it's because one or more of these fields are empty.</p>
<?
if ($sqlhost && $sqllogin && $sqldb && $table && $faqcats) print "<form name='form1' method='post' action='create_tables.php'>
  <div align='center'>
    <input type='hidden' name='create' value='1'>
    <input type='submit' value='Create Tables!'>
  </div><br><br><b>Note: This script will not create the \"$sqldb\" database. It only creates the tables in the database. You may need to create the database yourself if \"$sqldb\" does not exist.</b>
</form>";
?>
