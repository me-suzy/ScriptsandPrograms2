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
if (!isset($sf)) $sf="delitem";


commonheader();
bodybegin();
logobar($logoname,$textlogo);
mainmenu($f);

if($result=mysql_query("SELECT title, RefId FROM rssitem WHERE ItemId='$itemid'"))
{
$row=mysql_fetch_row($result);
$itemtitle=$row[0];
$refid=$row[1];
}

echo "
<br><br>
<table>
<tr>
	<td valign=\"top\" width=\"20%\">";

submenu($f,$sf);

echo "
	</td>
	<td>
	<center><h2>RSS editor - delete an item.</h2></center><br /><br />
	<b>Are you sure you want to remove the item?</b><br />
<br />
	<center>
	<div id=\"formular\">
	<form action='delitemResponse.php' method='post'>
	<input type=\"hidden\" name=\"itemid\" value=\"$itemid\" />
	<input type=\"hidden\" name=\"refid\" value=\"$refid\" />
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>
	<td align=\"left\">
	Title of the item:
	</td>
	<td colspan=\"2\" align=\"left\">
	<b>$itemtitle</b>
	</td>
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"Yes, I'm sure, remove it\" />
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
