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
// Detail of Configurations
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
$t = new Template($_SESSION['conf_system_root']."/admin/templates/conf");

$main = "detail.htm";
$t->set_file("main",$main);
// Action links blocks
$t->set_block("main","del_block","del_block_handle");
$t->set_block("main","modify_block","modify_block_handle");
$t->set_block("main","add_block","add_block_handle");
// Other Blocks
$t->set_block("main","language_block","language_block_handle");
$t->set_block("main","theme_block","theme_block_handle");
$t->set_block("main","apply_articles_block","apply_articles_block_handle");
$t->set_block("main","apply_days_block","apply_days_block_handle");

// Check Authentication
if(!isset($_SESSION['adm_id'])){

    // Redirect or change the Interface shown?
    header("Location: ".$_SESSION['conf_system_prefix']."admin/index.php");

}else{
    

	//----- Modify the DB entry ------//
	if(isset($_POST['action']) && $_POST['action'] == "Modify"){
		
		if($_POST['clean_up'] == "no"){
			$clean_days = 0;
			$clean_articles = 0;
		}
		if($_POST['clean_up'] == "days"){
			$clean_days = $_POST['conf_clean_MAX_days'];
			$clean_articles = 0;
		}
		if($_POST['clean_up'] == "articles"){
			$clean_days = 0;
			$clean_articles = $_POST['conf_clean_MAX_articles'];
		}

		$query = "UPDATE myng_config SET 
		
			conf_name='".$_POST['conf_name']."',
			conf_description='".$_POST['conf_description']."' ,
			conf_active_yn='".$_POST['conf_active_yn']."', 
			conf_system_prefix='".$_POST['conf_system_prefix']."',
			conf_system_root='".$_POST['conf_system_root']."',
			conf_system_language='".$_POST['conf_system_language']."',
			conf_down_days='".$_POST['conf_down_days']."',
			conf_down_list_items='".$_POST['conf_down_list_items']."',
			conf_down_num_groups='".$_POST['conf_down_num_groups']."',
			conf_down_num_articles='".$_POST['conf_down_num_articles']."',
			conf_clean_MAX_articles='".$clean_articles."',
			conf_clean_MAX_days='".$clean_days."',
			conf_vis_theme='".$_POST['conf_vis_theme']."',
			conf_vis_num_2_flames='".$_POST['conf_vis_num_2_flames']."',
			conf_vis_articles_x_page='".$_POST['conf_vis_articles_x_page']."',
			conf_vis_nav_bar_items='".$_POST['conf_vis_nav_bar_items']."',
			conf_vis_nav_bar_pages='".$_POST['conf_vis_nav_bar_pages']."',
			conf_vis_time_highlight_new='".($_POST['conf_vis_time_highlight_new'] * 3600)."',
			conf_sec_secret_string='".$_POST['conf_sec_secret_string']."',
			conf_system_debug_yn='".$_POST['conf_system_debug_yn']."',			
			conf_system_login_yn='".$_POST['conf_system_login_yn']."',
			conf_system_online_yn='".$_POST['conf_system_online_yn']."',
			conf_sec_protect_email_yn='".$_POST['conf_sec_protect_email_yn']."',
			conf_sec_send_poster_host_yn='".$_POST['conf_sec_send_poster_host_yn']."',
			conf_sec_test_group_yn='".$_POST['conf_sec_test_group_yn']."',
			conf_sec_validate_email_yn='".$_POST['conf_sec_validate_email_yn']."'
		
			WHERE conf_id ='".$_POST['conf_id']."' ";

		//echo $query;
		
		$db->query($query);
		
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

    //----- Add a new DB entry ------//
	if(isset($_POST['action']) && $_POST['action'] == "Add"){

		if($_POST['clean_up'] == "no"){
			$clean_days = 0;
			$clean_articles = 0;
		}
		if($_POST['clean_up'] == "days"){
			$clean_days = $_POST['conf_clean_MAX_days'];
			$clean_articles = 0;
		}
		if($_POST['clean_up'] == "articles"){
			$clean_days = 0;
			$clean_articles = $_POST['conf_clean_MAX_articles'];
		}
	
		$query = "INSERT INTO myng_config (
		
			conf_name,
			conf_description,
			conf_active_yn, 
			conf_system_prefix,
			conf_system_root,
			conf_system_language,
			conf_down_days,
			conf_down_list_items,
			conf_down_num_groups,
			conf_down_num_articles,
			conf_clean_MAX_articles,
			conf_clean_MAX_days,
			conf_vis_theme,
			conf_vis_num_2_flames,
			conf_vis_articles_x_page,
			conf_vis_nav_bar_items,
			conf_vis_nav_bar_pages,
			conf_vis_time_highlight_new,
			conf_sec_secret_string,
			conf_system_debug_yn,			
			conf_system_login_yn,
			conf_system_online_yn,
			conf_sec_protect_email_yn,
			conf_sec_send_poster_host_yn,
			conf_sec_test_group_yn,
			conf_sec_validate_email_yn		
		
			) VALUES (
		
			'".$_POST['conf_name']."',
			'".$_POST['conf_description']."' ,
			'".$_POST['conf_active_yn']."', 
			'".$_POST['conf_system_prefix']."',
			'".$_POST['conf_system_root']."',
			'".$_POST['conf_system_language']."',
			'".$_POST['conf_down_days']."',
			'".$_POST['conf_down_list_items']."',
			'".$_POST['conf_down_num_groups']."',
			'".$_POST['conf_down_num_articles']."',
			'".$clean_articles."',
			'".$clean_days."',
			'".$_POST['conf_vis_theme']."',
			'".$_POST['conf_vis_num_2_flames']."',
			'".$_POST['conf_vis_articles_x_page']."',
			'".$_POST['conf_vis_nav_bar_items']."',
			'".$_POST['conf_vis_nav_bar_pages']."',
			'".($_POST['conf_vis_time_highlight_new'] * 3600)."',
			'".$_POST['conf_sec_secret_string']."',
			'".$_POST['conf_system_debug_yn']."',			
			'".$_POST['conf_system_login_yn']."',
			'".$_POST['conf_system_online_yn']."',
			'".$_POST['conf_sec_protect_email_yn']."',
			'".$_POST['conf_sec_send_poster_host_yn']."',
			'".$_POST['conf_sec_test_group_yn']."',
			'".$_POST['conf_sec_validate_email_yn']."'
				
			)";

    	$db->query($query);

		header ("Location: master.php");


	}

    //------ Del the DB entry -------//
    
	if(isset($_POST['action']) && $_POST['action'] == "Del"){
		
		$query = "DELETE FROM myng_config WHERE conf_id ='".$_POST['conf_id']."' ";		
    	$db->query($query);
    	
		// Redireccionamos a default!!
		header ("Location: master.php");


	}

    // -- Show the DB Record --------------------- //
    
	if(isset($_GET['action']) && $_GET['action'] == "Query"){



		$consulta = "SELECT * FROM myng_config WHERE conf_id = '".$_GET['conf_id']."'";
		//echo $consulta;
    	$db->query($consulta);
		$db->next_record();
		
		// Input Vars
		$t->set_var("conf_name",$db->Record['conf_name']);				
		$t->set_var("conf_description",$db->Record['conf_description']);
		if($db->Record['conf_active_yn'] == "Y"){		
			$t->set_var("conf_active_yes","checked");
		}else{		
			$t->set_var("conf_active_no","checked");
		}
		// -- System
		$t->set_var("conf_system_prefix",$db->Record['conf_system_prefix']);
		$t->set_var("conf_system_root",$db->Record['conf_system_root']);
		//$t->set_var("conf_system_language",$db->Record['conf_system_language']);
		// -- Download
		$t->set_var("conf_down_days",$db->Record['conf_down_days']);
		$t->set_var("conf_down_list_items",$db->Record['conf_down_list_items']);
		$t->set_var("conf_down_num_groups",$db->Record['conf_down_num_groups']);
		$t->set_var("conf_down_num_articles",$db->Record['conf_down_num_articles']);
		
		// -- Clean up
		$t->set_var("conf_clean_MAX_articles",$db->Record['conf_clean_MAX_articles']);				
		$t->set_var("conf_clean_MAX_days",$db->Record['conf_clean_MAX_days']);			
		
		// -- Visualization
		//$t->set_var("conf_vis_theme",$db->Record['conf_vis_theme']);
		$t->set_var("conf_vis_num_2_flames",$db->Record['conf_vis_num_2_flames']);
		$t->set_var("conf_vis_articles_x_page",$db->Record['conf_vis_articles_x_page']);
		$t->set_var("conf_vis_nav_bar_items",$db->Record['conf_vis_nav_bar_items']);
		$t->set_var("conf_vis_nav_bar_pages",$db->Record['conf_vis_nav_bar_pages']);
		$t->set_var("conf_vis_time_highlight_new",$db->Record['conf_vis_time_highlight_new'] / 3600);
		// -- Security
		$t->set_var("conf_sec_secret_string",$db->Record['conf_sec_secret_string']);
		
		
		// Radio Buttons				
		if($db->Record['conf_system_debug_yn'] == "Y"){
			// Check the checkbox
			$t->set_var("conf_system_debug_yes","checked");
		}else{
			// Don't check it
			$t->set_var("conf_system_debug_no","checked");
		}
		
		
		if($db->Record['conf_clean_MAX_days'] != "0"){			
			$t->set_var("clean_days_yes","checked");
			// Show Apply link
			$t->parse("apply_days_block_handle","apply_days_block",true);
		}
		if($db->Record['conf_clean_MAX_articles'] != "0"){			
			$t->set_var("clean_articles_yes","checked");
			// Show Apply link
			$t->parse("apply_articles_block_handle","apply_articles_block",true);
		}
		if($db->Record['conf_clean_MAX_articles'] == "0" && $db->Record['conf_clean_MAX_days'] == "0"){			
			$t->set_var("clean_no","checked");			
			
		}
		
		
		if($db->Record['conf_system_login_yn'] == "Y"){			
			$t->set_var("conf_system_login_yes","checked");
		}else{			
			$t->set_var("conf_system_login_no","checked");
		}
		
		if($db->Record['conf_system_online_yn'] == "Y"){			
			$t->set_var("conf_system_online_yes","checked");
		}else{			
			$t->set_var("conf_system_online_no","checked");
		}
		
		if($db->Record['conf_sec_protect_email_yn'] == "Y"){			
			$t->set_var("conf_sec_protect_email_yes","checked");
		}else{			
			$t->set_var("conf_sec_protect_email_no","checked");
		}
		
		if($db->Record['conf_sec_send_poster_host_yn'] == "Y"){			
			$t->set_var("conf_sec_send_poster_host_yes","checked");
		}else{			
			$t->set_var("conf_sec_send_poster_host_no","checked");
		}
		
		if($db->Record['conf_sec_test_group_yn'] == "Y"){			
			$t->set_var("conf_sec_test_group_yes","checked");
		}else{			
			$t->set_var("conf_sec_test_group_no","checked");
		}
		
		if($db->Record['conf_sec_validate_email_yn'] == "Y"){		
			$t->set_var("conf_sec_validate_email_yes","checked");
		}else{		
			$t->set_var("conf_sec_validate_email_no","checked");
		}
		
		
		// Try to read the available Languages and Themes
		if ($handle = opendir("../../lang")) {
    		while (false !== ($file = readdir($handle))) { 
    			// Check if the directory name is '.','..','CVS' or if it's a directory.
        		if ($file != "." && $file != ".." && filetype("../../lang/".$file) == "dir" && $file != "CVS") { 
 	                $t->set_var("language",$file);
 	                // Check for the current language
 	                if($file == $db->Record['conf_system_language'] ){ 	                	 	               
 	                	$t->set_var("language_is_selected","selected");
 	                }else{ 
 	                	$t->set_var("language_is_selected",""); 	                
 	                }
                	$t->parse("language_block_handle","language_block",true);            	
        		} 
    		}
    		closedir($handle); 
		}


		// Try to read the available Languages and Themes
		if ($handle = opendir("../../themes")) {
    		while (false !== ($file = readdir($handle))) { 
    			// Check if the directory name is '.','..','CVS' or if it's a directory.
        		if ($file != "." && $file != ".." && filetype("../../themes/".$file) == "dir" && $file != "CVS") { 
 	                $t->set_var("theme",$file);
 	                // Check for the current language
 	                if($file == $db->Record['conf_vis_theme'] ){ 	                	 	               
 	                	$t->set_var("theme_is_selected","selected");
 	                }else{ 
 	                	$t->set_var("theme_is_selected",""); 	                
 	                }
                	$t->parse("theme_block_handle","theme_block",true);            		
        		} 
    		}
    		closedir($handle); 
		}


		$t->set_var("return_page",$_SERVER['REQUEST_URI']);		
		
        // Id
		$t->set_var("conf_id",$_GET['conf_id']);
		
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
	if(!isset($_GET['action']) || $_GET['action'] == ""){

		$main = "detail.htm";

		// Try to read the available Languages and Themes
		if ($handle = opendir($_SESSION['conf_system_root']."lang")) {
    		while (false !== ($file = readdir($handle))) { 
    			// Check if the directory name is '.','..','CVS' or if it's a directory.
        		if ($file != "." && $file != ".." && filetype($_SESSION['conf_system_root']."lang/".$file) == "dir" && $file != "CVS") { 
 	                $t->set_var("language",$file);
 	               	$t->set_var("language_is_selected",""); 	                 	  
                	$t->parse("language_block_handle","language_block",true);            	
        		} 
    		}
    		closedir($handle); 
		}
		
		// Try to read the available Languages and Themes
		if ($handle = opendir($_SESSION['conf_system_root']."themes")) {
    		while (false !== ($file = readdir($handle))) { 
    			// Check if the directory name is '.','..','CVS' or if it's a directory.
        		if ($file != "." && $file != ".." && filetype($_SESSION['conf_system_root']."themes/".$file) == "dir" && $file != "CVS") { 
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
				

    // ---- Show the HTML ---- //
    show_iface($main);
    // ----------------------- //


	}
    

}

?>








