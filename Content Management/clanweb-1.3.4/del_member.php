<?php
//	-----------------------------------------
// 	$File: del_members.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-02-23
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------

	    require ('auth.php');
		require ('_inc/top.inc.php');

		// Content
		echo"<div class=\"welcome\">";
          		
				  $sql = "DELETE 
						  FROM ".$db_prefix. "members
						  WHERE id = '".$_GET['id']."' ";
					
					$db->query($sql) or exit('An error occured while deleting data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
		
					echo "<h3>$lang_member_deleted</h3>";
	        		echo"</div>";
		// bottom.inc
  		require ('_inc/bottom.inc.php');

 ?>
