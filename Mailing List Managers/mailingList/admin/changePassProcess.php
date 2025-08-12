<?php
if(@$_SESSION['admin'] != 1)
{
	header("location: login.php");
}
///change details
include '../inc/config.php';
include '../inc/conn.php';
$q = mysql_query("update admin set userName = '".$userName."', passWord = '".$passWord."', adminEmail = '".$email."' where userName = '".$_COOKIE['admin']."'");
if($q)
{
	mysql_close($conn);
	unset($_COOKIE['admin']);
	unset($_SESSION['admin']);
	header("location: login.php");
}
?>