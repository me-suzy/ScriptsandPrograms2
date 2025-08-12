<?php

/*  
   Login Processor Script
   (c) 2006 Philip Shaddock All rights reserved.
       pshaddock@ragepictures.com 
*/ 	

include_once("../../inc/config_cms/configuration.php");	
include_once("../../inc/db/db.php");	
include_once("../../inc/languages/". $language .".public.php");
include_once("../../inc/functions/user.php");

if (user_isloggedin()) {
        user_logout();
        $username='';
}

    $username = $_POST['username'];
	$password = $_POST['password'];
	$pageid = $_POST['pageid'];
	
	user_login($username,$password,$pageid);

	
?>