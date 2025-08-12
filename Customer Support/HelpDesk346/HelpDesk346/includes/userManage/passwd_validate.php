<?php
	//validate old password with username
	if (empty($_POST['oldPass'])) {
		$page_error = 'Please Enter the Old Password';
		$error = true;	
	}
	
	//attempt a psuedo login to determine matchign password and username
	if (!isset($page_error)) {
		$q = "select id from " . DB_PREFIX . "accounts where (id = '" . mysql_real_escape_string($_POST['user']) . "' and pass = '" . md5($_POST['oldPass']) . "') LIMIT 1";
		$s = mysql_query($q) or die(mysql_error());
		if (!mysql_num_rows($s)) {
			$page_error = 'Username/Password Combination not Valid';
			$error = true;			
		}
	}
	
	//check new password for emptiness
	if (!isset($page_error)) {
		if (empty($_POST['newPass1'])) {
			$page_error = 'Please Enter a New Password';
			$error = true;	
		}	
	}
	
	//check length
	if (!isset($page_error)) {
		if (strlen($_POST['newPass1']) < 4) {
			$page_error = 'New Password is too Short - 4 Character Minimum';
			$error = true;	
		}
	}
	
	//character check
	if (!isset($page_error) && ereg("[[:punct:]]", $_POST['newPass1'])) {
		$page_error = "Password Contains Invalid Characters - No Punctuation";
		$error = true;	
	}
	
	//check for equality
	if (!isset($page_error)) {
		if (strcmp($_POST['newPass1'], $_POST['newPass2'])) {
			$page_error = 'New Passwords Do Not Match';
			$error = true;	
		}	
	}
	
	if (!$error) {
		$user = new User($_POST['user']);
		$user->passwd($_POST['newPass1']);
	}
?>