<?php
/*
Copyright 2005 VUBB
*/

// Get the functions
include('includes/functions.php');

// Post parsing
include('includes/post_parser.php');

// Get the config
include('./config.php');

// Have we been asked to logout?
if (isset($_GET['logout']) && $_GET['logout'] == 'yes')
{
	logout();
	header("Location: index.php");
}

// cookies
cookies();

// Setup the config, find the name and value from config table and then make the name = to value and stick it into $site_config
$result = mysql_query("SELECT `name`, `value` FROM `config`"); 
            
$site_config = array(); 
				
while ($array = mysql_fetch_array($result)) 
{ 
	$name = $array['name']; 
	$value = $array['value']; 
	$site_config[$name] = $value; 
}

// Get the language
include('language/' . $site_config['language'] . '.php');

// get the templating
include('includes/templating.php');

// set the ip to $ip
$ip = $_SERVER['REMOTE_ADDR'];

// Setting up the $user_info command for use with users
if (isset($_SESSION['user']) && isset($_SESSION['pass']))
{
	$user_info = mysql_fetch_array(mysql_query("SELECT * FROM `members` WHERE `user` = '".$_SESSION['user']."' AND `pass` = '".$_SESSION['pass']."'")); 
	mysql_query("UPDATE `members` SET `ip` = '".$ip."' WHERE `user` = '".$user_info['user']."'");
	lock_checker();
}

if (!isset($_SESSION['user']) && !isset($_SESSION['pass']))
{
	$user_info = mysql_fetch_array(mysql_query("SELECT * FROM `members` WHERE `user` = 'Guest'")); 
	mysql_query("UPDATE `members` SET `ip` = '".$ip."' WHERE `user` = '".$user_info['user']."'");
}

// Time format
$fadyt = date("H:i:s"); 
// Date format: d=day, n=month, y=year
$fadyd = date("d/n/y");
// 'Proper' format time, for db
$absolutetime = time();
?>