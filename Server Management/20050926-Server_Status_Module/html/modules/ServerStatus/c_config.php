<?php
if (eregi("c_config.php",$PHP_SELF)) 
{
 Header("Location: ../../index.php");
 die();
}

//Server Status
$nome_menu = "Server Status";
$cpanel_username = "username"; //cpanel username account
$cpanel_password = "password"; //cpanel password account
$server_ip = "69.69.69.69"; //xxx.xxx.xxx.xxx ip number server hosted
$cpanel_port = "2082"; //default cpanel port = 2082
$cpanel_theme = "x"; //cpanel theme
?>