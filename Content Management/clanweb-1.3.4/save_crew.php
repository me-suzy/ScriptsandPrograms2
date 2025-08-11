<?php
//	-----------------------------------------
// 	$File: edit_crew.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-01-24
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------

      require ('auth.php');
	  require ('_inc/top.inc.php');
				
				$name 		= $_POST['name'];
				$nick 		= $_POST['nickname'];
				$picture 	= $_POST['picture'];
				$age		= $_POST['age'];
				$position 	= $_POST['position'];
				$work 		= $_POST['work'];
				$resolution = $_POST['resolution'];
				$sex 		= $_POST['sex'];
				$quote 		= $_POST['quote'];
				$location 	= $_POST['location'];
				$cpu 		= $_POST['cpu'];
				$mouse 		= $_POST['mouse'];
				$gfx 		= $_POST['gfx'];
				$mousepad 	= $_POST['mousepad'];
				$screen 	= $_POST['screen'];
				$memory 	= $_POST['memory'];
				$os 		= $_POST['os'];
				$mail 		= $_POST['mail'];
				$hdd 		= $_POST['hdd'];

				$sql = "INSERT INTO " .$db_prefix. "members (name, nickname, picture, age, position, work, resolution, sex, quote, location, cpu, mouse, gfx, mousepad, memory, os, hdd, mail, screen)
					   	VALUES ('$name', '$nick', '$picture', '$age', '$position', '$work', '$resolution', '$sex', '$quote', '$location', '$cpu', '$mouse', '$gfx', '$mousepad', '$memory', '$os', '$hdd', '$mail', '$screen')";
				
				$db->query($sql) or exit('An error occured while saving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
			  	
				echo"<div class=\"welcome\"><h3>$lang_member_added</h3></div>";

      	require ('_inc/bottom.inc.php');

?>
