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
// actions.php
// Author: Carlos Sánchez
// Created: 30/11/02
//
// Description: Actions Controller.
//
//
//------------------------------------------------------------------//
?>
<?
session_start();

include("config.php");

$db = new My_db;
$db2 = new My_db;
$db->connect();

// MyNG setting up...
init();

// Mark all articles as read
if($_GET['action'] == "mark_all_read"){
			
	$result = mark_all_read();
	if($result == 1){
		header("Location:".$_SERVER['HTTP_REFERER']);	
	}
}

// Mark all thread articles as read
if($_GET['action'] == "mark_thread_read"){
			
	$art_id = $_GET['art_id'];	
	$result = mark_thread_read($art_id);
	if($result == 1){
		header("Location:".$_SERVER['HTTP_REFERER']);	
	}
}


?>