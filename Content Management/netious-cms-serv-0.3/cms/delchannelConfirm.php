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

if (!isset($f)) $f="rss";
if (!isset($sf)) $sf="delchnl";

if (!isset($name))
{if ($result=mysql_query("SELECT Name, title FROM rsschannel WHERE RssId='$rssid'"))
{$row=mysql_fetch_row($result);
$name=$row[0];
$chnltitle=$row[1];}
}

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
	<b>Are you sure you want to remove the selected channel and all of its items?</b><br />
<br />

	<center>
	<div id=\"formular\">
	<form enctype=\"multipart/form-data\" action='delchannelResponse.php' method='post'>
	<input type=\"hidden\" name=\"rssid\" value=\"$rssid\" />
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>
	<td align=\"left\">
	Name of the channel:
	</td>
	<td colspan=\"2\" align=\"left\">
	<b>$name</b>
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Title:
	</td>
	<td colspan=\"2\" align=\"left\">
	<b>$chnltitle</b>
	</td>
	</tr>
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"I'm sure. Remove it.\" />
	</td>
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
