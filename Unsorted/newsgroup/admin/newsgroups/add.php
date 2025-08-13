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


// --------- newsgroups Administration ------------//
//
// File: add.php
//
// Created: 10/06/2002
//
// Description:
//
// Form to add new NewsGroups to the System
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

$db2=new My_db;
$db2->connect();

// MyNG setting up...
init();


// Templates
$t = new Template($_SESSION['conf_system_root']."/admin/templates/newsgroups");

$main = "add.htm";
$t->set_file("main",$main);
// Action links blocks
$t->set_block("main","del_block","del_block_handle");
$t->set_block("main","modify_block","modify_block_handle");
$t->set_block("main","add_block","add_block_handle");
// Other Blocks
$t->set_block("main","block_servers","block_servers_handle");
$t->set_block("main","inner_block","inner_block_handle");
$t->set_block("main","outter_block","outter_block_handle");

// Check Authentication
if(!isset($_SESSION['adm_id'])){

    // Redirect or change the Interface shown?
    header("Location: ".$_SESSION['conf_system_prefix']."admin/index.php");

}else{
    
	// If the checkBox is UnChecked !!
	if($grp_allow_post_yn != "Y"){
		$grp_allow_post_yn = "N";		
	}	

    //----- Add a new DB entry ------//
	if(isset($_POST['action']) && $_POST['action'] == "Add"){
			
	
		// $num_articles is the initial amount of articles to download
		// The new downloading and indexing system allow us
		// to download the articles step by step as pages are going
		// called by the users.	
		$num_articles = $_POST['num_articles'];		
	    $result = add_group($_POST['grp_name'],$_POST['grp_serv_host'],$num_articles,$_SESSION['conf_system_zlib_yn'],$_POST['grp_allow_post_yn']);

	   
	   if($result[0] == "false"){
       // Can't connect to News Server
       // Show the Error Page. Only the System Admin can view this page.
       $system_info = $result[1];
       //$system_info = _MYNGCON_ERROR;
       //$t->set_var("error_message",$system_info);
       //echo $system_info;
       //$main = "error.htm";
       //$t->set_file("main",$main);
       // Show all the page
       //show_layout($t,$left_bar,$system_info,$version);
       //exit();

	   }
	   

	   // Get the admin authentication 
        $adm_login = $_SESSION['adm_login'];
        $adm_id = $_SESSION['adm_id'];
		
    	// Destroy the session
		session_destroy();
		
		session_start();
		
		// Log the user admin
		$_SESSION['adm_login'] = $adm_login ;
        $_SESSION['adm_id'] = $adm_id;
        
        // Provoke initialization
		init();
		    	
		header ("Location: master.php");


	}	

    // -- Show the DB Record --------------------- //

	if(isset($action) && $action == "Query"){



		$consulta = "SELECT * FROM myng_newsgroup WHERE grp_id = '".$grp_id."'";
		//echo $consulta;
    	$db->query($consulta);
		$db->next_record();

		$t->set_var("grp_name",$db->Record['grp_name']);

		$t->set_var("grp_description",$db->Record['grp_description']);
		// Read only fields:
		/*
		$t->set_var("grp_num_messages",$db->Record['grp_num_messages']);
		$t->set_var("grp_first_article",$db->Record['grp_first_article']);
		$t->set_var("grp_last_article",$db->Record['grp_last_article']);
		$t->set_var("grp_activity_index",$db->Record['grp_activity_index']);
		*/

		// Check Flag
		if($db->Record['grp_allow_post_yn'] == "Y"){
			// Check the checkbox
			$t->set_var("is_checked","checked");
		}else{
			// Don't check it
			$t->set_var("is_checked","");
		}

		// Server's List
		$query = "SELECT * FROM myng_server";
		$db2->query($query);
		while($db2->next_record()){
			$t->set_var("serv_id",$db2->Record['serv_id']);
			$t->set_var("serv_host",$db2->Record['serv_host']);
			if($db2->Record['serv_id'] == $db->Record['grp_serv_id']){
				$t->set_var("is_selected","SELECTED");
			}else{
				$t->set_var("is_selected","");
			}
			$t->parse("block_servers_handle","block_servers",true);
		}

        // Id
		$t->set_var("grp_id",$grp_id);

		// Blocks to show the action links
		$t->set_var("modify_link","javascript:modify()");
		$t->parse("modify_block_handle","modify_block",true);
		$t->set_var("del_link","javascript:del()");
		$t->parse("del_block_handle","del_block",true);

		// ---- Show the HTML ---- //
        show_iface($main);
        // ----------------------- //


	}

    //----- No action, show the Form ----------- //
	if(!isset($action)){

		// Change the 'max_execution_time' parameter of php.ini
		ini_set("max_execution_time","1000");

		$main = "add.htm";

		// Server's List
		$query = "SELECT * FROM myng_server";			
		$db2->query($query);		
			
		while($db2->next_record()){

			$t->set_var("serv_id",$db2->Record['serv_id']);
			$t->set_var("serv_host",$db2->Record['serv_host']);
			$t->set_var("num_server",$i);						
			$t->parse("block_servers_handle","block_servers",true);						
							
		}
				
		// Blocks to show the action links		
		$t->set_var("add_link","javascript:add()");
		$t->parse("add_block_handle","add_block",true);
		
		// Select 'Allow Posting No By default'
		$t->set_var("is_checked_no","checked");

    // ---- Show the HTML ---- //
    show_iface($main);
    // ----------------------- //


	}

}

?>








