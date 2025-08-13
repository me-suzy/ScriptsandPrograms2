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
// File: master.php
//
// Created: 07/06/2002
//
// Description:
//
// List of registered Configurations.
//
//

session_start();

// ----------- Systemcheck standalone or postnuke -------------- //

if(LOADED_AS_MODULE=="1") {
    // Define Modulename for Postnuke
    $ModName = basename( dirname( __FILE__ ) );
    $myng['system'] = "postnuke";
    // Include the postnuke config file
    include("modules/$ModName/config.php");
} else {
    $myng['system'] = "standalone";
    // Include the standard config file, with all the configuration and files required.
    include("../../config.php");
}
// ------------------------------------------------------------- //

// Need DB connection
$db=new My_db;
$db->connect();

// MyNG setting up...
init();


// Templates
$t = new Template($_SESSION['conf_system_root']."/admin/templates/conf");

// Check Authentication
if(!isset($_SESSION['adm_id'])){

    header("Location: ".$_SESSION['conf_system_prefix']."admin/index.php");

}else{

    // Main Template
    $main = "master.htm";
    $t->set_file("main",$main);
    $t->set_block("main","conf_block","conf_block_handle");
    $t->set_block("main","nav_block","nav_block");

    // Navigation bar query
    $consulta = "SELECT count(*) FROM myng_config";
    $db->query($consulta);
    $db->next_record();

	// Number of elements to show
	$num_elements = $db->Record[0];

	// If it's the first time here...
	if(!isset($_GET['page'])){
		$_GET['page'] = "1";
	}
		
	// Build the nav bar
	navigation_bar($num_elements,$_GET['page'],$_SESSION['conf_vis_nav_bar_items']);

	// To limit the query to the DB
	$first_element = ($_GET['page'] - 1) * $_SESSION['conf_vis_nav_bar_items'];

	// We get only the elements that we're going to show.
	$consulta = "SELECT conf_name,conf_active_yn,conf_id FROM myng_config LIMIT ".$first_element.",".$_SESSION['conf_vis_nav_bar_items']." ";
	$db->query($consulta);

    if($db->num_rows() == "0"){

		// There're no items in the DB		
		$t->set_var("error_message","Oops, there're no items in the DB.");

	}else{

	    // Show the items
		while($db->next_record()){
			
			$t->set_var("conf_name",$db->Record['conf_name']);
			if($db->Record['conf_active_yn'] == "Y"){
				$t->set_var("conf_active_yn","Yes");							
			}else{
				$t->set_var("conf_active_yn","No");								
			} 
			
			//$t->set_var("conf_description",$db->Record['conf_description']);
			$t->set_var("conf_id",$db->Record['conf_id']);
			$t->set_var("action","Query");
			$t->parse("conf_block_handle","conf_block",true);

		}

	}

    // ---- Show the HTML ---- //
    show_iface($main);
    // ----------------------- //

}

?>


