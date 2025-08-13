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
// File: admin_home.php
//
// Created: 07/06/2002
//
// Description:
//
// Home of Administration Interface
//
//

// Iniciamos la sesión!!
session_start();

include("../config.php");

// Need DB Connection
$db=new My_db;
$db->connect();

// MyNG setting up...
init();

// Templates
$t = new Template($_SESSION['conf_system_root']."/admin/templates/");

$main = "admin_home.htm";
$t->set_file("main",$main);

// Check Authentication
if(!isset($_SESSION['adm_id'])){
//if(!session_is_registered("Admin")){

    header("Location: ".$_SESSION['conf_system_prefix']."admin/index.php");

}else{

    // ---- Show the HTML ---- //
    show_iface($main);
    // ----------------------- //

}

?>


