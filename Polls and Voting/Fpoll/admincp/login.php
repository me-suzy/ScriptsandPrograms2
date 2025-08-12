<?php

// Author: PHPFront.com © 2005
// License: Free (GPL)
//
// Version: 1.1
//
// Created: 8.12.2005 
//
// More information and downloads 
// available at http://www.PHPFront.com
//
// #### login.php ####





include("config.php");

//logout procedure
if($_GET['do']=="kill"){
	
	setcookie ("user");	
	setcookie ("pass");

	header("Location: index.php");
	
}


//checking user details
$user = $_POST['user'];
$pass = md5($_POST['pass']);

$checkuser = mysql_query("SELECT * FROM fpoll_config WHERE user='$user' AND pass='$pass'");
$returned = mysql_num_rows($checkuser);

if($returned=="1"){
	
	$referer = $_POST['referer']; 

	setcookie ("user", $user, time()+3600); 
	setcookie ("pass", $pass, time()+3600);
	
	header("Location: index.php");
	
}else{
	
	header("Location: index.php");
	
}



?>