<?php
	if ($_POST['command'] == "Login") {
		$q = "select id from " . DB_PREFIX . "accounts where user = '" . mysql_real_escape_string($_POST['uname']) . "' and pass = '" . md5($_POST['upass']) . "' LIMIT 1";
		$s = mysql_query($q) or die(mysql_error());
		if (mysql_num_rows($s)) {
			$r = mysql_fetch_assoc($s);
			$_SESSION['loggedIn'] = true;
			$_SESSION['enduser'] = serialize(new User($r['id']));
			$t->set('regUser', $r['id']);
		}
		else {
			$error_msg = "Login Invalid";
		}	
	}
	else {
		if (empty($_POST['FirstName']) || empty($_POST['LastName']) || empty($_POST['eMail']) || empty($_POST['PCatagory']) || empty($_POST['describe']))
			$_error_msg = "All Required Fields Must be Entered or Selected";
			
		//Email Format Check - Regex courtesy of regexlib.com : Myle Ott
		if (!isset($_error_msg) && !preg_match('/^([a-zA-Z0-9_\-])+(\.([a-zA-Z0-9_\-])+)*@((\[(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5])))\.(((([0-1])?([0-9])?[0-9])|(2[0-4][0-9])|(2[0-5][0-5]))\]))|((([a-zA-Z0-9])+(([\-])+([a-zA-Z0-9])+)*\.)+([a-zA-Z])+(([\-])+([a-zA-Z0-9])+)*))$/', $_POST['eMail']))
			$_error_msg = "Email Address Not Properly formmatted";
		
		if (!isset($_error_msg)) {
			//Gather UA Variables - For SNiff
			$security1 = $OBJ->get('helpdesk');
			$contents  = $OBJ->get('navigation');
			$result_page = $OBJ->get('result_page');
			$hdticket  = $OBJ->get('hdticket');
			
			// initialize some vars
			$GET_VARS = isset($_GET) ? $_GET : $HTTP_GET_VARS;
			$POST_VARS = isset($_POST) ? $_GET : $HTTP_POST_VARS;
			if(!isset($GET_VARS['UA'])) $GET_VARS['UA'] = '';
			if(!isset($GET_VARS['cc'])) $GET_VARS['cc'] = '';
			if(!isset($GET_VARS['dl'])) $GET_VARS['dl'] = '';
			if(!isset($GET_VARS['am'])) $GET_VARS['am'] = '';	
			
			$sniffer_settings = array('check_cookies'=>$GET_VARS['cc'],
			                          'default_language'=>$GET_VARS['dl'],
			                          'allow_masquerading'=>$GET_VARS['am']);
			$client =& new phpSniff($GET_VARS['UA'],$sniffer_settings);
			
			$t->set('FirstName', $_POST['FirstName'], 'mysql_real_escape_string');
			$t->set('LastName', $_POST['LastName'], 'mysql_real_escape_string');
			$t->set('EMail', $_POST['eMail'], 'mysql_real_escape_string');
			
			$t->set('PCatagory', new Category($_POST['PCatagory']));
			$t->set('descrip', $_POST['describe'], 'mysql_real_escape_string');
			$t->set('staff', 0);
			$t->set('status', new Status(
									mysql_result(
										mysql_query("select id from " . DB_PREFIX . "status order by position LIMIT 1"),
										0)));	
			$t->set('mainDate', date("H:i M d Y"));
			$t->set('uastring', $client->get_property('ua'));
			$t->set('browser', $client->get_property('browser'));
			$t->set('bversion', $client->get_property('version'));
			$t->set('platform', $client->get_property('platform'));
			$t->set('os', $client->get_property('os'));
			$t->set('ip', $client->get_property('ip'));
			
			if (isset($_POST['priority'])) $t->set('priority', new Priority($_POST['priority']));
			else {
				$c = new Category($_POST['PCatagory']);
				$t->set('priority', new Priority($c->get('priority')));	
			}
			
			if (isset($_SESSION['enduser'])) {
				$u = unserialize($_SESSION['enduser']);
				$t->set('regUser', $u->get('id'));	
			}
		
			if (isset($_FILES['file']) && strlen($_FILES['file']['name']) && !isset($_error_msg)) {
				//process the file being uploaded
				if (!file_exists("./uploaded_files/")) mkdir("uploaded_files");
				if ($OBJ->CheckFile($_FILES['file']['name']) && $OBJ->checkSize($_FILES['file']['size'])) {
					$t->commit();
					$tid = $t->get('id');
					if (!move_uploaded_file($_FILES['file']['tmp_name'], "./uploaded_files/" . $tid . "_" . $_FILES['file']['name']))
						die("Upload Failed");
					else {
						$cmd = "insert into " . DB_PREFIX . "files(id, name) values(" . intval($tid) . ", '" . $_FILES['file']['name'] . "')";
						mysql_query($cmd) or die(mysql_error());
					}
				}
				else {
					$_error_msg = "File is Not Valid for Upload - Blocking may be enabled or Max Size Exceeded";
				}
			}
			else {
				$t->commit();	
			}
			
			if (!isset($_error_msg)) {
				if ($OBJ->get('hdemail_create', 'intval')) {
					PerformCreateAction($OBJ->get('hdemail'), $t->get('FirstName', 'mysql_real_escape_string') . " " . $t->get('LastName', 'mysql_real_escape_string'),
										$t->get('EMail'), date("h:i  M d Y"), $t->get('descrip', 'mysql_real_escape_string'), $OBJ->get('email_type'));
				}
						
				//create the variables to be displayed for the end user
				$ticketno = $t->get('id');
				$q = "select id from " . DB_PREFIX . "status order by position limit 1";
				$set = mysql_query($q) or die(mysql_error());
				$q = "select id from " . DB_PREFIX . "data where id <> " . $t->get('id') . " and status = " . mysql_result($set, 0) . "";
				$s = mysql_query($q) or die(mysql_error());
				$num_calls = mysql_num_rows($s);
							
				include_once "./includes/report_end.php";
				exit;	
			}
		}
	}
	
	//create action function definition
	/*
		@return		void
		@param		intval		Integer Representing Rule
		@param		name		Name of Submitter
		@param		email		Email Address of Submitter
		@param		datetime	Date of Submission
		@param		desc		Descritpion of Submitted Problem
		@param		type		Type of Email Submission
	*/
	function PerformCreateAction($intval, $name, $email, $datetime, $desc, $type)
	{
		//define headers
		global $OBJ;
		$headers = "From: " . $OBJ->get('hd_from', 'stripslashes') . "\r\n";
		
		//define message
		switch ($type)
		{
			case 0:
				$message= "<table width=100%><tr><td align=left><strong>Solution :</strong></td></tr>";
				$message.= "<tr><td align=left></td></tr></table>";
				$message ="<font face='Arial, Helvetica, sans-serif' size='2'><strong>Attention: $name,</strong><br><br>";
				$message.="Your Ticket has been added to the Helpdesk Ticket List<br><br></font>";
				$message.="<tr><td><table width=100%>";
				$message.="<tr><td width=50%><font face='Arial, Helvetica, sans-serif' size='2'>Email Address of Submitter:&nbsp;&nbsp;</font></td><td><font face='Arial, Helvetica, sans-serif' size='2'>	<strong>$email</strong></font></td></tr>";   
				$message.="<tr><td width=50%><font face='Arial, Helvetica, sans-serif' size='2'>Description of Call&nbsp;&nbsp;</font></td><td><font face='Arial, Helvetica, sans-serif' size='2'>	<strong>" . nl2br($desc) . "</strong></font></td></tr>";   
				$message.="<tr><td width=50%><font face='Arial, Helvetica, sans-serif' size='2'>Date&nbsp;&nbsp;</font></td><td><font face='Arial, Helvetica, sans-serif' size='2'>	<strong>$datetime</strong></font></td></tr>";
				$message.="</table></td></tr></font></table>";
				$message.="<br><br><font face='Arial, Helvetica, sans-serif' size='2'>Thanks<br><strong>Technical Team</strong><br>";
				
				$headers .= 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				break;
			case 1:
				$message  = "Attention: " . $name . " has submitted a new problem on " . $datetime . chr(10) . chr(10);
				$message .= "The Problem is Described Below:" . chr(10);
				$message .= $desc . chr(10) . chr(10);
				break;
		}
		
		switch ($intval)
		{
			case 1:
				//email all technicians
				$q = "select email_addr from " . DB_PREFIX . "accounts where securityLevel = 2";
				$s = mysql_query($q) or die("Error Selected Technician Email Addresses in Rule Determination (Create)");
				while ($r = mysql_fetch_assoc($s))
					mail($r['email_addr'], "A New Ticket Has Been Submitted", $message, $headers);
				break;
			case 2:
				//email to everyone
				$q = "select email_addr from " . DB_PREFIX . "accounts";
				$s = mysql_query($q) or die("Error Selected Technician Email Addresses in Rule Determination (Create)");
				while ($r = mysql_fetch_assoc($s))
					mail($r['email_addr'], "A New Ticket Has Been Submitted", $message, $headers);	
				break;
			case 3:	
				//email admins only
				$q = "select email_addr from " . DB_PREFIX . "accounts where securityLevel = 1";
				$s = mysql_query($q) or die("Error Selected Technician Email Addresses in Rule Determination (Create)");
				while ($r = mysql_fetch_assoc($s))
					mail($r['email_addr'], "A New Ticket Has Been Submitted", $message, $headers);	
				break;
		}
	}
?>