<?php 
/********************************************************
								default.php
								-----------
					$Copyright: (c) ClanAdmin Tools 2003-2005
					$Last modified: 2005-01-22 by ArreliuS
					$email: support@clanadmintools.com

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/
	
  	require ('auth.php');
	require ('_inc/top.inc.php');
	
				echo"<div class=\"welcome\" id=\"post_one\">\n
              				<h3>ClanAdmin tools: Start</h3>\n";
        		require "$lang/main.php";
        		echo"</div>";
				echo"<br/><br/>";

				$sql='SELECT * 
					  FROM ' .$db_prefix. 'motd 
					  ORDER BY id DESC 
					  LIMIT 1';
				
				$sql = $db->query($sql) or exit('An error occured while retrieving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
				
					while ($read=$db->fetch_array($sql)) 
					{
						$id = $read['id'];
						$username = $read['username'];
						$motd = $read['motd'];
						$dates = $read['dates'];
					}
					
					echo"<div class=\"motd\" id=\"post_two\">\n";
					echo"<h3>$lang_motd_title</h3>\n";
					echo"$motd\n";
					echo"<h4><i>$username - $dates</i></h4>\n";
					echo"</div>";
					echo"<br/><br/>";
					
			
				// bottom.inc 
				require ('_inc/bottom.inc.php');
				$db->close(); 
				exit; 

?>
