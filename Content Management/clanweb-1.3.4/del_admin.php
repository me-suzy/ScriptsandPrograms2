<?php
//	-----------------------------------------
// 	$File: del_admin.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-05-29
// 	$email: info@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------

      require ('auth.php');
	  require ('_inc/top.inc.php');

		   	// Content
		   echo"<div class=\"welcome\">";
								$sql = $db->query("SELECT user_id FROM " .$db_prefix. "online 
						  WHERE cookiesum = '".$_COOKIE['catcookie']."' LIMIT 1");
	  $read = $db->fetch_array($sql);
						  
      $sql = $db->query("SELECT admin FROM " .$db_prefix. "users 
						  WHERE id = '".$read['user_id']."' LIMIT 1");
	  $read = $db->fetch_array($sql);
 				if($read['admin'] == 1)
				{

					$sql = "DELETE 
							FROM " .$db_prefix. "users 
							WHERE id = '".$_GET['id']."' LIMIT 1";
					$db->query($sql) or exit('An error occured while deleting data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
					echo "<h3>$lang_user_deleted</h3>";
					echo"<br/><br/>";
				}
				else
				{
					echo"$lang_denied";
				}
				echo"</div>";
				// bottom.inc
				$db->close(); 
				require ('_inc/bottom.inc.php');


?>				
				
