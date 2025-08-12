<? include("../conf.inc.php");
include("../functions.inc.php");
admin_login();?>
<html><title>Set Admin Password</title><script>window.focus();</script><center><h2>Set Admin Password</h2>
<hr>
<?
if ($setpassword){
if ($setpassword!=$setconfirmpassword){
sendinfo();
echo "Error Passwords did not match";}
else { @mysql_query("replace into ".$mysql_prefix."system_values set name='admin password',value=password('$setpassword')"); echo "Admin password has been updated. DO NOT FORGET IT!"; exit;}
}
echo "<br><br><form method=post action=password.php>THE LONGER YOUR PASSWORD THE MORE SECURE YOUR SITE WILL BE. USE LETTERS AND NUMBERS INSTEAD OF COMMON PHRASES<br><br>Enter New Password: <input type=password name=setpassword><br>Confirm New Password: <input type=password name=setconfirmpassword><br><input type=submit></form></html>";

