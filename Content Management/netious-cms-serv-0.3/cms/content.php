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

if (!isset($f)) $f="content";
if (!isset($sf)) $sf="";

commonheader();
bodybegin();
logobar($logoname,$textlogo);
mainmenu($f);

$result=mysql_query("SELECT PageId, Name FROM pages WHERE RefId='0' order by PageId");
$num_rows=mysql_num_rows($result);




echo "
<br><br>
<table>
<tr>
	<td valign=\"top\" width=\"20%\">";

submenu($f,$sf);

echo "
	</td>
	<td>
	<center><h2>Control panel - edit the page content.</h2></center><br /><br />
	<b>Choose the section</b><br />
<br /><br />
<br />
	<center>
	<div id=\"formular\">
	<form action='editcontent.php' method='post'>
	<table width=\"50%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>";

if ($num_rows!=0)
{echo "
	<td align=\"left\">
	Section to be modified:
	</td>
	<td align=\"left\">
	<select name=\"pageid\">
";

while ($row=mysql_fetch_row($result))
	{$thispageid=$row[0];
	$thisname=$row[1];
	echo "<option value=\"$thispageid\">$thisname</option>";
	$subresult=mysql_query("SELECT PageId, Name FROM pages WHERE RefId='$thispageid' order by PageId");
	while ($subrow=mysql_fetch_row($subresult))
		{$thissubpageid=$subrow[0];
		$thissubname=$subrow[1];
		echo "<option value=\"$thissubpageid\"> --- $thissubname</option>";
		}
	}
	echo "
	</select>
	</td>
	</tr>
	<tr>
	<td colspan=\"2\" align=\"center\">
	<input type=\"submit\" value=\"Edit the section's content\" />
	</td>";} else {echo"<td>There are no sections in the DB. Go to the Structure Management first.</td>";}

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
