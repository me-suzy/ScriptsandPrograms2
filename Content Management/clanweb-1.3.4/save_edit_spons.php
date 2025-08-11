<?php
/********************************************************
								save_edit_spons.php
								------------------
					$Copyright: (c) ClanAdmin Tools 2003-2005
					$Last modified: 2005-01-27
					$email: support@clanadmintools.com

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/
      require ('auth.php');		
      require ('_inc/top.inc.php');
      
	    	  echo"<div class=\"welcome\">";
              	
				$sql = "UPDATE ".$db_prefix."spons SET spons_name = '".$_POST['spons_name']."',
					   spons_info = '".addslashes($_POST['spons_info'])."' WHERE id = '".$_GET['id']."' LIMIT 1";
				
				$db->query($sql) or exit('An error occured while saving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');

				echo"<h3>Sponsor updated</h3>";
				echo"</div>";
 
	require ('_inc/bottom.inc.php');
	
?>
