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
if (!isset($sf)) $sf="editchnl";




commonheader();
bodybegin();
logobar($logoname,$textlogo);
mainmenu($f);


if($result=mysql_query("SELECT RssId, Name FROM rsschannel order by RssId DESC"))
{$num_rows=mysql_num_rows($result);
} else $num_rows="0";


echo "
<br><br>
<table>
<tr>
	<td valign=\"top\" width=\"20%\">";

submenu($f,$sf);

echo "
	</td>
	<td>
	<center><h2>RSS editor - Edit an RSS channel.</h2></center><br /><br />
	<b>Choose the channel you want to edit</b><br />
<br />
	<center>
	<div id=\"formular\">
	<form enctype=\"multipart/form-data\" action='editchannel1.php' method='post'>
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>";


if ($num_rows!=0)
{echo "
	<td align=\"left\">
	Select channel:
	</td>
	<td colspan=\"2\" align=\"left\">
	<select name=\"rssid\">";
	if($result=mysql_query("SELECT RssId, Name FROM rsschannel order by RssId DESC"))
	{while ($row=mysql_fetch_row($result))
		{$thisrssid=$row[0];
		$thisname=$row[1];
		echo "<option value=\"$thisrssid\">$thisname</option>";
		}
	}
	echo "
	</select>
	</td>
	</tr>
	<tr>
	<td colspan=\"3\" align=\"center\">
	<input type=\"submit\" value=\"Edit\" />
	</td>";} else {echo "<td>There are no channels in the DB</td>";}

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
