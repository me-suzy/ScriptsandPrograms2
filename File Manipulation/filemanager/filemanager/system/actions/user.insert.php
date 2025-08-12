<?php

if(isset($_POST['sent_data'])) {
	
	$user_role		= $_POST['user_role'];
	$user_username	= $_POST['user_username'];
	$user_password	= $_POST['user_password'];
	$user_email		= $_POST['user_email'];
	$user_form		= $_POST['user_form'];
	$user_firstname	= $_POST['user_firstname'];
	$user_lastname	= $_POST['user_lastname'];
	$user_company	= $_POST['user_company'];
	$user_status	= $_POST['user_status'];
	$user_send		= (isset($_POST['user_send']) && $_POST['user_send'] == "true") ? "true" : "false";
	$user_groups	= (isset($_POST['user_groups']) && is_array($_POST['user_groups'])) ? $_POST['user_groups'] : array();
	
	$user_password_md5 = md5($user_password);
	
	$sql = "INSERT `user_profile` ";
	$sql .= "(`user_role`,`user_username`,`user_password`,`user_email`,`user_form`,`user_firstname`,`user_lastname`,`user_company`,`user_status`,`user_registered`) VALUES ";
	$sql .= "('$user_role','$user_username','$user_password_md5','$user_email','$user_form','$user_firstname','$user_lastname','$user_company','$user_status',NOW())";
	$result = mysql_query($sql, Config::getDbLink());
	
	$user_id = mysql_insert_id();
	
	foreach($user_groups as $group_id) {
		
		$sql = "INSERT `relation_user2group` ";
		$sql .= "(`user_id`,`group_id`) VALUES ";
		$sql .= "('$user_id','$group_id')";
		$result = mysql_query($sql, Config::getDbLink());
	}
	
	if($user_send == "true") {
		
		$user_form = ($user_form == "mr") ? "Herr" : "Frau";
		
		$message = "Sehr geehrte(r) $user_form $user_firstname $user_lastname\n";
		$message .= "\n";
		$message .= "Ihnen wurde soeben ein Zugang zum ".Application::getWebsiteName()." erstellt.\n";
		$message .= "Ihre Logindaten lauten wie folgt:\n";
		$message .= "\n";
		$message .= "Username: $user_username\n";
		$message .= "Passwort: $user_password\n";
		$message .= "Website:  ".Application::getWebsiteUrl()."\n";
		$message .= "\n";
		$message .= "Bei Fragen wenden Sie sich bitte an ".Application::getWebsiteEmail().".\n";
		
		mail("$user_email", "".Application::getWebsiteName()." - Logindaten", $message,"From: ".Application::getWebsiteName()." <".Application::getWebsiteEmail().">\nReply-To: ".Application::getWebsiteName()." <".Application::getWebsiteEmail().">\nContent-Type: text/plain; charset=iso-8859-1\nContent-Transfer-Encoding: 7bit\nX-Mailer: PHP/" . phpversion());
	}
}

Utilities::redirect("admin.php?action=users.display");

?>