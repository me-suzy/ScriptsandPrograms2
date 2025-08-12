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

$pubDate=date("r");

/* Remove from the DB */

mysql_query("DELETE FROM rssitem WHERE ItemId='$itemid'") or die(mysql_error());
mysql_query("UPDATE rsschannel SET lastBuildDate='$pubDate' WHERE RssId='$refid'");

/* Remove the media */


$result=mysql_query("SELECT Name FROM rsschannel WHERE RssId='$refid'");
$row=mysql_fetch_row($result);
$channelname=$row[0];

$directory="../rss/$channelname/$itemid";

if (file_exists($directory))
	{$dh=opendir($directory);
		while($file=readdir($dh))
			{if ($file!="." && $file!="..") unlink("$directory/$file");
			}
		closedir($dh);
		rmdir($directory);
	}

Header("Location:makerss.php?rssid=$refid");


}

?>
