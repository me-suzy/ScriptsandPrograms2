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
if (!isset($sf)) $sf="edititem";

if (!isset($action)) $action="0";

if (!isset($itemtitle)) 
{if ($result=mysql_query("SELECT title, link, description, author FROM rssitem WHERE ItemId='$itemid'"))
{$row=mysql_fetch_row($result);
$itemtitle=$row[0];
$link=$row[1];
$itemdescription=$row[2];
$author=$row[3];}
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
	<center><h2>RSS editor - edition of an item.</h2></center><br /><br />
	<b>All fields are optional, but one of title or description needs to be specified</b><br />
<br />";

if ($action=="1") echo "<b style=\"color:red\">Specify at least title or description</b>";


echo "
	<center>
	<div id=\"formular\">
	<form enctype=\"multipart/form-data\" action='edititemResponse.php' method='post'>
	<input type=\"hidden\" name=\"itemid\" value=\"$itemid\" />
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>
	<td align=\"left\">
	Title:
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" name=\"itemtitle\" value=\"$itemtitle\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Description:
	</td>
	<td colspan=\"2\" align=\"left\">
	<textarea name=\"itemdescription\" rows=\"5\" cols=\"30\">$itemdescription</textarea>
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Link to the item (article, publication etc.):
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" name=\"link\" value=\"$link\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Author (e-mail address):
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" name=\"author\" value=\"$author\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\" colspan=\"3\">
	Media (attached image, audio, video file) - attach if you want to replace the old one:
	</td>
	</tr>
	<tr>
	<td>
	</td>
	<td align=\"left\">
	Upload the file:
	</td>
	<td align=\"left\">
	<input type=\"file\" name=\"media\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	The channel:
	</td>
	<td colspan=\"2\" align=\"left\">
	<select name=\"refid\">
	";
	if($result=mysql_query("SELECT RssId, Name FROM rsschannel order by RssId DESC"))
	{
	while ($row=mysql_fetch_row($result))
		{$thisrefid=$row[0];
		$thisname=$row[1];
		if ($thisrefid==$refid) echo "<option selected=\"selected\" value=\"$thisrefid\">$thisname</option>";
		else echo "<option value=\"$thisrefid\">$thisname</option>";
		}
	}
	echo "
	</select>
	</td>
	</tr>
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"Save\" />
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
