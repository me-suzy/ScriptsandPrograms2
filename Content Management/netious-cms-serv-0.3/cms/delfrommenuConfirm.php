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

if (!isset($f)) $f="structure";
if (!isset($sf)) $sf="del";

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
	<center><h2>Control panel - delete a section.</h2></center><br /><br />
	<b>Are you sure you want to remove completely the following section?</b><br />
<br /><br />
<br />
	<center>
	<div id=\"formular\">
	<form action='delfrommenuResponse.php' method='post'>
	<input type=\"hidden\" name=\"pageid\" value=\"$pageid\" />
	<table width=\"50%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>
	<td align=\"left\">
	Section to be removed:
	</td>
	</tr>
	<tr>
	<td align=\"left\">";
	$result=mysql_query("SELECT Name, RefId FROM pages WHERE PageId='$pageid'");
	$row=mysql_fetch_row($result);
	$name=$row[0];
	$refid=$row[1];

	echo "<b>$name</b><br />";

	if ($refid=="0")	
		{$result=mysql_query("SELECT Name FROM pages WHERE RefId='$pageid'");
		$num_rows=mysql_num_rows($result);
		if ($num_rows!=0)
		{
		while ($row=mysql_fetch_row($result))
			{$name=$row[0];
			echo "<b style=\"color:red\">-- $name</b><br />";
			}
		}
		}
	echo "
	</td>
	</tr>
	<tr>
	<td align=\"center\">
	<input type=\"submit\" value=\"Yes, I'm sure.\" />
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
