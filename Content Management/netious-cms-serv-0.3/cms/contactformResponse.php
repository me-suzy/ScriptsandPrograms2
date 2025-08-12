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




if ($mail_exists=="0") 
{mysql_query("ALTER TABLE `mycmsadmin` ADD `adminMail` VARCHAR( 255 ) NOT NULL");
}

mysql_query("UPDATE mycmsadmin SET adminMail='$adminMail' WHERE AdminId='1'");


if ($adminMail=="") $act="disactivated";
else $act="activated";



/* Create the page */

if (!isset($f)) $f="structure";
if (!isset($sf)) $sf="contact";


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
	<center><h2>Control panel - the Contact Form has been $act.</h2></center><br /><br />
	<b></b><br />
<br /><br />
<br />

	</td>
</tr>
</table>
 ";

bodyend();
commonfooter();



}
?>
