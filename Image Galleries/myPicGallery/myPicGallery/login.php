<?php

require("includes/config.php");

$submit = $_POST['submit'];

if($submit){
	
	$userID = $_POST['userID'];
	$password = md5($_POST['password']);

	$result = mysql_query("select * from album_users where (userID = '$userID' and 	password = '$password')")or die (mysql_error());

	if($row = mysql_fetch_array($result)){
		$id = $row['ID'];
		session_start();
		session_register($id);
		header("location: gallery.php?userID=".$id);
		exit();
	}
	else{
		$message = "Your login information was incorrect! Please try again...";
	}
}
if(!($submit) || ($message)){
	echo "For Admin: user = admin and password = admin<br>";
	echo "For non Admin: user = test and password = test<br>";
	?>
	<LINK href="includes/style.css" rel="stylesheet" type="text/css">
	<center><h3><?=$greetings?></h3></center>
	<form action="login.php" method="post">
	<table align="center" class="tblBody">
		<tr><td colspan="2" class="tblTitle">Please Login</td></tr>
		<tr><td align="right"><b>User ID: </b></td><td><input type="text" name="userID" size="20"></td></tr>
		<tr><td align="right"><b>Password: </b></td><td><input type="password" name="password" size="20"></td></tr>
		<tr><td></td><td><input type="submit" name="submit" value="Login"></td></tr>
		<tr><td colspan="2"><?=$message?></td></tr>
	</table>
	</form>
<?
}
?>