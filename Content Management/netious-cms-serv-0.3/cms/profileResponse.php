<?
require("../db.php");
require("include.php");
DBinfo();

mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");



$SUID=f_ip2dec($REMOTE_ADDR);
if (!session_id($SUID))
session_start();

$uname=$_SESSION['uname'];
$pass=$_SESSION['pass'];

$result=mysql_query("SELECT AdminId FROM mycmsadmin WHERE username='$uname' and password='".sha1($pass)."'");
$row=mysql_fetch_row($result);
$num_rows = mysql_num_rows($result);
$id=$row[0];

if ($_SESSION['signed_in']!='indeed' || $num_rows!=1 || $id!=1){
Header( "Location: index.php?action=2");
}else{


if ($password!=$password1) {Header("Location:profile.php?action=1&username=$username");} else {

if ($mail_exists=="0") 
{mysql_query("ALTER TABLE `mycmsadmin` ADD `adminMail` VARCHAR( 255 ) NOT NULL");
}

mysql_query("UPDATE mycmsadmin SET username='$username', password='".sha1($password)."', adminMail='$adminMail' WHERE AdminId='1'");


$_SESSION['uname']=$username;
$_SESSION['pass']=$password;


/* Create the page */

if (!isset($f)) $f="profile";
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
	<center><h2>Control panel - the Admin Profile has been updated.</h2></center><br /><br />
	<b>Remember the new username and password!</b><br />
<br /><br />
<br />

	</td>
</tr>
</table>
 ";

bodyend();
commonfooter();


}
}
?>
