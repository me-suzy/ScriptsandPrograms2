<?php
session_start();
if(   (!isset($_SESSION['adminUser'])) || (!isset($_SESSION['adminPassword'])) ) {
	include_once("adminLogin.php");
	
	exit;
}

if(file_exists("connect.php")) { 
	require_once("connect.php");
}


if( ($_SESSION['adminUser'] != ADMINUSER) || ($_SESSION['adminPassword'] != ADMINPASSWORD) ) {
	
	include_once("adminLogin.php");
	exit;
}?>