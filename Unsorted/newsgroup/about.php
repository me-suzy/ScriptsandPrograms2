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
// about.php
// Author: Carlos Sánchez
// Created: 23/06/01
//
// Description: Thread Page. We show all the 'parents' in a table.
//              We show also the date, the author, the number of
//              articles in the thread, etc.
//
// In:  We need the number of the group to show.
//
//
//
//------------------------------------------------------------------//
?>
<?
session_start();

include("config.php");

$start = start_time();

// Set up the language
modules_get_language();

// DB Connect
$db=new My_db;
$db->connect();

// MyNG setting up...
init();

// Templates
$t = new Template($_SESSION['conf_system_root']."/themes/".$_SESSION['conf_vis_theme']."/templates/");

// Fetch the latest articles
if(!fetch_articles($cron = false)){
	// Redirect to the error page, there're no groups at the system
	header("Location:".$_SESSION['conf_system_prefix']."error.php?error_id=1");	
}

//Registramos el momento actual
$current_time=time();

// Manage the login module
$left_bar = manage_login($current_time,$t,$db);

$system_info = "Welcome to MyNewsGroups :) v ".MYNG_VERSION;
$main = "about.htm";
$t->set_file("main",$main);

$finish = finish_time($start);
$t->set_var("page_time",$finish);

// Show all the page
show_layout($t,$left_bar,$system_info,MYNG_VERSION);


?>

