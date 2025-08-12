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


/* Remove the directory */

if ($result=mysql_query("SELECT Name FROM rsschannel WHERE RssId='$rssid'"))
{
$row=mysql_fetch_row($result);
$name=$row[0];

$dirname="../rss/$name";

/* Clean all the possible media files and dirs before proceeding */

$result=mysql_query("SELECT ItemId FROM rssitem WHERE RefId='$rssid'");
while ($row=mysql_fetch_row($result))
	{$itemid=$row[0];
	$subdirname="$dirname/$itemid";
	if(file_exists($subdirname))
		{$dh=opendir($subdirname);
		while ($file=readdir($dh))
			{if ($file!="." && $file!="..") unlink("$subdirname/$file");
			}
		closedir($dh);
		rmdir($subdirname);
		}
	}

$dh=opendir($dirname);
while ($file=readdir($dh))
	{if ($file!="." && $file!="..") unlink("$dirname/$file");
	}
closedir($dh);
rmdir($dirname);


/* Remove from the DB */

mysql_query("DELETE FROM rsschannel WHERE RssId='$rssid'") or die("Something went wrong".mysql_error());

mysql_query("DELETE FROM rssitem WHERE RefId='$rssid'") or die("Something went wrong".mysql_error());
}


if (!isset($f)) $f="rss";
if (!isset($sf)) $sf="delchnl";


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
	<center><h2>RSS editor - delete an existing RSS channel.</h2></center><br /><br />
	<b>The channel and its items removed successfully.</b><br />
<br />
	</td>
</tr>
</table>
 ";

bodyend();
commonfooter();



}
?>
