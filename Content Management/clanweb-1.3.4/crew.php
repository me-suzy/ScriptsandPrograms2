<?php
//	-----------------------------------------
// 	$File: crew.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-02-23
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------

	  require ('auth.php');
	  require ('_inc/top.inc.php');

				// Content
      	echo"<div class=\"welcome\">

            <a href=\"add_crew.php\">$lang_add_member</a><br />";
            
				$sql = 'SELECT id, nickname, position 
						FROM '.$db_prefix.'members 
						ORDER BY id DESC';
						
				$sql = $db->query($sql) or exit('An error occured while retrieving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
				echo"<table class=\"text\" style=\"padding: 3px; \">
					    			<tr style=\"margin: 3px;\">
					    				<td></td>
					    				<td></td>
					    				<td><strong>Nickname</strong></td>
					    				<td><strong>Position</strong></td>
					    			</tr>
					    			";
				while ($read=$db->fetch_array($sql)) 
				{
				  $id 		= $read['id'];
				  $nick 	= $read['nickname'];
				  $position = $read['position'];

          		  echo"<tr id=\"";

						  if ( $k++ % 2 == 0 ) 
						  {
           				   	 echo "post_one";
     					  } 
						   else 
						  {
           				   echo "post_two";
						  }
						  
						  echo"\"><td><a href=\"edit_member.php?id=$id\"><img src=gfx/edit.gif border=0 title=\"$lang_edit_member\"></a></td> 
					<td><a onClick=\"if(confirm('$lang_delete_member ?')) location='del_member.php?id=$id'\" href=\"#\"><img src=gfx/delete.gif border=0 title=\"$lang_delete_member\"></a></td><td>$nick</td><td>$position</td></tr>\n";
        		}
      	echo"</table>";
      	echo"</div>";
	
	// bottom.inc
	require ('_inc/bottom.inc.php');

?>
