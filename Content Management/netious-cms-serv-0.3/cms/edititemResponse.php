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

/* Checks whether one of the required fields were filled */

if ($itemtitle=="" && $itemdescription=="") {Header("Location:edititem1.php?action=1&itemtitle=$itemtitle&itemdescription=$itemdescription&link=$link&author=$author&refid=$refid");}
else {

/* prepare the data to be inserted into the DB and save the image, if it is uploaded */


/* Update DB */

$pubDate=date("r");

mysql_query("UPDATE rssitem SET title='$itemtitle', link='$link', description='$itemdescription',author='$author', RefId='$refid' WHERE ItemId='$itemid'") or die(mysql_error());

mysql_query("UPDATE rsschannel SET lastBuildDate='$pubDate' WHERE RssId='$refid'");

/* Read out the item's id to replace the corresponding media */

$media=$_FILES['media']['tmp_name'];
$media_name=$_FILES['media']['name'];

if ($media!="")
{$result=mysql_query("SELECT Name FROM rsschannel WHERE RssId='$refid'");
$row=mysql_fetch_row($result);
$channelname=$row[0];

$directory="../rss/$channelname/$itemid";

if (file_exists($directory))
	{$dh=opendir($directory);
		while($file=readdir($dh))
			{if ($file!="." && $file!="..") unlink("$directory/$file");
			}
		closedir($directory);
	} else {mkdir($directory,0777);
		chmod($directory,0777);
		}
$media_location="$directory/$media_name";
copy ($media,$media_location); 
}

Header("Location:makerss.php?rssid=$refid");


}
}
?>
