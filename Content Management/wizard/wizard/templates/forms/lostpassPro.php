<?php

/*  
   Lost Password Processor Script
   (c) 2004-2005 Philip Shaddock All rights reserved.
       pshaddock@wizardinteractive.com 
*/ 	
include_once("../../inc/config_cms/configuration.php");	
include_once("../../inc/db/db.php");	
include_once '../../inc/languages/' . $language . '.public.php';
include_once("user.php");

if (user_isloggedin()) {
        user_logout();
        $username='';
}

    $username = $_POST['username'];
	$email = $_POST['email'];
	
	user_lost_password($email,$username);

	

?>