<?php
include("./admin/config.php");
include("$include_path/common.php");

check_user_login();

if(isset($_GET['msg_id']) && isset($_GET['folder'])){
	
	move_message($_GET['msg_id'], "trash");
	
	$sql = "
		select
			id
		from
			$tb_pms
		where
			user_id = '$_SESSION[userid]'
		and
			pm_status = '$_GET[folder]'
		and
			id > '$_GET[msg_id]'
		order by
			id
		limit
			1
	";
	$query = mysql_query($sql) or die(mysql_error());

	if(mysql_num_rows($query)){
		$msg_id = mysql_result($query, 0, "id");
		header("Location: $base_url/view_msg.php?msg_id=$msg_id&folder=$_GET[folder]");
		exit();
	}

	$sql = "
		select
			id
		from
			$tb_pms
		where
			user_id = '$_SESSION[userid]'
		and
			pm_status = '$_GET[folder]'
		and
			id < '$_GET[msg_id]'
		order by
			id
		limit
			1
	";
	$query = mysql_query($sql) or die(mysql_error());

	if(mysql_num_rows($query)){
		$msg_id = mysql_result($query, 0, "id");
		header("Location: $base_url/view_msg.php?msg_id=$msg_id&folder=$_GET[folder]");
		exit();
	}

}

header("Location: $base_url/messages.php?folder=$_GET[folder]");
exit();
?>