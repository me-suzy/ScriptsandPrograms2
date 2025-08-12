<?
if ($_GET['a'] == "") {
?>
<br><br><div align="center"><h2>Welcome to aWebBB</h2><br><br>
After editing the configuration file located at /forum/config.php please click <a href="upgrade.php?a=upgrade">here</a> to upgrade from version 1.0 or  <a href="upgrade.php?a=upgrade1">here</a> for 1.1.<br>
</div>

<?
} else { }
if ($_GET['a'] == "upgrade") {
echo "Updating MYSQL Tables...<br>";
include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = 'ALTER TABLE `prefs` ADD `adenable` VARCHAR(11), ADD `adcode` TEXT, ADD `adlocation` VARCHAR(20), ADD `email` VARCHAR( 100 )';
$result = mysql_query($query); 
echo "Updated Prefs Table<br>";
$query7 = 'CREATE TABLE `menu` (
  `id` int(11) NOT NULL auto_increment,
  `bname` varchar(50) default NULL,
  `link` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
)';
$result7 = mysql_query($query7); 
echo "Created Menu Table<br>";

echo '<meta http-equiv="refresh" content="1;url=upgrade.php?a=done">'; 
} else { }
if ($_GET['a'] == "upgrade1") {
echo "Updating MYSQL Tables...<br>";
include "../config.php";
$db = mysql_connect($db_host,$db_user,$db_pass); 
mysql_select_db ($db_name) or die ("Cannot connect to database"); 
$query = 'ALTER TABLE `prefs` ADD `adenable` VARCHAR(11), ADD `adcode` TEXT, ADD `adlocation` VARCHAR(20)';
$result = mysql_query($query); 
echo "Updated Prefs Table<br>";

$query7 = 'CREATE TABLE `menu` (
  `id` int(11) NOT NULL auto_increment,
  `bname` varchar(50) default NULL,
  `link` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
)';
$result7 = mysql_query($query7); 
echo "Created Menu Table<br>";

echo '<meta http-equiv="refresh" content="1;url=upgrade.php?a=done">'; 
} else { }
if ($_GET['a'] == "done") {
?>
<div align="center">
<br><br>All done!!!<br><br>You can procede to the <a href="index.php">Admin section</a> or the <a href="../index.php">forum</a>.<br><br><font color="red"><h2>Remember to delete upgrade.php AND install.php for security reasons!!!</h2></font>
</div>
<?
} else { }
?>



