<?php
if($name && $prename && $password && $email && $url)
{
$sid=mt_srand((double)microtime()*1000000);
$sid=md5(str_replace('.', '', getenv('REMOTE_ADDR') + mt_rand(100000, 999999)));
require('./prepend.inc.php');
if(account_add($name, $prename, $password, $email, $url, 2, $points_register, $sid, $referer))
{
mail($email, $email_welcome_title, $email_welcome, $email_header);
mail($email_notifynewmember, $email_notifynewmember_title, $email_notifynewmember_msg, $email_header);
header("Location: $url_register_succesfull");
exit;
}
}
?>
<?php
require('./prepend.inc.php');
?>

<?
include("./templates/main-header.txt");
?>


<br><font size="3"><?php
if($name && $prename && $email && $url && $password)
echo "Account with e-mail $email already exists!";?>
<form method="post" action="./register.php">
<?php if($referer){ ?>
<input type="hidden" name="referer" value="<?php echo $referer ?>">
<?php } ?>
<br><br>
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td><b>Name</b></td>
<td>
<input type="text" name="name" value="<?php echo stripslashes($name); ?>">
</td>
</tr>
<tr>
<td><b>First name</b></td>
<td>
<input type="text" name="prename" value="<?php echo stripslashes($prename); ?>">
</td>
</tr>
<tr>
<td height="30"><b>e-mail:</b></td>
<td height="30">
<input type="text" name="email" value="<?php echo stripslashes($email); ?>">
</td>
</tr>
<tr>
<td><b>Website-URL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
<td>
<input type="text" name="url" value="http://<?php echo stripslashes($url); ?>">
</td>
</tr>
<tr>
<td><b>Password</b></td>
<td>
<input type="password" name="password" value="<?php echo stripslashes($password); ?>">
</td>
</tr>
<tr>
<td colspan="2">
<br><br>
<input type="submit" value="Submit">
</td>
<b>
</tr>
</table>
</form>

<?
include("./templates/main-footer.txt");
?>