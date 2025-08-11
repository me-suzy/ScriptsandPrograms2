<?php
//	-----------------------------------------
// 	$File: edit_admin.php
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
							if(isset($_GET['update'])) 
							{
							    $password = md5($_POST['password']);
								$sql = "UPDATE " .$db_prefix. "users 
										SET username='".$_POST['username']."', password ='$password' 
										WHERE id = '".$_GET['id']."'";
								
								$db->query($sql) or exit('An error occured while saving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
								
								echo "<h3>$lang_user_updated</h3>"; 						
							}
							else
							{
								$sql="SELECT id, username, password 
									  FROM " .$db_prefix. "users 
									  WHERE id = '".$_GET['id']."' LIMIT 1";
								
								$sql = $db->query($sql) or exit('An error occured while retrieving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
								
								while ($read=$db->fetch_array($sql))
								{
									$id = $read['id'];
									$username = $read['username'];
									$password = $read['password'];
							
              						echo"<form name=\"sends\" method=\"post\" action=\"edit_admin.php?update&amp;id=$id\">\n";
        							echo"<b>$lang_username:\n</b>";
									echo"<input class=\"textfelt\" type=\"text\" name=\"username\" size=\"20\" maxlength=\"75\" value=\"$username\" />\n";
									echo"<b>$lang_password:</b>\n";
             						echo"<input class=\"textfelt\" type=\"password\" name=\"password\" size=\"20\" maxlength=\"75\ value=\"$password\" />\n";
									echo"<input class=\"button\" type=\"submit\" name=\"sends\" value=\"$lang_edit_admin\">\n";
      								echo"</form>";
								}
							}
                    }
					else
					{ 
						echo "$lang_denied"; 
					} 
              	echo"</div>";
	// bottom.inc
	require ('_inc/bottom.inc.php');

?>
