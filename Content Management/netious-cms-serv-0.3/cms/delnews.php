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


if (!isset($refid))
{
if ($result=mysql_query("SELECT PageId, Name FROM pages WHERE Type='news' order by PageId DESC limit 0,1")){
$row=mysql_fetch_row($result);
$refid=$row[0];}
}

commonheader();
bodybegin();
logobar($logoname,$textlogo);
mainmenu($f);


if($result=mysql_query("SELECT PageId, Name FROM pages WHERE Type='news' order by PageId DESC")) {$num_newspages=mysql_num_rows($result);} else {$num_newspages="0";}

if ($result=mysql_query("SELECT NewsId, Title FROM news WHERE RefId='$refid' order by NewsId DESC")) {$num_items=mysql_num_rows($result);} else {$num_items="0";}


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
	<form action='delnews.php' method='post' name='selectref'>
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>";

if ($num_newspages!=0)
{echo "
	<td align=\"left\">
	Select the 'News (headers)' - type section:
	</td>
	<td colspan=\"2\" align=\"left\">
	<select name=\"refid\" onchange=\"document.selectref.submit()\">";
	if($result=mysql_query("SELECT PageId, Name FROM pages WHERE Type='news' order by PageId DESC"))
	{while($row=mysql_fetch_row($result))
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
	<form action='delnewsConfirm.php' method='post'>
	<input type=\"hidden\" name=\"refid\" value=\"$refid\" />
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>";
	
	if ($num_items!=0)
	{echo "
	<td align=\"left\">
	Select the news page:
	</td>
	<td colspan=\"2\" align=\"left\">
	<select name=\"newsid\">";
	if ($result=mysql_query("SELECT NewsId, Title FROM news WHERE RefId='$refid' order by NewsId DESC"))
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
	<input type=\"submit\" value=\"Remove\" />
	</td>";} else {echo "<td>There are no news in this sections.</td>";}




} else {echo "<td>Warning: no News - type pages, no news, nothing to remove!</td>";}

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
