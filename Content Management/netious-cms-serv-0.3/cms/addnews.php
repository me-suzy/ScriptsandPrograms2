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
if (!isset($sf)) $sf="addnews";

if (!isset($action)) $action="0";

if (!isset($ntitle)) $ntitle="";
if (!isset($nsummary)) $nsummary="";
if (!isset($author)) $author="";



commonheader();
bodybegin();
logobar($logoname,$textlogo);
mainmenu($f);

if ($result=mysql_query("SELECT * FROM pages WHERE type='news'")) {$num_news=mysql_num_rows($result);} else {$num_news="0";}


if ($result=mysql_query("SELECT RssId, Name FROM rsschannel order by RssId DESC")) {$num_channels=mysql_num_rows($result);} else {$num_channels="0";}



echo "
<br><br>
<table>
<tr>
	<td valign=\"top\" width=\"20%\">";

submenu($f,$sf);

echo "
	</td>
	<td>
	<center><h2>News Management - add a news item.</h2></center><br /><br /><b>Here you can give only the basic information. You can upload files and edit the content in the next steps.
	<br />";

if ($action=="1") echo "<b>Write at least the title!</b>";

echo "
<br />
	<center>
	<div id=\"formular\">
	<form enctype=\"multipart/form-data\" action='addnewsR1.php' method='post'>
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>";

if ($num_news!=0)
{echo "
	<td align=\"left\">
	Title:
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" name=\"ntitle\" value=\"$ntitle\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Summary:
	</td>
	<td colspan=\"2\" align=\"left\">
	<textarea name=\"nsummary\" rows=\"5\" cols=\"30\">$nsummary</textarea>
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Author:
	</td>
	<td colspan=\"2\" align=\"left\">
	<input type=\"text\" name=\"author\" value=\"$author\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	The \"News\" - type section:
	</td>
	<td>
	<select name=\"refid\">";

	if ($result=mysql_query("SELECT PageId, Name FROM pages WHERE type='news'")) {while ($row=mysql_fetch_row($result))
		{$thisrefid=$row[0];
		$thisname=$row[1];
		if ($thisrefid==$refid) {echo "<option selected=\"selected\" value=\"$thisrefid\">$thisname</option>";} else {echo "<option value=\"$thisrefid\">$thisname</option>";}

		}
	}


	echo "</select></td></tr>

";

if ($num_channels!=0) {echo "
	<tr>
	<td align=\"left\">
	Create an RSS item (if yes, select the channel):
	</td>
	<td colspan=\"2\" align=\"left\">
	<select name=\"chnlid\">
	<option value=\"\">Do not create an RSS item for this publication</option>
	";
	if ($result=mysql_query("SELECT RssId, Name FROM rsschannel order by RssId DESC"))
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
	</tr>";} else {echo "<input type=\"hidden\" name=\"chnlid\" value=\"\" />";}
	
echo "
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"Add the item\" />
	</td>";} else {echo "<td>Warning: there is no section with the \"News (headers)\" type!</td>";}

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
