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
// List of registered Servers.
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
$t = new Template($_SESSION['conf_system_root']."/admin/templates/servers");

$main = "detail.htm";
$t->set_file("main",$main);
// Action links blocks
$t->set_block("main","del_block","del_block_handle");
$t->set_block("main","modify_block","modify_block_handle");
$t->set_block("main","add_block","add_block_handle");
$t->set_block("main","group_list_block","group_list_block_handle");

// Check Authentication
if(!isset($_SESSION['adm_id'])){

    // Redirect or change the Interface shown?
    header("Location: ".$_SESSION['conf_system_prefix']."admin/index.php");

}else{
    

	//----- Modify the DB entry ------//
	if(isset($_POST['action']) && $_POST['action'] == "Modify"){

		$query = "UPDATE myng_server SET 
		     		 serv_host='".$_POST['serv_host']."',
			  		 serv_port='".$_POST['serv_port']."',
					 serv_login='".$_POST['serv_login']."',
					serv_passwd='".$_POST['serv_passwd']."'
			 	  WHERE serv_id ='".$_POST['serv_id']."'";

		$db->query($query);

    	header ("Location: master.php");

	}

    //----- Add a new DB entry ------//
	if(isset($_POST['action']) && $_POST['action'] == "Add"){
	
		$query = "INSERT INTO myng_server (
		
					serv_host,
					serv_port,
					serv_login,
					serv_passwd
		
				  ) VALUES (
		
					'".$_POST['serv_host']."',
					'".$_POST['serv_port']."',
					'".$_POST['serv_login']."',	
					'".$_POST['serv_passwd']."'
		
				)";
		
    	$db->query($query);		
		header ("Location: master.php");


	}

    //------ Del the DB entry -------//
    
	if(isset($_POST['action']) && $_POST['action'] == "Del"){

		// Del the server's info
		$query = "DELETE FROM myng_server WHERE serv_id ='".$_POST['serv_id']."' ";		
    	$db->query($query);

    	// Del ALL the groups of that server
    	$query = "SELECT grp_name, grp_id FROM myng_newsgroup WHERE grp_serv_id = '".$_POST['serv_id']."'";
    	$db->query($query);
    	
    	while($db->next_record()){    	    		
    		del_group($db->Record['grp_id'],$db->Record['grp_name']);	
    	}
    	
		// Redireccionamos a default!!
		header ("Location: master.php");


	}

    // -- Show the DB Record --------------------- //
    
	if(isset($_GET['action']) && $_GET['action'] == "Query"){

		// Recogemos la información para esa oferta de la BD

		$consulta = "SELECT * FROM myng_server WHERE serv_id = '".$_GET['serv_id']."'";		
    	$db->query($consulta);
		$db->next_record();

		$t->set_var("serv_host",$db->Record['serv_host']);
		$t->set_var("serv_port",$db->Record['serv_port']);
		$t->set_var("serv_login",$db->Record['serv_login']);
		$t->set_var("serv_passwd",$db->Record['serv_passwd']);

		// Show the newsgroups list file
		$file_path = $_SESSION['conf_system_root']."upload/lists/".real2table($db->Record['serv_host']).".list";
				
		// If the file do not exist..
		if(file_exists($file_path)){
			$t->set_var("file_name",real2table($db->Record['serv_host']).".list");
		}
		
		$t->set_var("serv_id",$_GET['serv_id']);
		
		// Blocks to show the action links		
		$t->set_var("modify_link","javascript:modify()");
		$t->parse("modify_block_handle","modify_block",true);		
		$t->set_var("del_link","javascript:del()");
		$t->parse("del_block_handle","del_block",true);
		
		// Show the newsgroup list
		$t->parse("group_list_block_handle","group_list_block",true);
		
		// ---- Show the HTML ---- //
        show_iface($main);
        // ----------------------- //


	}

    //----- No action, show the Form ----------- //
	if(!isset($_GET['action']) || $_GET['action'] == ""){

		$main = "detail.htm";
		
		$t->set_var("serv_port","119");
		
		// Blocks to show the action links		
		$t->set_var("add_link","javascript:add()");
		$t->parse("add_block_handle","add_block",true);

    // ---- Show the HTML ---- //
    show_iface($main);
    // ----------------------- //


	}

}

?>








