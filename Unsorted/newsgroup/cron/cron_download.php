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


// --------- Servers Administration ------------//
//
// File: cron.php
//
// Created: 07/06/2002
//
// Description:
//
// Script for cron job
//
//

// Iniciamos la sesión!!
session_start();

ini_set("max_execution_time",600);

include("../config.php");

// Need DB Connection
$db=new My_db;
$db->connect();

// MyNG setting up...
init();

// Set up the language
modules_get_language();

// Templates
$t = new Template($_SESSION['conf_system_root']."/themes/".$_SESSION['conf_vis_theme']."/templates/");

fetch_articles($cron = true);
 
// Update the cron counter
$query = "UPDATE myng_cron SET cron_num_times = cron_num_times + 1 WHERE cron_id = 1";
$db->query($query);

?>