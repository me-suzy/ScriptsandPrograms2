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


/* checks whether the e-mail field exists */

if ($result=mysql_query("SELECT adminMail FROM mycmsadmin WHERE AdminId='1'")) {$mail_exists="1";
$row=mysql_fetch_row($result);
$adminMail=$row[0];

} else {$mail_exists="0"; $adminMail="";}




if (!isset($username))
{
$result=mysql_query("SELECT username FROM mycmsadmin WHERE AdminId='1'");
$row=mysql_fetch_row($result);
$username=$row[0];
}

if (!isset($action)) $action="0";
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
	<center><h2>Control panel - the Admin Profile</h2></center><br /><br />
	<b>Here you can modify the username and password to the Control Panel. Make sure you remember it!</b><br />
<br /><br />
<br />
	<center>";
if ($action=="1") echo "<b style=\"color:red\">The passwords do not match</b>";

	echo "
	<div id=\"formular\">
	<form action='profileResponse.php' method='post'>
	<input type=\"hidden\" name=\"mail_exists\" value=\"$mail_exists\" />
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>
	<td align=\"left\">
	Username:
	</td>
	<td align=\"left\">
	<input type=\"text\" size=\"30\" name=\"username\" value=\"$username\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Password:
	</td>
	<td align=\"left\">
	<input type=\"password\" size=\"30\" name=\"password\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Repeat Password:
	</td>
	<td align=\"left\">
	<input type=\"password\" size=\"30\" name=\"password1\" />
	</td>
	</tr>
	<tr>
	<td align=\"left\">
	Admin (contact) e-mail (write only if you want to include a contact formular in your service!):
	</td>
	<td align=\"left\">
	<input type=\"text\" size=\"30\" name=\"adminMail\" value=\"$adminMail\" />
	</td>
	</tr>
	<tr>
	<td colspan=\"2\" align=\"center\">
	<input type=\"submit\" value=\"Update the profile\" />
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
