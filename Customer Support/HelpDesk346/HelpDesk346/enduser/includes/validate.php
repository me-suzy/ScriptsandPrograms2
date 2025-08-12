<?php
	/*
		Programmers Note:
		Through this portion of the script you will see numerous !isset checks and these are generally done at the start of the if
		conditional, there are reasons for this format based on efficiency. As we know, within the context of an and conditional the
		interperter will not evaluate the second part of the condition unless the first part is evaluated as true, this is the principle
		of truth, this there is no reason for evaluation to continue. For this reason we avoid checking data that we dont need to. The isset
		are in place to allow top level errors to remain and be displayed without being overridden by lower checks that would be redundant
		
		The basic idea here is, if an error occurs the related _error variable will be set and thus no more error checking need happen for
		that data, as what is the point of trying to validate errored data.
	*/
	
	define('REG_USER_SECURE_LEVEL', 0);
	
	include_once "../classes/user.php";

	//validate username
	/*if (empty($_POST['uname'])) {
		$uname_error = 'Please Enter a Username';
		$error = true;	
	}
	
	//duplicate check
	if (!isset($uname_error)) {	//only check if no error has been recorded
		$q = "select id from " . DB_PREFIX . "accounts where user = '" . mysql_real_escape_string($_POST['uname']) . "' LIMIT 1";
		$s = mysql_query($q) or die(mysql_error());
		if (mysql_num_rows($s)) {
			$uname_error = "Username Already Exists - Please Select Another";
			$error = true;	
		}
	}
	
	///////////////////////////////////////////////////////////////////////////////////////////
	//validate password
	if (empty($_POST['pass1'])) {
		$pass_error = "Pleaes Enter a Password";
		$error = true;
	}
	
	//check length
	if (strlen($_POST['pass1']) < 4 && !isset($pass_error)) {
		$pass_error = "Invalid Length Password";
		$error = true;
	}
	
	//character check
	if (!isset($pass_error) && ereg("[[:punct:]]", $_POST['pass1'])) {
		$pass_error = "Password Contains Invalid Characters - No Punctuation";
		$error = true;	
	}
	
	//check confirm pass
	if (strcmp($_POST['pass1'], $_POST['pass2']) && !isset($pass_error)) {	//zero would evaluate to false, 
		$pass_error = "Passwords Do Not Match";								//meaning they are the same, otherwise enter the block
		$error = true;
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////
	//validate firstname
	if (empty($_POST['fname'])) {
		$fname_error = "Please Enter a First Name";
		$error = true;	
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////
	//validate lastname
	if (empty($_POST['lname'])) {
		$lname_error = "Please Enter a Last Name";
		$error = true;
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////
	//validate email adress
	if (empty($_POST['email_addr'])) {
		$email_error = "Please Enter an Email Address";
		$error = true;	
	}
	
	//length check
	if (!isset($email_error) && strlen($_POST['email_addr']) < 7) {
		$email_error = "Incorrect Length for Email Address";
		$error = true;
	}
	
	//@ symbol check
	if (!isset($email_error) && substr_count($_POST['email_addr'], '@') != 1) {
		$email_error = "Incorrect Format for Email Address";
		$error = true;	
	}
	
	//@ character check
	if (!isset($email_error) && ( preg_match('/\.@/', $_POST['email_addr']) || preg_match('/@\./', $_POST['email_addr']) ) ) {
		$email_error = "Incorrect Format for Email Address";
		$error = true;
	}
	
	//character check 2
	if (!isset($email_error) && ( substr_count($_POST['email_addr'], "'") || substr_count($_POST['email_addr'], '"') ) ) {
		$email_error = "Invalid Characters in Email Address";
		$error = true;
	}
	
	//check email duplication
	#if (!isset($email_error)) {
	#	$q = "select id from " . DB_PREFIX . "accounts where email_addr = '" . mysql_real_escape_string($_POST['email_addr']) . "'";
	#	$s = mysql_query($q) or die(mysql_error());
	#	if (mysql_num_rows($s)) {
	#		$email_error = "Email Address Already Exists in Database";
	#		$error = true;	
	#	}
	#}
	*/
	
	//new validation - using the common validation schema
	include_once "../includes/functions.php";
	
	$error = false;
	if (!validateUsername($_POST['uname'])) {
		$error = true;
		$uname_error = "Invalid Username Selected - May Exist or contain spaces";	
	}
	
	if (!$error && !validatePassword($_POST['pass1'], $_POST['pass2'])) {
		$error = true;
		$pass_error = "Invalid or Unmatching Password Keyed - Please Reenter";
	}
	
	if (!$error && !validateEmail($_POST['email_addr'])) {
		$error = true;
		$email_error = "Invalid Email Address - Please Check the Format and reenter";	
	}
	
	if (!$error && !validateLastName($_POST['fname'])) {
		$error = true;
		$lname_error = "Invalid First Name - Please Verify";	
	}
	
	if (!$error && !validateLastName($_POST['lname'])) {
		$error = true;
		$lname_error = "Invalid Last Name - Please Verify";	
	}
	
	if (!$error && !validatePhoneNumber($_POST['phoneNum'])) {
		$error = true;
		$phoneNum_error = "Invalid Phone Number - Please Ensure their are 10 digits being used";
	}
	else {
		$_POST['phoneNum'] = preg_replace('/[^\d]/', '', $_POST['phoneNum']);
	}
	
	if (strlen($_POST['phoneExt'])) {
		if (!$error && !validatePhoneExt($_POST['phoneExt'])) {
			$error = true;
			$phoneExt_error = "Invalid Phone Extension - Must be all numeric";	
		}
		else
			$_POST['phoneExt'] = preg_replace('/[^\d]/', '', $_POST['phoneExt']);
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////
	//commit the data
	if (!$error) {
		$user = new User();
		$user->set('email_addr', $_POST['email_addr'], 'mysql_real_escape_string');
		$user->set('FirstName', $_POST['fname'], 'mysql_real_escape_string');
		$user->set('LastName', $_POST['lname'], 'mysql_real_escape_string');
		$user->set('pass', $_POST['pass1'], 'mysql_real_escape_string');
		$user->set('phoneNum', $_POST['phoneNum']);
		$user->set('phoneExt', $_POST['phoneExt']);
		$user->set('securityLevel', REG_USER_SECURE_LEVEL);
		$user->set('user', $_POST['uname'], 'mysql_real_escape_string');
		$user->commit();
	}
?>