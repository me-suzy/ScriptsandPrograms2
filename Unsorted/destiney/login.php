<?php
include("./admin/config.php");
include("$include_path/common.php");

if(isset($_POST['login'])){
	$sql = "
		select
			username,
			id
		from
			$tb_users
		where
			username = '$_POST[UN]'
		and
			password = password('$_POST[PW]')
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($query)){
		$_SESSION['username'] = mysql_result($query, 0, "username");
		$_SESSION['userid'] = (int) mysql_result($query, 0, "id");
	}
	$_SESSION['sl'] = true;
}

if(check_approved_image($_SESSION['userid'])){
	header("Location: $base_url/");
	exit();
} else {
	header("Location: $base_url/upload.php");
	exit();
}

?>