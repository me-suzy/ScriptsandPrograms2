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


if (!isset($refid))
{
if ($result=mysql_query("SELECT RssId, Name FROM rsschannel order by RssId DESC limit 0,1"))
{
$row=mysql_fetch_row($result);
$refid=$row[0];
}
}

commonheader();
bodybegin();
logobar($logoname,$textlogo);
mainmenu($f);


if ($result=mysql_query("SELECT RssId, Name FROM rsschannel order by RssId DESC")) {$num_channels=mysql_num_rows($result);} else {$num_channels="0";}

if ($result=mysql_query("SELECT ItemId, title FROM rssitem WHERE RefId='$refid' order by ItemId DESC")) {$num_items=mysql_num_rows($result);} else {$num_items="0";}


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
	<b></b><br />
<br />
	<center>
	<div id=\"formular\">
	<form action='delitem.php' method='post' name='selectref'>
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>";

if ($num_channels!=0)
{echo "

	<td align=\"left\">
	Select the channel to which the item belongs:
	</td>
	<td colspan=\"2\" align=\"left\">
	<select name=\"refid\" onchange=\"document.selectref.submit()\">";
	if ($result=mysql_query("SELECT RssId, Name FROM rsschannel order by RssId DESC")){
	while($row=mysql_fetch_row($result))
		{$thisrefid=$row[0];
		$thisname=$row[1];
		if ($thisrefid==$refid) echo "<option selected=\"selected\" value=\"$thisrefid\">$thisname</option>"; else echo "<option value=\"$thisrefid\">$thisname</option>";
		}
	}
	echo "
	</select>	
	</td>
	</tr>
	</table>
	</form>
	<form action='delitemConfirm.php' method='post'>
	<input type=\"hidden\" name=\"refid\" value=\"$refid\" />
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>";

	if ($num_items!=0)
	{echo "
	<td align=\"left\">
	Select the item from this channel:
	</td>
	<td colspan=\"2\" align=\"left\">
	<select name=\"itemid\">";
	if ($result=mysql_query("SELECT ItemId, title FROM rssitem WHERE RefId='$refid' order by ItemId DESC"))
	{
	while($row=mysql_fetch_row($result))
		{$thisitemid=$row[0];
		$thistitle=$row[1];
		echo "<option value=\"$thisitemid\">$thistitle</option>"; 
		}
	}
	echo "
	</select>
	</td>
	</tr>
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"Delete\" />
	</td>";} else {echo "<td>There are no items in this channel</td>";}

} else {echo "<td>No channels, no items in the DB. Noting to remove.</td>";}


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
