<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require('../config.php');
?>
<html><body>
<div align="center"><h3>FAQEngine: Upgrade v4.05 to v4.06</h3></div>
<br>
<?php
echo "Upgrading tables...<br>";
flush();
$sql="ALTER TABLE ".$tableprefix."_layout ";
$sql.="add srchtoolpic varchar(80) NOT NULL default 'gfx/srchtool.gif',";
$sql.="add donltrans tinyint(4) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_layout ".mysql_error());
$sql="ALTER TABLE ".$tableprefix."_settings ";
$sql.="add usebwlist tinyint(1) unsigned NOT NULL default '0'";
if(!$result = mysql_query($sql, $db))
	die("Unable to upgrade table ".$tableprefix."_settings ".mysql_error());
echo "Adding new tables...<br>";
flush();
// create table faq_bad_words
if(!table_exists($badwordprefix."_bad_words"))
{
	$sql = "CREATE TABLE ".$badwordprefix."_bad_words (";
	$sql.= "indexnr int(10) unsigned NOT NULL auto_increment,";
	$sql.= "word varchar(100) NOT NULL default '',";
	$sql.= "replacement varchar(100) NOT NULL default '',";
	$sql.= "PRIMARY KEY  (indexnr));";
	if(!$result = mysql_query($sql, $db))
		die("Unable to create table ".$badwordprefix."_bad_words");
}
?>
<br><div align="center">Upgrade done.<br>Please remove install.php, upgrade*.php, mkconfig.php and fill_*.php from server</div>
<div align="center">Now you can login to the <a href="index.php">admininterface</a></div>
</body></html>
<?php
function table_exists($searchedtable)
{
	global $dbname;

	$tables = mysql_list_tables($dbname);
	$numtables = @mysql_numrows($tables);
	for($i=0;$i<$numtables;$i++)
	{
		$tablename = mysql_tablename($tables,$i);
		if($tablename==$searchedtable)
			return true;
	}
	return false;
}
?>