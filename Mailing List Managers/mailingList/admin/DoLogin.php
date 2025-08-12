<?php
session_start();
/// check image
$number = $_POST['verifyImage'];
if(@md5($number) != $_SESSION['image_random_value'])
	{
		header("location: login.php");
		exit();
	}
///check for usename and password
include '../inc/config.php';
include '../inc/conn.php';
$q = mysql_query("select * from admin");
while($result = mysql_fetch_array($q))
{
	$UN = $result['userName'];
	$PW = $result['passWord'];
}
if($userName == $UN && $passWord == $PW)
{
	///set cookies
	$_SESSION['admin'] = setcookie('admin', $UN, time() + 3600);
	///sent to index
	mysql_free_result($q);
	mysql_close($conn);
	header("location: index.php");
}
else
{
	mysql_free_result($q);
	mysql_close($conn);
	header("location: login.php");
}
?>