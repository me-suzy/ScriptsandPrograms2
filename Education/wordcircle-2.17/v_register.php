<?php

if(isset($_POST['submit'])){
	
		
		
		if(!isset($_POST['agree'])){
			$GLOBALS['error'][0] = "You must agree to the terms and conditions";
		}
		
		
		$result9 = $GLOBALS['db']->execQuery("select user_id from users where email = '".trim($_POST['email'])."'");
		$num_rows9 = mysql_num_rows($result9);
		if($num_rows9 > 0){$GLOBALS['error'][0] = "That email address is already in use";}
		
		if($_POST['email'] == $GLOBALS['admin_email']){
		$GLOBALS['error'][0] = "That email address is reserved";
		}
		
		$GLOBALS['db']->checkTyped($_POST['first_name'],"You must enter a first name");
		$GLOBALS['db']->checkNames($_POST['first_name'],"Names must not have special characters");
		$GLOBALS['db']->checkLen($_POST['first_name'],30,"First name must be less than 30 characters");
		$GLOBALS['db']->checkTyped($_POST['last_name'],"You must enter a last name");
		$GLOBALS['db']->checkNames($_POST['first_name'],"Names must not have special characters");
		$GLOBALS['db']->checkLen($_POST['last_name'],30,"Last name must be less than 30 characters");
		$GLOBALS['db']->checkTyped($_POST['email'],"You must enter an email address");
		$GLOBALS['db']->checkLen($_POST['email'],50,"email address must be less than 50 characters");
		$GLOBALS['db']->checkEmail($_POST['email'],"email address contains invalid characters");
		$GLOBALS['db']->checkTyped($_POST['email2'],"You must re-enter an email address");
		$GLOBALS['db']->checkTyped($_POST['pword'],"You must enter a password");
		$GLOBALS['db']->checkLen($_POST['pword'],15,"Passwords must be less than 16 characters");
		$GLOBALS['db']->checkTyped($_POST['pword2'],"You must re-enter a password");
		$GLOBALS['db']->compareTwo($_POST['pword2'],$_POST['pword'],"The passwords do not match");
		$GLOBALS['db']->compareTwo($_POST['email'],$_POST['email2'],"The emails do not match");
		
		
		if((count($GLOBALS['error'])==0)){
				
				$security_code = md5(uniqid(rand(), true));
				$result = $GLOBALS['db']->execQuery("insert into users (first_name,last_name,pword,email,security_code) values ('" . trim($_POST['first_name']) . "','" . trim($_POST['last_name']) . "','" . strtolower(trim($_POST['pword'])) . "','" . strtolower(trim($_POST['email'])) . "','".$security_code."')" );

				//set the cookie
				
				$userdata['user_id'] = mysql_insert_id();
				$userdata['first_name'] = addslashes(trim($_POST['first_name']));
				$userdata['last_name'] = addslashes(trim($_POST['last_name']));
				$userdata['email'] = addslashes(trim($_POST['email']));
				$userdata['ul'] = 1;
				$userdata['security_code'] = addslashes($security_code);
				setcookie("loggedin", urldecode(serialize($userdata)));
				$GLOBALS['page']->head("wordcircle","","Remember you password!",0);
				$GLOBALS['page']->pleaseWait("Registration successful - logging you in","index.php");
				include("v_footer.php");
				exit;
			
			
		}		
	
	}

$GLOBALS['page']->head("wordcircle","","You must register in order to use wordcircle",0);
	
	//function tableStart($class,$width,$type,$image="",$linkArray=array())
	
	$arr = array();
	$arr[0]['I agree to the terms and conditions']='1';
	$GLOBALS['page']->tableStart("","100%","TAB","Register");
	echo("<br><div align='center'>Register by completing the form below</div>");
	$GLOBALS['page']->tableStart("","100%","FORM");
	//function text($value,$name,$class,$desc,$size,$chngeOnPost=0
	//function checkbox($checkSuperArray,$name,$desc,$class,$chngeOnPost){
	$GLOBALS['page']->checkbox($arr,"agree","Read the <a href='index.php?a=terms'>terms and conditions</a>","inputs",0);
	$GLOBALS['page']->text("","first_name","inputs","First Name:",30,1);
	$GLOBALS['page']->text("","last_name","inputs","Last Name:",30,1);
	$GLOBALS['page']->text("","email","inputs","Email:",30,1);
	$GLOBALS['page']->text("","email2","inputs","Type Your Email again:",30,1);
	$GLOBALS['page']->password("","pword","inputs","Choose a Password:",30,1);
	$GLOBALS['page']->password("","pword2","inputs","Type Password again:",30,1);
	
	$GLOBALS['page']->submit("Submit Registration","inputs");
	$GLOBALS['page']->tableEnd("FORM");
	$GLOBALS['page']->tableEnd("TAB");
	
?>