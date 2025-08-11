<?php
/********************************************************
								del_comment.php
								-----------
					$Copyright: (c) ClanAdmin Tools 2003, 2004
					$Last modified: 2004-12-14 by ArreliuS
					$email: support@clanadmintools.com

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/
      require ('auth.php');
		
				require ('_inc/top.inc.php');

				$sql = "UPDATE " .$db_prefix. "comments 
						SET names='$lang_deleted', email='$lang_deleted', comment='$lang_comment_deleted' 
						WHERE id = '".$_GET['id']."'";
				$db->query($sql) or exit('An error occured while saving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
			  
				echo"<div class=\"welcome\"><h3>$lang_comment_removed</h3></div>";
        	
          	  	$sql = "DELETE 
						FROM " .$db_prefix. "reported 
						WHERE rid = '".$_GET['id']."'";
					$db->query($sql) or exit('An error occured while deleting data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');


     	 	require ('_inc/bottom.inc.php');


 ?>
