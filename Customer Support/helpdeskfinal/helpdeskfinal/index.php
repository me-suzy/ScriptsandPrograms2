<?php
require_once "includes/auth.php";
$index_page = "mytickets.php";
//
// Project: Help Desk support system
// Description: Main page
// Copyright 2005 http://simplehelpdesk.com  Do not resell or redistribute.. This is free copyrighted software.

// If config file doesn`t exist, redirect to installation
if(!is_file("includes/config.php"))
{
	header("Location: install/index.php");
}


// File to redirect to

if($hduser['logged_in'])
	header("Location: $index_page");
else
	header("Location: login.php");
?>
