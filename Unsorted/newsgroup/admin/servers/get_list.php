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
// File: detail.php
//
// Created: 10/06/2002
//
// Description:
//
// Build List of Available NewsGroups.
//
//

session_start();

// Systemcheck standalone or postnuke
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

// Need DB connection
$db=new My_db;
$db->connect();

// MyNG setting up...
init();

// Templates
$t = new Template($_SESSION['conf_system_root']."/admin/templates/servers");
$main = "get_list.htm";
$t->set_file("main",$main);
$t->set_block("main","init_block","init_block_handle");
$t->set_block("main","success_block","success_block_handle");


// Check Authentication
if(!isset($_SESSION['adm_id'])){

    // Redirect or change the Interface shown?
    header("Location: ".$_SESSION['conf_system_prefix']."admin/index.php");

}else{

	// GET variables
	$server = $_GET['server'];
	//echo $server;
	
	// Get the NewsGroups List
	// Beware!! This function needs a lot of time to
	// perform (Depends on your bandwith.)
	
	// Change the 'max_execution_time' parameter of php.ini
	ini_set("max_execution_time","100000");
		
	
	// Build the required file and store it 
	// in 'upload/lists'
	
	// Check if the form has been submitted
	if(isset($_POST['action']) && $_POST['action'] == "get_list"){

		// POST variables
		$server = $_POST['server'];
		
		// Get the NewsGroups List!!
		// $list = list_groups($server);
		
		$file_path = $_SESSION['conf_system_root']."upload/lists/".real2table($_POST['server']).".list";
				
		// If the file do not exist..
		if(!file_exists($file_path)){
			// Build the file that holds the newsgroups name
			$fp = fopen ($file_path, "wb");
			$list = list_groups($server);
			foreach($list as $value){
				fwrite($fp,$value."\n");
			}
			fclose($fp);
		}
		
		// Get the number of newsgroups available
		$i=0;
		$fcontents = file($file_path);
		while (list ($line_num, $line) = each ($fcontents)) {
    		//echo "<b>Line $line_num:</b>; ", htmlspecialchars ($line), "<br>\n";
    		$i ++;
		}

		
		$t->set_var("style_dir",$_SESSION['conf_system_prefix']."themes/".$_SESSION['conf_vis_theme']."/styles");
		$t->set_var("home_dir",$_SESSION['conf_system_prefix']);
		$t->set_var("server",$_POST['server']);
		$t->set_var("num_groups",$i);
		$t->set_var("list_file",real2table($_POST['server']).".list");
		$t->set_var("list_file_size",round(filesize($file_path)/1024,2));		
		
		$t->parse("success_block_handle","success_block",true);		
		
		// Show the interface
		$t->parse("out","main");	
		$t->p("out");
		
	}else{
	
		
		// Style Sheet
		$t->set_var("style_dir",$_SESSION['conf_system_prefix']."themes/".$_SESSION['conf_vis_theme']."/styles");
		$t->set_var("home_dir",$_SESSION['conf_system_prefix']);
		$t->set_var("server",$_GET['server']);
		
		$t->parse("init_block_handle","init_block",true);		
		
		//echo "kk".$_GET['server'];
		
		// Show the interface
		$t->parse("out","main");	
		$t->p("out");
		
	
	}
	
}










?>