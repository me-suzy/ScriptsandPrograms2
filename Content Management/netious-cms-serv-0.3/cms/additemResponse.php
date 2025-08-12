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

/* In case the tables do not exist... */
mysql_query("CREATE TABLE IF NOT EXISTS `rsschannel` (
  `RssId` int(3) NOT NULL auto_increment,
  `Name` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `language` varchar(20) NOT NULL default '',
  `copyright` varchar(255) NOT NULL default '',
  `managingEditor` varchar(255) NOT NULL default '',
  `webMaster` varchar(255) NOT NULL default '',
  `pubDate` varchar(255) NOT NULL default '',
  `lastBuildDate` varchar(255) NOT NULL default '',
  `ttl` varchar(11) NOT NULL default '',
  `img_url` varchar(255) NOT NULL default '',
  `img_title` varchar(255) NOT NULL default '',
  `img_link` varchar(255) NOT NULL default '',
  `img_width` varchar(4) NOT NULL default '',
  `img_height` varchar(4) NOT NULL default '',
  `img_description` text NOT NULL,
  UNIQUE KEY `RssId` (`RssId`)
) TYPE=MyISAM AUTO_INCREMENT=1") or die("Something went wrong (create rsschannel:".mysql_error()); 

mysql_query("CREATE TABLE IF NOT EXISTS `rssitem` (
  `ItemId` int(5) NOT NULL auto_increment,
  `RefId` int(4) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `link` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `author` varchar(255) NOT NULL default '',
  `pubDate` varchar(50) NOT NULL default '',
  UNIQUE KEY `ItemId` (`ItemId`)
) TYPE=MyISAM AUTO_INCREMENT=1") or die("Something went wrong (create rssitem):".mysql_error());

/* And the rss directory! */

if (!file_exists("../rss"))
	{mkdir("../rss",0775);
	chmod("../rss",0775);}




/* Checks whether one of the required fields were filled */

if ($itemtitle=="" && $itemdescription=="") {Header("Location:additem.php?action=1&itemtitle=$itemtitle&itemdescription=$itemdescription&link=$link&author=$author&refid=$refid");}
else {

/* prepare the data to be inserted into the DB and save the image, if it is uploaded */


/* Read out the date */
$pubDate=date("r");


/* Insert into... */

mysql_query("INSERT into rssitem VALUES ('','$refid','$itemtitle','$link','$itemdescription','$author','$pubDate')");

mysql_query("UPDATE rsschannel SET lastBuildDate='$pubDate', pubDate='$pubDate' WHERE RssId='$refid'");


/* Read out the item's id to create the corresponding media directory */

$media=$_FILES['media']['tmp_name'];
$media_name=$_FILES['media']['name'];

if ($media!="")
{$result=mysql_query("SELECT Name FROM rsschannel WHERE RssId='$refid'");
$row=mysql_fetch_row($result);
$channelname=$row[0];

$result=mysql_query("SELECT ItemId FROM rssitem WHERE RefId='$refid' AND title='$title' AND link='$link' AND description='$description' limit 0,1");
$row=mysql_fetch_row($result);
$itemid=$row[0];

$directory="../rss/$channelname/$itemid";

if (!file_exists($directory))
	{mkdir($directory,0777);
	chmod($directory,0777);
	}
$media_location="$directory/$media_name";
copy ($media,$media_location);
}

Header("Location:makerss.php?rssid=$refid");


}
}
?>
