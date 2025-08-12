<?
include "./config.php";

if ($create){
	$sql = "CREATE TABLE `$table` (
	  `id` int(11) NOT NULL auto_increment,
	  `email` varchar(255) NOT NULL default '',
	  `aim` varchar(20) NOT NULL default '',
	  `icq` varchar(20) NOT NULL default '',
	  `yahoo` varchar(255) NOT NULL default '',
	  `homepage` varchar(255) NOT NULL default '',
	  `vote_a` int(11) NOT NULL default '0',
	  `vote_b` int(11) NOT NULL default '0',
	  `vote_c` int(11) NOT NULL default '0',
	  `vote_d` int(11) NOT NULL default '0',
	  `vote_e` int(11) NOT NULL default '0',
	  `picfile` varchar(255) NOT NULL default '',
	  `dt` datetime NOT NULL default '0000-00-00 00:00:00',
	  PRIMARY KEY  (`id`)
	) TYPE=MyISAM AUTO_INCREMENT=1 ;";
	$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());
	print "The MySQL table <b>$table</b> has been created and PhotoRate is ready for use! I highly suggest that you now delete <b>create_table.php</b> before you continue. :)";
	exit;

}
?>

<p>This script will automatically create the MySQL tables for PhotoRate. <b>You 
  MUST edit config.php before this script can be run</b>. The 'Create Table' button 
  will appear below if you've successfully given all the info needed to do this. 
  Please review the info below just to be sure:</p>
<p>Database Host: <? print $sqlhost; ?><br>
  Database Login: <? print $sqllogin; ?><br>
  Database Password: <? print $sqlpass; ?><br>
  Database Name: <? print $sqldb; ?><br>
  MySQL Table Name: 
  <? print $table; ?>
  <br>
  <br>
  <br>
  If this is all correct, click the 'Create Table' button below. If the button 
  does not show below, it's because one of these fields are empty.</p>
<?
if ($sqlhost && $sqllogin && $sqlpass && $sqldb && $table)print "<form name='form1' method='post' action='create_table.php'>
  <div align='center'>
    <input type='hidden' name='create' value='1'>
    <input type='submit' value='Create Table!'>
  </div>
</form>";
?>
