<?
require("../db.php");
require("include.php");
DBinfo();

mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");



$SUID=f_ip2dec($REMOTE_ADDR);
if (!session_id($SUID))
session_start();

$username=$_SESSION['uname'];
$password=$_SESSION['pass'];

$result=mysql_query("SELECT AdminId FROM mycmsadmin WHERE username='$username' and password='".sha1($password)."'");
$row=mysql_fetch_row($result);
$num_rows = mysql_num_rows($result);
$id=$row[0];



if ($_SESSION['signed_in']!='indeed' || $num_rows!=1 || $id!=1){
Header( "Location: index.php?action=2");
}else{



/* Checks whether the chosen alias already exists in the DB */

$result=mysql_query("SELECT PageId FROM pages WHERE Alias='$alias' limit 0,1");
$repeat=mysql_num_rows($result);

if ($repeat!=0 && $alias!=$alias_old) {Header("Location: addtomenu.php?action=1&name=$name&refid=$refid&alias_old=$alias_old&thiskeywords=$thiskeywords&thisdescription=$thisdescription&pagetype=$pagetype");}
else {

/* In case the table 'pages' contains no 'Type' attribute - add it! */

if (mysql_query("ALTER TABLE `pages` ADD `Type` VARCHAR( 255 ) NOT NULL AFTER `Active`"));

/* if the type is bulletin board - create the tables 'thread', 'post', 'user' */

if ($pagetype=="bb")
{
mysql_query("CREATE TABLE IF NOT EXISTS `user` (
`UserId` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`nick` VARCHAR( 255 ) NOT NULL ,
`password` VARCHAR( 255 ) NOT NULL ,
`email` VARCHAR( 255 ) NOT NULL ,
`key` VARCHAR( 255 ) NOT NULL ,
`active` INT( 1 ) NOT NULL ,
UNIQUE (
`UserId` 
)
) TYPE = MYISAM");

mysql_query("CREATE TABLE IF NOT EXISTS `thread` (
`ThrId` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`RefId` INT( 11 ) NOT NULL ,
`Thread` VARCHAR( 255 ) NOT NULL ,
`date` VARCHAR( 255 ) NOT NULL ,
`nick` VARCHAR( 255 ) NOT NULL ,
`text` TEXT NOT NULL ,
UNIQUE (
`ThrId` 
)
) TYPE = MYISAM");


mysql_query("CREATE TABLE IF NOT EXISTS `post` (
`PostId` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`RefId` INT( 11 ) NOT NULL ,
`Post` VARCHAR( 255 ) NOT NULL ,
`date` VARCHAR( 255 ) NOT NULL ,
`nick` VARCHAR( 255 ) NOT NULL ,
`text` TEXT NOT NULL ,
UNIQUE (
`PostId` 
)
) TYPE = MYISAM");


}

/* Create table 'news' if the pagetype is like that */

if ($pagetype=="news")
{
mysql_query("CREATE TABLE IF NOT EXISTS `news` (
`NewsId` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`RefId` INT( 11 ) NOT NULL ,
`Author` VARCHAR( 255 ) NOT NULL ,
`Title` VARCHAR( 255 ) NOT NULL ,
`Summary` TEXT NOT NULL ,
`date` VARCHAR( 255 ) NOT NULL ,
`active` INT( 1 ) NOT NULL ,
`Text` TEXT NOT NULL ,
UNIQUE (
`NewsId` 
)
) TYPE = MYISAM");


}





/* update the DB */
mysql_query("UPDATE pages SET Name='$name', Description='$thisdescription', Keywords='$thiskeywords', Active='$active', Alias='$alias', RefId='$refid', Type='$pagetype' WHERE PageId='$pageid'")
or die("Something went wrong: <br />".mysql_error());

/* Modification of the re-direction in the .htaccess file, if necessary */

if ($alias!="" && $alias!=$alias_old)
{
$file = "../.htaccess";
	$file_cont=file_get_contents($file);
	$file_cont_new=str_replace("$alias_old","$alias",$file_cont);
	$handle=fopen($file,"w");
	fwrite($handle,$file_cont_new);
	fclose($handle);
}


/* Create the page */

if (!isset($f)) $f="structure";
if (!isset($sf)) $sf="add";


commonheader();
bodybegin();
logobar($logoname,$textlogo);
mainmenu($f);


echo "
<br><br>
<table>
<tr>
	<td valign=\"top\" width=\"20%\">";

submenu($f,$sf);

echo "
	</td>
	<td>
	<center><h2>Control panel - the item has been successfully modified.</h2></center><br /><br />
	<b>You can now visit your page to check whether the changes are visible (provided the section was chosen to be visible).</b><br />
<br /><br />
<br />

	</td>
</tr>
</table>
 ";

bodyend();
commonfooter();


}
}
?>
