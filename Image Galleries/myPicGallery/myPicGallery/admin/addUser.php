<?
session_start();
if($_GET['userID']){
	$_SESSION['userID'] = $_GET['userID'];
}
if(!session_is_registered($_SESSION['userID'])){	
	header("location: login.php");
}
?><LINK href="../includes/style.css" rel="stylesheet" type="text/css"><?
require("../includes/config.php");

$submit = $_POST['submit'];

if($submit){
	$fullName = $_POST['fullName'];
	$uName = $_POST['userName'];
	$password = md5($_POST['password']);
	$conPassword = md5($_POST['conPassword']);
	$uType = $_POST['uType'];
	$email = $_POST['email'];
	if($password != $conPassword){
		$message = "Your password was not confirmed. Try again...";
		$password = "";
		$submit = "";
	}
	else if(!$_POST['password']){
		$message = "Password field can not be empty";
		$password = "";
		$submit = "";
	}
	$ckUser = mysql_query("select * from album_users where userID = '$uName'")or die(mysql_error());
	if(!$uName){
		$message = "User Name field can not be empty.";
		$submit = "";
	}
	if(mysql_num_rows($ckUser) != 0){
		$message = "User name ".$uName." is already taken. Please choose another user name";
		$submit = "";
		$uName = "";
	}
	if($uName && $password){
		mysql_query("insert into album_users (userID, password, fullName, email, userType) values ('$uName', '$password', '$fullName', '$email', '$uType')")or die(mysql_error());
		//echo "<center>User ".$uName." has been successfully added...</center>";
		//echo "<center><a href=\"editUser.php?edit=true&user=".$uName."\">Edit user permission</a></center>";
		echo "<META HTTP-EQUIV=\"refresh\" content=\"0; URL=editUser.php?edit=true&user=".$uName."\">";
	}
}
if($submit == ""){
	?>
	<form action="addUser.php" method="post">
	<table align="center" width="700" class="tblBody">
	<tr><td colspan="4" class="tblTitle" align="center">Add User</td></tr>
	<tr><td colspan="4" align="center"><font color="#FF0000"><?=$message?></font></td></tr>
	<tr>
		<td align="right"><b>User Name: </b></td><td><input type="text" name="userName" size="15" maxlength="12" value="<?=$uName?>"></td>
		<td align="right"><b>Full Name: </b></td><td><input type="text" name="fullName" size="25" maxlength="50" value="<?=$fullName?>"></td>
	</tr>
	<tr>
		<td align="right"><b>Password: </b></td><td><input type="password" name="password" size="20" maxlength="20" value=""></td>
		<td align="right"><b>Email: </b></td><td><input type="text" name="email" size="20" maxlength="50" value="<?=$email?>"></td>
	</tr>
	<tr>
		<td align="right"><b>Retype Password: </b></td><td><input type="password" name="conPassword" size="20" maxlength="20" value=""></td>
		<td align="right"><b>User Type: </b></td><td><select name="uType">
													<option value="regular" selected>Regular</option>
													<option value="admin">Admin</option>
													</select></td>
	</tr>
	<tr><td colspan="4" align="center"><input type="submit" name="submit" value="Submit"></td></tr>
	</table>
	</form>
	<?
}
?>