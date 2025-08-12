<?php
include("./admin/config.php");
include("$include_path/common.php");

setcookie("keep_me_logged_in");
$sql = "
	delete from
		$tb_cookies
	where
		userid = '$_SESSION[userid]'
";
$query = mysql_query($sql) or die(mysql_error());

unset($_SESSION['userid']);
unset($_SESSION['username']);
unset($_SESSION['rc']);
unset($_SESSION['sl']);
$sid = session_id();

$sql = "
	delete from
		$tb_sessions
	where
		id = '$sid'
";
$query = mysql_query($sql) or die(mysql_error());

header("Location: $base_url/");
exit();
?>