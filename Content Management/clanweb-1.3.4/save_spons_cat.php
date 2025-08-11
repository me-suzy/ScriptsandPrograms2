<?php
/********************************************************
								save_spons_cat.php
								-------------
					$Copyright: (c) ClanAdmin Tools 2003-2005
					$Last modified: 2005-01-25
					$email: support@clanadmintools.com

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/
       	require ('auth.php');
		require ('_inc/top.inc.php');
	
					$spons_type	= $_POST['spons_type'];
					
					$sql = "INSERT INTO " .$db_prefix. "spons_cat (spons_type) 
						   	VALUES ('$spons_type')";
						   	
					$db->query($sql) or exit('An error occured while saving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
       
			 	 	echo"<div class=\"welcome\"><h3>Category added</h3></div>";	
					
		require ('_inc/bottom.inc.php');
	
 ?>
