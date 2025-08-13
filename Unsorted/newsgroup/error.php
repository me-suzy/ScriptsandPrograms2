<?
// ---------------------------------------------------------------------------- //
// MyNewsGroups :) 'Share your knowledge'
// Copyright (C) 2002 Carlos Sánchez Valle (yosoyde@bilbao.com)

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------------------------- //

//------------------------------------------------------------------//
// error.php
// Author: Carlos Sánchez
// Created: 23/06/01
//
// Description: Error Page
//
//
//------------------------------------------------------------------//
?>
<?

session_start();

include("config.php");

$db=new My_db;
$db->connect();

// MyNG setting up...
init();

// Set up the language
modules_get_language();

// Templates
$t = new Template($_SESSION['conf_system_root']."/themes/".$_SESSION['conf_vis_theme']."/templates/");

//Registramos el momento actual
$current_time=time();

// Manage the login module
$left_bar = manage_login($current_time,$t,$db);

// Here we list the error messages
$error_messages[0] = _MYNGCON_ERROR;
$error_messages[1] = _MYNGNOGROUPS;
$error_messages[2] = _MYNGUNKNOWN_GROUP;
$error_messages[3] = _MYNGMUST_LOGIN;
$error_messages[4] = _MYNGREAD_ONLY;
$error_messages[5] = _MYNGNO_ARTICLES;

$system_info = _MYNGNOGROUPS;
$main = "error.htm";
$t->set_file("main",$main);
$t->set_var("error_message", $error_messages[$_GET['error_id']]);

$finish = finish_time($start);
$t->set_var("page_time",$finish);

// Show all the page
show_layout($t,$left_bar,$system_info,MYNG_VERSION);
exit();

?>

