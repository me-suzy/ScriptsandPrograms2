<?php

include "s_classes.php";

$visuals = new visuals();
$visuals->showheader();

if(!isset($_POST['submit'])){

$visuals->registerForm();

}else{


	$e=0;
	
	if((!isset($_POST['coppa']) or trim($_POST['coppa'] == "1"))){
		$GLOBALS['error'][$e] = "\\nChildren under the age of 13 must have\\nparental consent to sign up\\nPlease email " . $GLOBALS['admin_email'] . " for instructions \\n";
		$e++;
		}
	
	if(!$GLOBALS['db']->checkTyped(trim($_POST['username']))){
		$GLOBALS['error'][$e] = "No username entered";
		$e++;
	}
	
	if(strlen($_POST['username']) > 15){
		$GLOBALS['error'][$e] = "Usernames must be less that 15 characters";
		$e++;
	}

	if(!$GLOBALS['db']->checkTyped(trim($_POST['first_name']))){
	$GLOBALS['error'][$e] = "You must enter a first name";
		$e++;
	}
	
	if(!$db->checkTyped(trim($_POST['last_name']))){
	$GLOBALS['error'][$e] = "You must enter a last name";
		$e++;
	}
	
	if(!$db->checkTyped(trim($_POST['email']))){
	$GLOBALS['error'][$e] = "No email address entered";
		$e++;
	}
	
	if(!$GLOBALS['db']->checkTyped(trim($_POST['email2']))){
	$GLOBALS['error'][$e] = "You must enter your email address twice";
		$e++;
	}
	
	if(!$GLOBALS['db']->checkTyped(trim($_POST['pword']))){
	$GLOBALS['error'][$e] = "No password entered";
		$e++;
	}

	if(!(trim($_POST['email']) == trim($_POST['email2']))){
	$GLOBALS['error'][$e] = "Your emails have to match exactly";
		$e++;
	}
	
	if(!(trim($_POST['pword2']) == trim($_POST['pword']))){
	$GLOBALS['error'][$e] = "Your passwords have to match exactly";
		$e++;
	}
	
	if(!$GLOBALS['db']->checkTyped(trim($_POST['pword2']))){
	$GLOBALS['error'][$e] = "You must enter your password twice";
		$e++;
	}
	
	if(!isset($_POST['agree'])){
	$GLOBALS['error'][$e] = "You must agree to the terms and conditions";
		$e++;
	}
	
		

	
		if(!$GLOBALS['db']->checkTyped(trim($_POST['answer']))){
	$GLOBALS['error'][$e] = "You must provide an answer to a security question";
		$e++;
	}
	
		if(count($GLOBALS['error'])>0)
		{
		$visuals->registerForm();
		}else{
		//check to see if the username exists...
				$result = $GLOBALS['db']->execQuery("select user_id from users where username = '" . trim($_POST['username']) . "'");
				if(mysql_num_rows($result) > 0){
				$GLOBALS['error'][0] = "That username is already in use\\nPlease choose another username";		
				$visuals->registerForm();
				}else{
				
				$security_code = md5(uniqid(rand(), true));
				$result = $GLOBALS['db']->execQuery("insert into users (username,first_name,last_name,pword,email,about_me,question,answer,security_code) values ('" . trim($_POST['username']) . "','" . trim($_POST['first_name']) . "','" . trim($_POST['last_name']) . "','" . strtolower(trim($_POST['pword'])) . "','" . strtolower(trim($_POST['email'])) . "','" . trim($_POST['about_me']) . "',". trim($_POST['question']) . ",'" . strtolower(trim($_POST['answer'])) . "','".$security_code."')" );

				//set the cookie
				
				$userdata['user_id'] = mysql_insert_id();
				$userdata['first_name'] = addslashes(trim($_POST['first_name']));
				$userdata['last_name'] = addslashes(trim($_POST['last_name']));
				$userdata['username'] = addslashes(trim($_POST['username']));
				$userdata['email'] = addslashes(trim($_POST['email']));
				$userdata['ul'] = 1;
				$userdata['security_code'] = addslashes($security_code);
	
				
				setcookie("loggedin", urldecode(serialize($userdata)));
				

				echo('<script language="JavaScript" type="text/javascript">
					window.setTimeout("window.location.href=\'index.php\'",3000);
					</script>
					
					<table   align="center" width="400">
					<tr><td align="center"><br>
					<br>
					<strong>Registration complete! Please wait while we log you in.</strong>
					<br>
					<br>
					
					<img src="icon_circle.gif" width="200" height="40" alt=""><br>
					<br>
					<br>
					<br></td></tr>
					</table><br>
					<br>
					
					');

				}
		}
	
}

$visuals->showfooter();

?>