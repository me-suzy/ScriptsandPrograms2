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

if (!isset($f)) $f="news";
if (!isset($sf)) $sf="delnews";


commonheader();
bodybegin();
logobar($logoname,$textlogo);
mainmenu($f);


$result=mysql_query("SELECT Author, Title, RefId FROM news WHERE NewsId='$newsid'");
$row=mysql_fetch_row($result);
$author=$row[0];
$ntitle=$row[1];
$refid=$row[2];

$result=mysql_query("SELECT ItemId, title FROM rssitem WHERE link='$thisurl/news.php?refid=$refid&amp;newsid=$newsid' limit 0,1");
$num_rows=mysql_num_rows($result);
if ($num_rows!=0)
{
$row=mysql_fetch_row($result);
$itemid=$row[0];
$itemtitle=$row[1];
} else $itemid="";



echo "
<br><br>
<table>
<tr>
	<td valign=\"top\" width=\"20%\">";

submenu($f,$sf);

echo "
	</td>
	<td>
	<center><h2>News Management - remove a news page.</h2></center><br /><br />
	<b></b><br />
<br />
	<center>
	<div id=\"formular\">
	<form action='delnewsResponse.php' method='post' name='selectref'>
	<input type=\"hidden\" name=\"itemid\" value=\"$itemid\" />
	<input type=\"hidden\" name=\"newsid\" value=\"$newsid\" />
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>";

echo "
	<td align=\"left\">
	Are you sure you want to remove:	
	</td>
	<td colspan=\"2\" align=\"left\">
	<b>News page: $ntitle ($author)	</b>";
	
	if ($itemid!="") echo "<br /><br /><b>Corresponding RSS item: $itemtitle </b>";

	echo "
	</td>
	</tr>
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"Remove\" />
	</td>";


echo "
	</tr>
	</table>
	</form>
	</div>
	</center>

	</td>
</tr>
</table>
 ";

bodyend();
commonfooter();



}
?>
