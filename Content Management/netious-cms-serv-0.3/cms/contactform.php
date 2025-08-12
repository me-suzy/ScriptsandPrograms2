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
	<center><h2>Control panel - activate/disactivate the contact form.</h2></center><br /><br />
	<b>If you want the contact form to appear in your service write here the contact e-mail (it won't be visible in the site). Otherwise leave blank. To disactivate the form erase the e-mail (if exists).</b><br />
<br /><br />
<br />";

if ($result=mysql_query("SELECT adminMail FROM mycmsadmin WHERE AdminId='1'")) {$mail_exists="1";
$row=mysql_fetch_row($result);
$adminMail=$row[0];

} else {$mail_exists="0"; $adminMail="";}


echo "
	<center>
	<div id=\"formular\">
	<form action='contactformResponse.php' method='post'>
	<input type=\"hidden\" name=\"mail_exists\" value=\"$mail_exists\" />
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"5\" border=\"1\">
	<tr>
	<td align=\"left\">
	The contact e-mail:
	</td>
	<td align=\"left\">
	<input type=\"text\" size=\"30\" name=\"adminMail\" value=\"$adminMail\" />
	</td>
	</tr>
	<tr>
	<td colspan=\"2\" align=\"center\">
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
