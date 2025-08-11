<?php
/********************************************************
								comments.php
								-----------
					$Copyright: (c) ClanAdmin Tools 2003-2005
					$Last modified: 2005-02-21
					$email: support@clanadmintools.com

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/
      require ('auth.php');
    	

					// top.inc
					require ('_inc/top.inc.php');

					// Content
        			echo"<div class=\"welcome\">";
					$sql='SELECT rid, names, comment, date, ip 
						  FROM ' .$db_prefix. 'reported 
						  ORDER BY id DESC';
								
					$sql = $db->query($sql) or exit('An error occured while retrieving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.mysql_error().'');
					    echo"<table class=\"text\" style=\"padding: 3px; \">
					    			<tr style=\"margin: 3px;\">
					    				<td></td>
					    				<td></td>
					    				<td><strong>Report date & time</strong></td>
					    				<td><strong>Nickname</strong></td>
					    				<td><strong>Comment</strong></td>
					    				<td><strong>IP</strong></td>
					    			</tr>
					    			";
						while ($r=$db->fetch_array($sql))
						{

              			  echo"<tr id=\"";

						  if ( $k++ % 2 == 0 ) 
						  {
           				   	 echo "post_one";
     					  } 
						   else 
						  {
           				   echo "post_two";
						  }
						  
						  echo"\"><td><a href=\"approve.php?id=".$r['rid']."\"><img src=gfx/approve.gif border=0 title=\"$lang_approve\"></a></td><td><a onClick=\"if(confirm('$lang_delete_comment ?')) location='del_comment.php?id=".$r['rid']."'\" href=\"#\"><img src=gfx/delete.gif border=0 title=\"$lang_delete\"></a></td> <td>".$r['date']."</td> <td>".$r['names']."</td> <td>".$r['comment']."</td> <td>".$r['ip']."</td></tr>\n";
              			  
            			}
            			if($db->num_rows($sql) == 0)
            				  echo"<tr><td colspan=6><h3>No reported comments</h3></td></tr>";
            		 echo"</table>";		  
					 echo"</div>";
					 // bottom.inc
					 require ('_inc/bottom.inc.php');

?>
