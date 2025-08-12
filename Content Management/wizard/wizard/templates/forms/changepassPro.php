<?php

/*  
   Change Password Form
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

    $username = $_POST['username'];
	$new_password1 = $_POST['new_password1'];
	$new_password2 = $_POST['new_password2'];
	$old_password = $_POST['old_password'];
	
	user_change_password ($new_password1,$new_password2,$username,$old_password);

	

?>