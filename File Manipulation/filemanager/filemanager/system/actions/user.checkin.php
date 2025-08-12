<?php

$sql = "SELECT * FROM `user_profile` WHERE `user_username` = '".$_POST['username']."' AND `user_password` = '".md5($_POST["password"])."' AND `user_status` = 'active'";
$result = mysql_query($sql, Config::getDbLink());
if($data = mysql_fetch_array($result)) {
	
	$_SESSION['s_userid']	= $data['user_id'];
	$_SESSION['s_role']		= $data['user_role'];
	
	if($data['user_role'] == "user") {
		
		Utilities::redirect("index.php?action=main.display");
	}
	else {
		
		Utilities::redirect("admin.php?action=main.display");
	}
}
else {
	Utilities::redirect("index.php?action=main.login");
}

?>