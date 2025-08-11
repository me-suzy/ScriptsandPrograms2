<?php
/********************************************************
								save_edit_match.php
								-------------------
					$Copyright: (c) ClanAdmin Tools 2003 - 2005
					$Last modified: 2005-01-03
					$email: support@clanadmintools.com

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/
 	require ('auth.php');
    require ('_inc/top.inc.php');

	     		echo"<div class=\"welcome\">";

              	$team1 	= $_POST['team1'];
				$team2 	= $_POST['team2'];
				$point1 = $_POST['point1'];
				$point2 = $_POST['point2'];
				$type 	= $_POST['type'];
				$map 	= $_POST['map'];
				$lineup = $_POST['lineup'];
				$report = $_POST['report'];	
				
					$sql = "UPDATE " .$db_prefix. "game 
							SET team1 = '$team1', 
							team2 = '$team2', 
							type = '$type', 
							point1 = '$point1', 
							point2 = '$point2', 
							report = '$report', 
							lineup = '$lineup', 
							map = '$map' 
							WHERE id = '".$_GET['id']."'";
									
					$db->query($sql) or exit('An error occured while saving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');

					echo"<h3>$lang_match_updated</h3>"; 
					echo"</div>";

		require ('_inc/bottom.inc.php');
	
 ?>
