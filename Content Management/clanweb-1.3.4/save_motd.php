<?php
/********************************************************
								save_motd.php
								-------------
					$Copyright: (c) ClanAdmin Tools 2003-2005
					$Last modified: 2005-01-22
					$email: support@clanadmintools.com

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/

       	require ('auth.php');
		require ('_inc/top.inc.php');
	
	        $motd = $_POST['motd'];
	        $username = $_COOKIE['catcookie'];
	        
					$dates = date("y-m-d H:i");
					$sql = "UPDATE " .$db_prefix. "motd 
							SET id = '1', 
							dates = '$dates', 
							username = '$username', 
							motd = '$motd'";
							
					$db->query($sql) or exit('An error occured while saving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
          		
				echo"<div class=\"welcome\">";
  				echo "<h3>$lang_motd_added</h3>";
  				echo "</div>";	
  				
				$db->close(); 
		require ('_inc/bottom.inc.php');
  
 ?>
