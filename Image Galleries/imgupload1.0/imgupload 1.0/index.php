<?php
/************************************************************************
Title: ImgUpload 1.0
Coded by: hellscythe191 | hellscythe.net
Bug reports: darthsidious666@gmail.com
Template by: gsvnet | zeonfx.com

Description: The newest and possibly last ImgUpload script. This script offers nearly
complete control over the script functions and a crapload of new features and functions.

Project started: 8:20:33 PM Friday, October 07, 2005
Project complete: 8:13 PM Sunday, October 30, 2005

This script may NOT be sold or re-distrubuted without giving proper credit.
*************************************************************************/

ini_set('display_errors', 1);
error_reporting(E_ALL & ~ E_NOTICE);

session_start();

if(file_exists("install.php")) { header('Location: install.php'); }
include "mysql_data.db.php";

mysql_connect($mysqlhost, $mysqluser, $mysqlpass);
mysql_select_db($mysqldb);

include "user.php";
include "admin.php";
include "upload.php";
include "functions.php";

$user_process = new user();
$exitp = new functions();
$footer = new functions();
$config = new functions();
$settings = $config->settings();

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>|| Img Upload ||</title>
  <meta http-equiv="Content-Type"
 content="text/html; charset=iso-8859-1">
  <style type="text/css">
