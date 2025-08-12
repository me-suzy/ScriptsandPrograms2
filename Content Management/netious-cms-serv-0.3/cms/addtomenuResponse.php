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

if ($repeat!=0 && $alias!="") {Header("Location: addtomenu.php?action=1&name=$name&refid=$refid&thisdescription=$thisdescription&thiskeywords=$thiskeywords&pagetype=$pagetype");}
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




/* insert the values into the DB and read out its PageId */
mysql_query("INSERT into pages VALUES ('','$refid','$name','$thisdescription','$thiskeywords','$alias','0','$pagetype','')")
or die("Something went wrong: <br />".mysql_error());

$result=mysql_query("SELECT PageId FROM pages WHERE Name='$name' AND Alias='$alias' AND RefId='$refid' limit 0,1");
$row=mysql_fetch_row($result);
$pageid=$row[0];


/* Create the corresponding directories - root, images and files */

$rootdir="../sections/$pageid";
if (!file_exists($rootdir))
	{mkdir($rootdir, 0775);
	 chmod($rootdir, 0775);
	}

$imagedir="../sections/$pageid/images";
if (!file_exists($imagedir))
	{mkdir($imagedir, 0775);
	 chmod($imagedir, 0775);
	}


$filedir="../sections/$pageid/files";
if (!file_exists($filedir))
	{mkdir($filedir, 0775);
	 chmod($filedir, 0775);
	}


/* Addition of the re-direction to the .htaccess file, if necessary */

if ($alias!="")
{
$handle=fopen("../.htaccess","a");
fwrite($handle,"\n Redirect 301 /$alias $thisurl/index.php?pageid=$pageid");

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
	<center><h2>Control panel - the item has been successfully added.</h2></center><br /><br />
	<b>The new item is now stored in the DB, but it still does not appear in the navigation menu. It will be visible after the page is edited and saved for the first time. You can now upload the images/files for this section and compose the page.</b><br />
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
