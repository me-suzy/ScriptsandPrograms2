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
// Created: 06/08/2002
//
// Description:
//
// Detail of Users
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
$t = new Template($_SESSION['conf_system_root']."/admin/templates/users");

$main = "detail.htm";
$t->set_file("main",$main);
// Action links blocks
$t->set_block("main","del_block","del_block_handle");
$t->set_block("main","modify_block","modify_block_handle");
$t->set_block("main","add_block","add_block_handle");
// Other Blocks
$t->set_block("main","language_block","language_block_handle");
$t->set_block("main","theme_block","theme_block_handle");
$t->set_block("main","block_password","block_password_handle");

// Check Authentication
if(!isset($_SESSION['adm_id'])){

    // Redirect or change the Interface shown?
    header("Location: ".$_SESSION['conf_system_prefix']."admin/index.php");

}else{
    

	//----- Modify the DB entry ------//
	if(isset($_POST['action']) && $_POST['action'] == "Modify"){
					
	$query = "UPDATE myng_user SET 
		
		usr_text='".$_POST['usr_text']."' ,
		usr_email_visible_yn='".$_POST['usr_email_visible_yn']."', 		
		usr_email='".$_POST['usr_email']."',
		usr_fst_name='".$_POST['usr_fst_name']."',
		usr_lst_name='".$_POST['usr_lst_name']."',
		usr_theme = '".$_POST['usr_theme']."',
		usr_icq='".$_POST['usr_icq']."'
	
		
		WHERE usr_id ='".$_POST['usr_id']."' ";
		//echo $query;
	
		$db->query($query);
    	header ("Location: master.php");
	}

    //----- Add a new DB entry ------//
	if(isset($_POST['action']) && $_POST['action'] == "Add"){

	
		$query = "INSERT INTO myng_user (
		
			usr_name,
			usr_passwd,
			usr_text,			
			usr_email_visible_yn, 
			usr_email,
			usr_fst_name,
			usr_lst_name,
			usr_theme,
			usr_icq
			
			) VALUES (
		
			'".$_POST['usr_name']."',
			'".md5($_POST['usr_passwd'])."' ,
			'".$_POST['usr_text']."', 
			'".$_POST['usr_email_visible_yn']."',
			'".$_POST['usr_email']."',
			'".$_POST['usr_fst_name']."',
			'".$_POST['usr_lst_name']."',
			'".$_POST['usr_theme']."',
			'".$_POST['usr_icq']."'
				
			)";

    	$db->query($query);
		
		header ("Location: master.php");


	}

    //------ Del the DB entry -------//
    
	if(isset($_POST['action']) && $_POST['action'] == "Del"){
		
		$query = "DELETE FROM myng_user WHERE usr_id ='".$_POST['usr_id']."' ";				
    	$db->query($query);
    	
		// Redireccionamos a default!!
		header ("Location: master.php");


	}

    // -- Show the DB Record --------------------- //
    
	if(isset($_GET['action']) && $_GET['action'] == "Query"){



		$consulta = "SELECT * FROM myng_user WHERE usr_id = '".$_GET['usr_id']."'";
		//echo $consulta;
    	$db->query($consulta);
		$db->next_record();	
		
		$t->set_var("usr_name",$db->Record['usr_name']);				
		$t->set_var("usr_text",$db->Record['usr_text']);
		if($db->Record['usr_email_visible_yn'] == "Y"){		
			$t->set_var("usr_email_visible_yes","checked");
		}else{		
			$t->set_var("usr_email_visible_no","checked");
		}
		$t->set_var("usr_passwd",$db->Record['usr_passwd']);				
		$t->set_var("usr_email",$db->Record['usr_email']);				
		$t->set_var("usr_fst_name",$db->Record['usr_fst_name']);				
		$t->set_var("usr_lst_name",$db->Record['usr_lst_name']);				
		//$t->set_var("usr_country",$db->Record['usr_country']);				
		$t->set_var("usr_icq",$db->Record['usr_icq']);				
		
		
		// Try to read the available Themes
		if ($handle = opendir("../../themes")) {
    		while (false !== ($file = readdir($handle))) { 
    			// Check if the directory name is '.','..','CVS' or if it's a directory.
        		if ($file != "." && $file != ".." && filetype("../../themes/".$file) == "dir" && $file != "CVS") { 
 	                $t->set_var("theme",$file);
 	                // Check for the current language
 	                if($file == $db->Record['usr_theme'] ){ 	                	 	               
 	                	$t->set_var("theme_is_selected","selected");
 	                }else{ 
 	                	$t->set_var("theme_is_selected",""); 	                
 	                }
                	$t->parse("theme_block_handle","theme_block",true);            		
        		} 
    		}
    		closedir($handle); 
		}
							
        // Id
		$t->set_var("usr_id",$_GET['usr_id']);
		
		// Blocks to show the action links		
		$t->set_var("modify_link","javascript:modify()");
		$t->parse("modify_block_handle","modify_block",true);		
		$t->set_var("del_link","javascript:del()");
		$t->parse("del_block_handle","del_block",true);
		
		// Make the User Name field read only
		$t->set_var("is_usr_name_readonly","readonly");		
		
		// ---- Show the HTML ---- //
        show_iface($main);
        // ----------------------- //


	}

	
	    //----- No action, show the Form ----------- //
	if(!isset($_GET['action']) || $_GET['action'] == ""){

		$main = "detail.htm";
				
		// Try to read the available Themes
		if ($handle = opendir("../../themes")) {
    		while (false !== ($file = readdir($handle))) { 
    			// Check if the directory name is '.','..','CVS' or if it's a directory.
        		if ($file != "." && $file != ".." && filetype("../../themes/".$file) == "dir" && $file != "CVS") { 
 	                $t->set_var("theme",$file);
 	               	$t->set_var("theme_is_selected",""); 	                 	  
                	$t->parse("theme_block_handle","theme_block",true);            		
        		} 
    		}
    		closedir($handle); 
		}

		// Blocks to show the action links		
		$t->set_var("add_link","javascript:add()");
		$t->parse("add_block_handle","add_block",true);

		// Make the User Name field writable
		$t->set_var("is_usr_name_readonly","");
		
		// Show the password input text field
		$t->parse("block_password_handle","block_password",true);

    // ---- Show the HTML ---- //
    show_iface($main);
    // ----------------------- //


	}
    

}

?>








