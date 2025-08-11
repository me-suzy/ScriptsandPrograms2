<?php
//	-----------------------------------------
// 	$File: match.php
// 	-----------------------------------------
// 	$Copyright: (c) ClanAdmin Tools 2003-2005
// 	$Last modified: 2005-02-23
// 	$email: support@clanadmintools.com
// 	$License: GPL - See LICENSE for more info
//	-----------------------------------------

	  require ('auth.php');    
	  require ('_inc/top.inc.php');

			// Content
       		echo"<div class=\"welcome\"><a href=\"add_match.php\">$lang_add_match</a><br />";
				$sql = 'SELECT id, team1, team2, type 
						FROM ' .$db_prefix. 'game 
						ORDER BY id DESC';
				
				 $sql = $db->query($sql) or exit('An error occured while retreiving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
				echo"<table class=\"text\" style=\"padding: 3px; \">
					    			<tr style=\"margin: 3px;\">
					    				<td></td>
					    				<td></td>
					    				<td><strong>Teams</strong></td>
					    				<td><strong>Game type</strong></td>
					    			</tr>
					    			";
				while ($read=$db->fetch_array($sql)) 
				{
					$id = $read["id"];
					$team1 = $read["team1"];
					$team2 = $read["team2"];
					$type = $read["type"];

					echo"<tr id=\"";

						  if ( $k++ % 2 == 0 ) 
						  {
           				   	 echo "post_one";
     					  } 
						   else 
						  {
           				   echo "post_two";
						  }
						  
						  echo"\"><td><a href=\"edit_match.php?id=$id\"><img src=gfx/edit.gif border=0 title=\"$lang_edit_match\"></a></td> 
					<td><a onClick=\"if(confirm('$lang_delete_match ?')) location='del_match.php?id=$id'\" href=\"#\"><img src=gfx/delete.gif border=0 title=\"$lang_delete_match\"></a></td><td>$team1 vs. $team2</td><td>$type</td></tr>\n ";
				}
				echo"</table>";
				echo"</div>";
				// bottom.inc
		require ('_inc/bottom.inc.php');

?>
