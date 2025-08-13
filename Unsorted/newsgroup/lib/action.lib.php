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
// action.lib.php
// Author: Carlos Sánchez
// Created: 30/11/02
//
// Description: Asynchronous actions library
//
//
//------------------------------------------------------------------//
?>
<?

//--------------------------------------------------//
// Name:        mark_all_read
// Task:        Mark al the articles of one group as read
// Description: Update the info in myng_library to show 
//				all articles as read
//
// In:          
// Out:         $result
//
//--------------------------------------------------//
function mark_all_read(){
	
	global $db, $db2;
	
	// Get the newsgroup from the session	
	$grp_name = $_SESSION['grp_name'];
	// Get the user from the session
	$usr_id = $_SESSION['usr_id'];
		
	// Get all the id's of this group's articles
	$query = "SELECT id_article 
				FROM `myng_".real2table($grp_name)."`			  
	    	  WHERE newsgroup LIKE '" . strtr($grp_name, ".", "_") ."'";
	
	$db->query($query);
	
	while($db->next_record()){
		//echo $db->Record['id_article'];
		$query_2 = "INSERT IGNORE INTO `myng_library` (
						lib_art_id,
						lib_grp_id,
						lib_usr_id,
						lib_my_article
					)VALUES(
						'".$db->Record['id_article']."',
						'".$_SESSION['grp_id']."',
						'".$usr_id."',
						'0'
					)";
		
		//echo $query_2;
    	$db2->query($query_2);
		
	}	
	
	// All went really well??
	$result = 1;
	
	return $result;
	
}

//--------------------------------------------------//
// Name:        mark_thread_read
// Task:        Mark all the articles of one thread as read
// Description: Update the info in  myng_library to show
//				thread articles as read
//
// In:          
// Out:         $result
//
//--------------------------------------------------//
function mark_thread_read($art_id){
	
	global $db;
			
	// Get the newsgroup from the session	
	$grp_name = $_SESSION['grp_name'];
	// Get the user from the session
	$usr_id = $_SESSION['usr_id'];
	
	$thread_ids = array();	
	get_thread_ids($grp_name,$art_id,&$thread_ids);

	foreach ($thread_ids as $j){
			
		$query = "SELECT id_article FROM `myng_".real2table($grp_name)."` 
				WHERE id='".$j."'";				
    	$db->query($query);
    	
		if($db->next_record()){
			
					$query = "INSERT IGNORE INTO `myng_library` (
						lib_art_id,
						lib_grp_id,
						lib_usr_id,
						lib_my_article
					)VALUES(
						'".$db->Record['id_article']."',
						'".$_SESSION['grp_id']."',
						'".$usr_id."',
						'0'
					)";
									
		    	$db->query($query);	
		}
							
	}	
	
	// All went really well??
	$result = 1;
	
	return $result;
	
}



?>