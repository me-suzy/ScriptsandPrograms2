<?php
	//Revised July 21, 2005
	//Revised by Jason Farrell
	//Revision 2
	//Rev 2: Rewerite to setup inital settings
	
	//start by including the nessecary files
	include_once "../config.php";
	mysql_connect(DB_HOST, DB_UNAME, DB_PASS);
	mysql_select_db(DB_DBNAME);
	
	include_once "../classes/user.php";
	include_once "../classes/settings.php";
	include_once "../includes/functions.php";
	
	//start with the accounts - we will assume that if the username is blank, then no entry is being submitted
	//ALL THE DATA MUST BE VALID FOR THE INSERTION TO TAKE PLACE
	$error = $global_error = false;
	$arr = array();
	$accounts = 0;
	foreach ($_POST['accounts'] as $array)
	{
		if (strlen($array['uname'])) {		//consider the values
			$accounts++;
			
			if (!validateFirstName($array['fname'])) {
				$error = true;
				$array['error'] = 'Invalid First Name';
			}
			
			if (!$error && !validateLastName($array['lname'])) {
				$error = true;
				$array['error'] = 'Invalid Last Name';
			}
			
			if (!$error && !validatePassword($array['psswd'])) {
				$error = true;
				$array['error'] = 'Password is Invalid - Must be at least four characters';
			}
			
			if (!$error && !validateUsername($array['uname'])) {
				$error = true;
				$array['error'] = 'Invalid or Existing Username Choosen';
			}
			
			if (!$error && !validateEmail($array['email'])) {
				$error = true;
				$array['error'] = 'Invalid Email Address Provided';	
			}
			
			if (!$error && !validatePhoneNumber($array['phoneNum'])) {
				$error = true;
				$array['error'] = 'Invalid Phone Number Provided';
			}
			
			if (!$error && !validateUserType($array['userType'])) {
				$error = true;
				$array['error'] = 'Invalid User Type';	
			}
			
			if (!$error && strlen($array['phoneExt']) && !validatePhoneExt($array['phoneExt'])) {
				$error = true;
				$array['error'] = 'Invalid Phone Extensions Provided';	
			}
			
			if (!$error && !validateEmail($_POST['hd_from'])) {
				$setting_data_error = "Invalid Helpdesk From Email";
				$error = true;	
			}
			
			if ($error) $global_error = $error;
			$error = false;
		}
		
		$arr[] = $array;
	}
	$_POST['accounts'] = $arr;
	
	if (!$global_error && $accounts) {
		//perform the insertion by going through $_POST['accounts']
		/*
			Note:
			For the purpose of simplcity and clarity, we require a global installation as opposed to valid users being inserted. This
			also allows users to circumvent the user insertion limit
		*/
		
		$account_number = 0;
		foreach ($_POST['accounts'] as $account)
		{
			if (strlen($account['uname'])) {
				$user = new User();
				$user->set('email_addr', $account['email'], 'mysql_real_escape_string');
				$user->set('FirstName', $account['fname'], 'mysql_real_escape_string');
				$user->set('LastName', $account['lname'], 'mysql_real_escape_string');
				$user->set('pass', $account['psswd']);
				$user->set('phoneExt', $account['phoneExt']);
				$user->set('phoneNumber', $account['phoneNum']);
				$user->set('securityLevel', $account['userType'], 'intval');
				$user->set('user', $account['uname'], 'mysql_real_escape_string');
				$user->commit();
				$account_number++;
			}
		}
		
		//now take care of the settings
		$s = new Settings();
		if (!$s->get('results')) {
			$s->set('navigation', $_POST['navigation'], 'mysql_real_escape_string');
			$s->set('helpdesk', $_POST['helpdesk'], 'mysql_real_escape_string');
			$s->set('result_page', $_POST['result_page'], 'intval');
			$s->set('email_type', $_POST['email_type'], 'intval');
			$s->set('show_kb', $_POST['show_kb'], 'intval');
			$s->set('ticket_lookup', $_POST['ticketAccessModify'], 'intval');
			$s->set('user_defined_priorities', $_POST['user_defined_priorities'], 'intval');
			$s->set('hd_from', $_POST['hd_from'], 'mysql_real_escape_string');
			$s->insert();
		}
		
		header("Location: ../index.php");
	}
	elseif ($accounts == 0) {
		$error_msg = 'You Must Create at Least One User';	
	}
	
	mysql_close();
?>