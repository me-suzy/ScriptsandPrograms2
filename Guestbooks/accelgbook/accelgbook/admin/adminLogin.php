<?php

if(file_exists("connect.php")) { 
	require_once("connect.php");
}


$loginAttempts = !isset($_POST['loginAttempts'])?1:$_POST['loginAttempts'];
$formuser = !isset($_POST['formuser'])?NULL:$_POST['formuser'];
$formpassword = !isset($_POST['formpassword'])?NULL:$_POST['formpassword'];
if(($formuser != ADMINUSER ) || ($formpassword != ADMINPASSWORD )) {
	if ($loginAttempts == 0) { 
		$_POST['loginAttempts'] = 1;
		include("adminLoginForm.php");
		exit;
	}else{
		if ( $loginAttempts >= 3 ) {
			echo "Login Failed";		
			exit;
		}else{
			include("adminLoginForm.php");
			exit;
		}
	}
}

if (($formuser == ADMINUSER ) && ($formpassword == ADMINPASSWORD )) {	
	session_start();
	$_SESSION['adminUser'] = ADMINUSER;
	$_SESSION['adminPassword'] = ADMINPASSWORD;
	$SID = session_id();
	$adminHome = ADMINHOME;
	include($adminHome);
}	
?>