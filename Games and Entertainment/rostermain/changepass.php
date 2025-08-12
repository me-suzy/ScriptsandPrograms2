<div class="log">
<form method=post action="index.php?log=1">
Old password:<br />
<input type=text name=old_passwd style="font-size:10px;border:solid 1px;"><br />
New password:<br />
<input type=text name=new_passwd style="font-size:10px;border:solid 1px;"><br />
Repeat new password:<br />
<input type=text name=new_passwd2 style="font-size:10px;border:solid 1px;"><br />
<input type=image src="buttons\cp.gif" name=change value="Change password" style="font-size:10px;">
</form>
</div>
<?
//script written by ryan marshall of irealms.co.uk
if (strlen($new_passwd)>16 || strlen($new_passwd)<4) 
   echo "<div class=\"log\">Passwords must be between<br />4 and 16 chars."; 
elseif ($_SESSION['pass'] != $old_passwd) 
   echo "<div class=\"log\">Your old password is incorrect<br />please try again.</div>"; 
elseif ($new_passwd!=$new_passwd2) 
   echo "<div class=\"log\">The passwords do not match.<br />passwords not changed.</div>"; 
else
{
$db_conn = mysql_connect("localhost", "cadmin", "cpass");
  mysql_select_db("crimson", $db_conn);
$query = "update users
set passwd = '$new_passwd'
where username = '$valid_user'";
  $result = mysql_query($query, $db_conn);
echo "<div class=\"log\">password changed</div>";
$_SESSION['pass'] = '$new_passwd';
}
?>
