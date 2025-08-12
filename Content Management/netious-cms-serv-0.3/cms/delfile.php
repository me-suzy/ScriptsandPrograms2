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

if (!isset($f)) $f="file";
if (!isset($sf)) $sf="";

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
	<center><h2>Control panel - delete the file</h2><br /><br />
	<b>Are you sure you want to remove the file $thefile ?</b>
	<br />
<br />
	<form action='delfileResponse.php' action='POST'>
	<input type=\"hidden\" name=\"pageid\" value=\"$pageid\" />
	<input type=\"hidden\" name=\"thefile\" value=\"$thefile\" />
	<input type=\"submit\" value=\"Remove\" />
	</form>
	<form action='manageFile.php' action='POST'>
	<input type=\"hidden\" name=\"pageid\" value=\"$pageid\" />
	<input type=\"submit\" value=\"Back\" />
	</form>

<br />

	</center>
	</td>
</tr>
</table>


";

bodyend();
commonfooter();


}
?>
