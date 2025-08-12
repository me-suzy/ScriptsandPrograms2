<?php
session_start();
if($_GET['userID']){
	$_SESSION['userID'] = $_GET['userID'];
}
if(!session_is_registered($_SESSION['userID'])){	
	header("location: ../login.php");
}
?><LINK href="../includes/style.css" rel="stylesheet" type="text/css"><?
require("../includes/config.php");
$submit = $_POST['submit'];
$postUser = $_POST['user'];
$curPass = md5($_POST['curPass']);
$newPass = md5($_POST['newPass']);
$conPass = md5($_POST['conPass']);
if($submit == "Cancel"){
	echo "<META HTTP-EQUIV=\"refresh\" content=\"0; URL=../gallery.php\">";
}
if($newPass == $conPass){
	$confirmed = true;
}
else{
	$confirmed = false;
	$message = "Your new password was not confirmed. Try again...";
	$submit = "";
}
$chkPass = mysql_query("select * from album_users where (userID = '$postUser' and password = '$curPass')")or die(mysql_error());
if((mysql_num_rows($chkPass)==1) && ($confirmed)){
	//mysql_query("update album_users set password = '$newPass' where userID = '$postUser'")or die(mysql_error());
	echo "<center>Your password has been changed successfully...(nothing is changed in this demo)</center><br>";
	echo "<center><a href=\"../logout.php\">Click Here</a> to login using your new password</center>";
	echo "<META HTTP-EQUIV=\"refresh\" content=\"5; URL=../logout.php\">";
}
else if($submit == "Change Password"){
	$message = "Your current password was not right. Try again...";
	$submit = "";
}
if($submit == ""){
	?>
	<form action="changePassword.php" method="post">
	<table align="center" cellpadding="3" class="tblBody">
	<tr><td colspan="2" class="tblHead2">Changing Password for <?=$userName?></td></tr>
	<tr><td colspan="2"><font color="#FF0000" style="font-weight:bold "><?=$message?></font></td></tr>
	<tr><td align="right">Enter current password: </td><td><input type="password" name="curPass" size="20" value=""></td></tr>
	<tr><td align="right">Enter new password: </td><td><input type="password" name="newPass" size="20" value=""></td></tr>
	<tr><td align="right">Confirm new password: </td><td><input type="password" name="conPass" size="20" value=""></td></tr>
	<tr><td colspan="2" align="center"><input type="submit" name="submit" value="Change Password"> <input type="submit" name="submit" value="Cancel"></td></tr>
	<input type="hidden" name="user" value="<?=$userName?>">
	</table>
	</form>
	<?
}