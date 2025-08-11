<?php
//	-----------------------------------------
// 	$File: sponsor.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-02-23
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------

    	require ('auth.php');
    	require ('_inc/top.inc.php');

		// Content
        echo"<div class=\"welcome\"><a href=\"add_spons_cat.php\">Add sponsor category</a><br/>";
					
					$sql = 'SELECT * 
							FROM ' .$db_prefix. 'spons_cat 
							ORDER BY spons_cat DESC';
					
					$sql = $db->query($sql) or exit('An error occured while retreiving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
					 
						while ($r=$db->fetch_array($sql))
						{
						  $spons_cat = $r["spons_cat"];
						  $spons_type = $r["spons_type"];
						  
						  echo"<h4><a href=\"edit_spons_cat.php?id=$spons_cat\"><img src=gfx/edit.gif border=0 title=\"..\"></a> <a onClick=\"if(confirm('Delete category? The sponsor category and all subcategories will be deleted. ')) location='del_spons_cat.php?id=$spons_cat'\" href=\"#\"><img src=gfx/delete.gif border=0 title=\"..\"></a> $spons_type</h4>\n";
						  $sq = "SELECT id,spons_cat, spons_name 
							  	FROM " .$db_prefix. "spons 
								WHERE spons_cat = '".$spons_cat."'
								ORDER BY id DESC";
					
						  $sq = $db->query($sq) or exit('An error occured while retreiving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
					 	  echo"<ul>\n";
					 	  while ($read=$db->fetch_array($sq))
						  {
    						  $id = $read["id"];
    						  $spons_cat = $read["spons_cat"];
    						  $spons_name = $read["spons_name"];
    						  
    						  echo"<a href=\"edit_spons.php?id=$id\"><img src=gfx/edit.gif border=0 title=\"..\"></a> <a onClick=\"if(confirm('Delete sponsor ?')) location='del_spons.php?id=$id'\" href=\"#\"><img src=gfx/delete.gif border=0 title=\"..\"></a> $spons_name <br/>\n";
						  }
						  echo"<a href=\"add_spons.php?scatID=$spons_cat\">Add sponsor</a>";
						  echo"</ul>\n";
              			  echo"<br />";
              			}
					echo"</div>";
					// bottom.inc
					$db->close(); 
			require ('_inc/bottom.inc.php');
	
?>
