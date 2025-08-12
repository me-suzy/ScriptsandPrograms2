<?php

/*  
   Change Email Processor Script
   (c) 2006 Philip Shaddock All rights reserved.
       www.ragepictures.com 
*/ 	
include_once("../../inc/config_cms/configuration.php");	
include_once("../../inc/db/db.php");	
include_once '../../inc/languages/' . $language . '.public.php';
include_once("../../inc/functions/user.php");

if (user_isloggedin()) {
        user_logout();
        $username='';
}

    $change_user_name = $_POST['change_user_name'];
	$new_email = $_POST['new_email'];
	$password1 = $_POST['password1'];
	
	user_change_email ($password1,$new_email,$change_user_name);

	

?>