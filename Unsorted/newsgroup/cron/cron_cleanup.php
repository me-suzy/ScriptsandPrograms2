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
// File: cron_cleanup.php
//
// Created: 17/01/2003
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

$db2=new My_db;
$db2->connect();

// MyNG setting up...
init();

// Set up the language
modules_get_language();

// Templates
$t = new Template($_SESSION['conf_system_root']."/themes/".$_SESSION['conf_vis_theme']."/templates/");


// Check if clean up ALL the groups or just the 
// configured ones
if($_SESSION['conf_clean_MAX_days'] == 0 && $_SESSION['conf_clean_MAX_articles'] == 0){
	
	
	$query = "SELECT grp_name,grp_id,grp_num_messages,grp_MAX_articles,grp_MAX_days FROM myng_newsgroup WHERE grp_MAX_articles != 0 OR grp_MAX_days != 0";
	$db->query($query);
			

	// For each newsgroup
	while($db->next_record()){
					
		// Articles!
		if($db->Record['grp_MAX_articles'] != 0){
									
			// Delete (M - N)articles     	        
            // M = articles at the group
            // N = MAX Articles
        	if($db->Record['grp_num_messages'] > $db->Record['grp_MAX_articles']){
        		$num_2_delete = ($db->Record['grp_num_messages'] - $db->Record['grp_MAX_articles']);   
        	}else{
        		$num_2_delete = 0;
        	}        

        	//echo $num_2_delete."<br>";             				
            if($num_2_delete != 0){            	
        		del_articles($num_2_delete,$db->Record['grp_name']);
            }
			
		}
		// Days!
		if($db->Record['grp_MAX_days'] != 0){
												
			// Get current timestamp
			$now = time();
			$num_days = $db->Record['grp_MAX_days'];

			// Get the limit timestamp
			$limit = $now - ($num_days * 24 * 60 * 60);
		
	        $group_name = real2table($db->Record['grp_name']);
        	$query_2 = "SELECT count(*) FROM `"."myng_".$group_name."` WHERE date < ".$limit;
        	//echo $query_2;
        	$db2->query($query_2);
        	$db2->next_record();
                	                 
            $num_2_delete = $db2->Record[0];                        	                       
            
            if($num_2_delete != 0){
        		del_articles($num_2_delete,$db->Record['grp_name']);
            }
					
		}
		
	}
	
}else{
		
	// All the groups !
	// Check if we depend on number of days or just articles
	
	// Days!
	if($_SESSION['conf_clean_MAX_days'] != 0){
					
		// Get current timestamp
		$now = time();
		$num_days = $_SESSION['conf_clean_MAX_days'];

		// Get the limit timestamp
		$limit = $now - ($num_days * 24 * 60 * 60);

		// Get all the groups
		
		$query = "SELECT grp_name,grp_id FROM myng_newsgroup";
		$db->query($query);			

		// For each newsgroup
		while($db->next_record()){

	        $group_name = real2table($db->Record['grp_name']);
        	$query_2 = "SELECT count(*) FROM `"."myng_".$group_name."` WHERE date < ".$limit;
        	//echo $query_2;
        	$db2->query($query_2);
        	$db2->next_record();
                	                
            $num_2_delete = $db2->Record[0];                        	
            
            if($num_2_delete != 0){
        		del_articles($num_2_delete,$db->Record['grp_name']);
            }

		}		
		
	}
	
	// Articles!
	if($_SESSION['conf_clean_MAX_articles'] != 0){
							
		// Get all the groups
		
		$query = "SELECT grp_name,grp_id,grp_num_messages FROM myng_newsgroup";
		$db->query($query);
			
		// For each newsgroup
		while($db->next_record()){

            // Delete (M - N)articles     	        
            // M = articles at the group
            // N = MAX Articles
        	if($db->Record['grp_num_messages'] > $_SESSION['conf_clean_MAX_articles']){
        		$num_2_delete = ($db->Record['grp_num_messages'] - $_SESSION['conf_clean_MAX_articles']);   
        	}else{
        		$num_2_delete = 0;
        	}        
                                 				
            if($num_2_delete != 0){
        		del_articles($num_2_delete,$db->Record['grp_name']);
            }

		}
		
	}
		
}


// Check if the cleanup has been FORCED

if(isset($_POST['apply_cleanup']) && $_POST['apply_cleanup'] == "yes"){
	// We've done up to now the clean up, just return to the 
	// refering page	
	header ("Location: ".$_POST['return_page']);
	//echo $_POST['return_page'];
}

?>

