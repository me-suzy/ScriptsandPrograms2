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
// File: detail.php
//
// Created: 10/06/2002
//
// Description:
//
// List of registered NewsGroups.
//
//



session_start();

include("../../config.php");

// Need DB connection
$db=new My_db;
$db->connect();

$db2=new My_db;
$db2->connect();

// MyNG setting up...
init();

// Templates
$t = new Template($_SESSION['conf_system_root']."/admin/templates/newsgroups");

$main = "detail.htm";
$t->set_file("main",$main);
// Action links blocks
$t->set_block("main","del_block","del_block_handle");
$t->set_block("main","modify_block","modify_block_handle");
$t->set_block("main","add_block","add_block_handle");
// Other Blocks
$t->set_block("main","block_servers","block_servers_handle");

// Check Authentication
if(!isset($_SESSION['adm_id'])){

    // Redirect or change the Interface shown?
    header("Location: ".$_SESSION['conf_system_prefix']."admin/index.php");

}else{
    

	//----- Modify the DB entry ------//
	if(isset($_POST['action']) && $_POST['action'] == "Modify"){
					
		// Check if need to delete articles
		if($_POST['downloaded_articles'] < $_POST['downloaded_articles_old']){
					
			$num_articles = $_POST['downloaded_articles_old'] - $_POST['downloaded_articles'];
			// We delete the difference of articles
			del_articles($num_articles,$_POST['grp_name']);
		}
				

		// Check Cleaning up parameters
		if($_POST['clean_up'] == "no"){
			$clean_days = 0;
			$clean_articles = 0;
		}
		if($_POST['clean_up'] == "days"){
			$clean_days = $_POST['grp_MAX_days'];
			$clean_articles = 0;
		}
		if($_POST['clean_up'] == "articles"){
			$clean_days = 0;
			$clean_articles = $_POST['grp_MAX_articles'];
		}
		
		// Do the update
		$query = "UPDATE myng_newsgroup SET 
			grp_name='".$_POST['grp_name']."',
			grp_description='".$_POST['grp_description']."' ,			
			grp_allow_post_yn='".$_POST['grp_allow_post_yn']."',
			grp_MAX_days='".$clean_days."',
			grp_MAX_articles='".$clean_articles."'
			WHERE grp_id ='".$_POST['grp_id']."' ";

		$db->query($query);

    	header ("Location: master.php");

	}


    //------ Del the DB entry -------//
    
	if(isset($_POST['action']) && $_POST['action'] == "Del"){	

		del_group($_POST['grp_id'],$_POST['grp_name']);		
    	
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
    	
		// Redireccionamos a default!!
		header ("Location: master.php");


	}

    // -- Show the DB Record --------------------- //
    
	if(isset($_GET['action']) && $_GET['action'] == "Query"){


		$consulta = "SELECT * FROM myng_newsgroup WHERE grp_id = '".$_GET['grp_id']."'";				
		//echo $consulta;
    	$db->query($consulta);
		$db->next_record();
		
		$t->set_var("grp_name",$db->Record['grp_name']);
				
		$t->set_var("grp_description",$db->Record['grp_description']);
		
		//$available_articles = $db->Record['grp_last_article'] - $db->Record['grp_first_article'] + 1;		
		//$t->set_var("available_articles",$available_articles);		
		
		$t->set_var("downloaded_articles",$db->Record['grp_num_messages']);				
		$t->set_var("grp_num_available",$db->Record['grp_num_available']);				
		
		$t->set_var("grp_MAX_articles",$db->Record['grp_MAX_articles']);				
		$t->set_var("grp_MAX_days",$db->Record['grp_MAX_days']);				

		// Read only fields:		
		/*
		$t->set_var("grp_first_article",$db->Record['grp_first_article']);
		$t->set_var("grp_last_article",$db->Record['grp_last_article']);
		$t->set_var("grp_activity_index",$db->Record['grp_activity_index']);
		*/
			
		// Check Flag
		if($db->Record['grp_allow_post_yn'] == "Y"){
			// Check the checkbox
			$t->set_var("is_checked_yes","checked");
		}else{
			// Don't check it
			$t->set_var("is_checked_no","checked");
		}

		// Radio Buttons
		
		if($db->Record['grp_MAX_days'] != "0"){
			$t->set_var("clean_days_yes","checked");
		}
		if($db->Record['grp_MAX_articles'] != "0"){
			$t->set_var("clean_articles_yes","checked");
		}
		if($db->Record['grp_MAX_articles'] == "0" && $db->Record['grp_MAX_days'] == "0"){
			$t->set_var("clean_no","checked");
		}
		
        // Id
		$t->set_var("grp_id",$_GET['grp_id']);

		$t->set_var("return_page",$_SERVER['REQUEST_URI']);
		
		// Blocks to show the action links		
		$t->set_var("modify_link","javascript:modify()");
		$t->parse("modify_block_handle","modify_block",true);		
		$t->set_var("del_link","javascript:del()");
		$t->parse("del_block_handle","del_block",true);
		
		// ---- Show the HTML ---- //
        show_iface($main);
        // ----------------------- //


	}

    

}

?>








