<?php
//Data Base Connection************************************************************************
$userid="";				//MySql server user id
$password="";		//password for your user id
$database="demoalbum";		//Database
$cnn=mysql_connect("localhost",$userid,$password) or die ("Unable to stablish mysql connection!");
mysql_select_db($database,$cnn);
//********************************************************************************************

//Settings************************************************************************************
$baseDir = "D:/phps/test/demoPicGallery/Albums/";							   //Path to your picture directory
$virtualPath = "http://".$_SERVER['HTTP_HOST']."/phps/test/demoPicGallery/Albums/";		   //Web address of your picture directory
$thumbDir = "D:/phps/test/demoPicGallery/thumbs/";										   //Path to your thumbnails directory
$virtualPathThumb = "http://".$_SERVER['HTTP_HOST']."/phps/test/demoPicGallery/thumbs/";   //Web address to your thumbnails directory

$theTitle = "My Picture Gallery";									   //Album Title
$greetings = "Welcome to My Picture Gallery";						   //Greetings on login page
//********************************************************************************************

//Do not change anything here*****************************************************************
$user = mysql_query("select * from album_users where ID = '$_SESSION[userID]'") or die(mysql_error());
$rowUser = mysql_fetch_array($user);
$userType = $rowUser['userType'];
$userName = $rowUser['userID'];
$userFullName = $rowUser['fullName'];
//*********************************************************************************************
?>