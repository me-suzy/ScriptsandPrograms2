<?php
//	-----------------------------------------
// 	$File: admins.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-05-29
// 	$email: info@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------

	     require ('auth.php');
			
			// top.inc
			require ('_inc/top.inc.php');

			// Content
			   			echo"<div class=\"welcome\"";
	  $sql = $db->query("SELECT user_id FROM " .$db_prefix. "online 
						  WHERE cookiesum = '".$_COOKIE['catcookie']."' LIMIT 1");
	  $read = $db->fetch_array($sql);
						  
      $sql = $db->query("SELECT admin FROM " .$db_prefix. "users 
						  WHERE id = '".$read['user_id']."' LIMIT 1");
	  $read = $db->fetch_array($sql);
			
 				
				if($read['admin'] == 1)
				{
				    echo"<a href=add_admin.php>$lang_add_admin</a><br/><br/>";
					$sql='SELECT id, username 
						  FROM '.$db_prefix.'users 
						  ORDER BY id DESC';
					
					$sql = $db->query($sql) or exit('An error occured while retrieving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.mysql_error().'');									
					echo"<table class=\"text\" style=\"padding: 3px; \">
					    			<tr style=\"margin: 3px;\">
					    				<td></td>
					    				<td></td>
					    				<td><strong>Admin name</strong></td>
					    			</tr>
					    			";
					while ($read=$db->fetch_array($sql))
					{
						$id = $read['id'];
						$username = $read['username'];

					  echo"<tr id=\"";

						  if ( $k++ % 2 == 0 ) 
						  {
           				   	 echo "post_one";
     					  } 
						   else 
						  {
           				   echo "post_two";
						  }
						  
						  echo"\"><td><a href=\"edit_admin.php?id=$id\"><img src=gfx/edit.gif border=0 title=\"$lang_edit_admin\"></a></td> <td><a onClick=\"if(confirm('$lang_delete_admin ?')) location='del_admin.php?id=$id'\" href=\"#\"><img src=gfx/delete.gif border=0 title=\"$lang_delete_admin\"></a></td> <td>$username</td></tr>\n ";
					}
					echo"</table>";
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
