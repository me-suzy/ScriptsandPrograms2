<?
//------------------------------------------------------------------//
// mystats.php
// Author: Carlos Sánchez
// Created: 14/01/02
// Last Modified: 14/01/02
//
// Description: TODO
//
//
//------------------------------------------------------------------//
?>
<?
session_start();

include("config.php");


// Templates
$t = new Template($myng_dir['themes']."/".$myng['theme']."/templates/");

// Set up the language
modules_get_language();

$db=new My_db;
$db->connect();

//Registramos el momento actual
$current_time=time();

// Manage the login module
$left_bar = manage_login($current_time,$t,$login_switch,$db);

$system_info = "Not Available Yet.";
$t->set_var("error_message","Not Available Yet.");

$main = "error.htm";
$t->set_file("main",$main);

$finish = finish_time($start);
$t->set_var("page_time",$finish);

// Show all the page
show_layout($t,$left_bar,$system_info,$myng['version']);
?>


