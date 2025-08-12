<?php
include("./admin/config.php");
include("$include_path/common.php");

check_user_login();

if(isset($_POST['submit_new_message']) && 
	isset($_POST['new_message']) &&
	isset($_POST['new_subject']) &&
	isset($_POST['receiver_id'])){

	$message = addslashes($_POST['new_message']);
	$subject = addslashes($_POST['new_subject']);
			
	$sql = "
		insert into $tb_pms (
			id,
			user_id,
			subject,
			message,
			author_id,
			author_ip,
			pm_status
		) values (
			'',
			'$_POST[receiver_id]',
			'$subject',
			'$message',
			'$_SESSION[userid]',
			'$_SERVER[REMOTE_ADDR]',
			'inbox'
		)
	";
	$query = mysql_query($sql) or die(mysql_error());
}

header("Location: $base_url/messages.php?folder=inbox");
exit();
?>