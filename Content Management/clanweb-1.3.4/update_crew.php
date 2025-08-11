<?php
//	-----------------------------------------
// 	$File: update_crew.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-01-03
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------

       	require ('auth.php');
		require ('_inc/top.inc.php');
		
		echo"<div class=\"welcome\">";
				  
				$name 		= $_POST['name'];
				$nick 		= $_POST['nickname'];
				$picture 	= $_POST['picture'];
				$age 		= $_POST['age'];
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
				
					$sql = "UPDATE " .$db_prefix. "members 
									SET name = '$name', 
											nickname = '$nick', 
											picture = '$picture', 
											age = '$age', 
											position = '$position', 
											work = '$work', 
											resolution = '$resolution', 
											sex = '$sex', 
											quote = '$quote', 
											location='$location', 
											cpu='$cpu', 
											mouse='$mouse', 
											gfx='$gfx', 
											mousepad='$mousepad', 
											memory='$memory', 
											os='$os', 
											hdd='$hdd', 
											mail='$mail', 
											screen='$screen' 
									WHERE id = '".$_GET['id']."'";
									
					$db->query($sql) or exit('An error occured while saving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
	
					echo"<h3>$lang_member_updated</h3>";
					echo"<br/><br/>";
    	    		echo"</div>";
					
			require ('_inc/bottom.inc.php');

 ?>