<!--
body,td,th {
	color: #333333;
	font-family: Lucida Sans;
	font-size: 12px;
}
a:link {
	color: #2757AF;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #2757AF;
}
a:hover {
	text-decoration: underline;
	color: #7B9FE1;
}
a:active {
	text-decoration: none;
	color: #2757AF;
}
body {
	background-image: url(images/bg.gif);
	margin-left: 0px;
	margin-right: 0px;
	margin-top: 50px;
	margin-bottom: 50px;
}	
input {
    background-color: #F2F2F2; 
    color: #2554AA;
    font-family: Verdana;
    font-size: 11;
}
select {
    background-color: #F2F2F2; 
    color: #2554AA;
    font-family: Verdana;
    font-size: 11;
}
textarea{
    background-color: #F2F2F2; 
    color: #2554AA;
    font-family: Verdana;
    font-size: 11;
}
}
.style2 {font-size: 9px}
.style3 {font-size: 12px}
.style5 {color: #2757AF}
-->
 </style>
</head>
<body>
<table align="center" border="0" cellpadding="0" cellspacing="0"
 width="580">
  <tbody>
    <tr>
      <td colspan="3" align="left" background="images/index_01.gif"
 height="137" valign="bottom">
      <p class="style3">  <br>
      </p>
      </td>
    </tr>
    <tr>
      <td colspan="3"> <img src="images/index_02.gif" alt=""
 height="15" width="580"></td>
    </tr>
    <tr>
      <td rowspan="5" align="left" background="images/index_03.gif"
 valign="top"> <img src="images/index_03.gif" alt="" height="174"
 width="21"></td>
      <td align="left" background="images/index_04.gif" valign="middle"><center>
		</center>
      <br>
      </span></strong> </td>
      <td rowspan="5" align="left" background="images/index_05.gif"
 valign="top" width="24">&nbsp;</td>
    </tr>
    <tr>
      <td> </td>
    </tr>
    <tr>
      <td align="left" bgcolor="#ffffff" height="130" valign="top"
 width="535">
      <p><a href="#"></a>&nbsp;</p>
      <center>

<?php
$user_get = $user_process->process_user();
$user_div = explode(':', $user_get);
$user_rank = $user_div[1];
$user_name = $user_div[2];

if($user_rank == "guest")
{
	// They're a guest, so display the guest navigation
	$guest = new user();
	if($_GET['user'])
	{
		switch($_GET['user'])
		{
			case register:
				echo '<p align="left"><u>Guest functions - Registration</u></p>';
				$guest->register($user_rank);
				$exitp->exitp($user_rank);
			break;
			case login:
				echo '<p align="left"><u>Guest functions - Log in</u></p>';
				$guest->login($user_rank);
				$exitp->exitp($user_rank);
			break;
			default:
				echo "This is not a valid action.";
				$exitp->exitp($user_rank);
			break;
		}
	}
	if(($settings['display_guest'] != "yes") && ($settings['display_login'] != "yes"))
	{
		echo "Welcome guest! Use the links below to continue.";
	}
	if($settings['display_guest'] == "yes")
	{
		echo $settings['final_guest_message'];
	}
	if($settings['display_login'] == "yes")
	{
		echo '<p><form action="' . $_SERVER['PHP_SELF'] . '?user=login" method="post" />
			  Username: <input type="text" name="user" /><br />
			  Password: <input type="password" name="pass" /><br />
			  <input type="submit" name="login" value="Log In" /></p>
			  </form>';
	}
	$exitp->exitp($user_rank);
}

// They're not a guest, w00t! 
$user = new user();
$uploads = new uploads();

if($_GET['user'])
{
	switch($_GET['user'])
	{
		case logout:
			echo '<p align="left"><u>User functions - Log out</u></p>';
			$user->logout($user_rank);
			$exitp->exitp($user_rank);
		break;
		case profile:
			echo '<p align="left"><u>User functions - Edit profile</u></p>';
			$user->editpro($user_rank, $user_name);
			$exitp->exitp($user_rank);
		break;
		default:
			echo "This is not a valid action.";
		break;
	}
}

if($_GET['action'])
{
	switch($_GET['action'])
	{
		case upload:
			echo '<p align="left"><u>Upload functions - Upload file</u></p>';
			$imgupload = $_FILES['imgupload'];
			$uploads->upload($user_name, $user_rank, $imgupload);
			$exitp->exitp($user_rank);
		break;
		case imgdir:
			echo '<p align="left"><u>Upload functions - Browse directory</u></p>';
			$uploads->display($user_name, $user_rank);
			$exitp->exitp($user_rank);
		break;
		case rename:
			echo '<p align="left"><u>Upload functions - Rename image</u></p>';
			if(!empty($_GET['rename']) && !empty($_GET['imgname']))
			{
				$uploads->rename($user_name, $user_rank);
			} else {
				echo "You didn't select an image to rename.";
			}
			$exitp->exitp($user_rank);
		break;
		case delete:
			echo '<p align="left"><u>Upload functions - Delete image</u></p>';
			if(!empty($_GET['delete']))
			{
				$uploads->delete($user_name, $user_rank);
			} else {
				echo "You didn't select an image to delete.";
			}
			$exitp->exitp($user_rank);
		break;
		default:
			echo "This is not a valid action.";
			$exitp->exitp($user_rank);
		break;
	}
}

if($user_rank != "admin")
{
	if($settings['display_global'] == "yes")
	{
		echo $settings['final_global_message'];
	} else {
		echo "Welcome, " . $user_name . ". Use the links below to navigate through the script.";
	}
	if($_GET['admin'])
	{
		echo "You can't access admin functions.";
	}
	$exitp->exitp($user_rank);
}

$check_firstlogin = new functions();
$check_login = $check_firstlogin->settings();

if($check_login['first_login'] == "yes")
{
	mysql_query("UPDATE imgup_config SET first_login='no'");
	echo '<script>window.location="' . $_SERVER['PHP_SELF'] . '?admin=settings"</script>';
}
$admin = new admin();
if($_GET['admin'])
{
	switch($_GET['admin'])
	{
		case settings:
			echo '<p align="left"><u>Admin functions - Modify settings</u></p>';
			$admin->settings($user_rank);
			$exitp->exitp($user_rank);
		break;
		case newuser:
			echo '<p align="left"><u>Admin functions - Create new user</u></p>';
			$admin->adduser($user_rank);
			$exitp->exitp($user_rank);
		break;
		case userlist:
			echo '<p align="left"><u>Admin functions - List all users</u></p>';
			$admin->viewusers($user_rank);
			$exitp->exitp($user_rank);
		break;
		case edituser:
			echo '<p align="left"><u>Admin functions - Edit user</u></p>';
			$admin->edituser($user_rank, $user_name);
			$exitp->exitp($user_rank);
		break;
		case check_updates:
			echo '<p align="left"><u>Admin functions - Check for updates</u></p>';
			$admin->check_update($user_rank);
			$exitp->exitp($user_rank);
		default:
			echo "This is not a valid action.";
			$exitp->exitp($user_rank);
		break;
	}
}

if($settings['display_global'] == "yes")
{
	echo $settings['final_global_message'];
} else {
	echo "Welcome " . $user_name . " use the links below to navigate through the script.";
}
$footer->footer($user_rank);
?>