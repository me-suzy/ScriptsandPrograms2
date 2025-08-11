<?php
/********************************************************
								news.php
								--------
					$Copyright: (c) ClanAdmin Tools 2003-2005
					$Last modified: 2005-02-21
					$email: support@clanadmintools.com

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/
    	require ('auth.php');
    	require ('_inc/top.inc.php');

		// Content
        echo"<div class=\"welcome\"><a href=\"add_news.php\">$lang_add_news</a><br/>";
					
					$sql = "SELECT id, topic, newstype, dates 
							FROM " .$db_prefix. "news 
							ORDER BY id DESC";
					
					$sql = $db->query($sql) or exit('An error occured while retreiving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
					 echo"<table class=\"text\" style=\"padding: 3px; \">
					    			<tr style=\"margin: 3px;\">
					    				<td></td>
					    				<td></td>
					    				<td><strong>Date & time</strong></td>
					    				<td><strong>Topic</strong></td>
					    				<td><strong>Newstype</strong></td>
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
						  
						  echo"\"><td><a href=\"edit_news.php?id=".$r['id']."\"><img src=gfx/edit.gif border=0 title=\"$lang_edit_news\"></a></td> 
							<td><a onClick=\"if(confirm('$lang_delete_news ?')) location='del_news.php?id=".$r['id']."'\" href=\"#\"><img src=gfx/delete.gif border=0 title=\"$lang_delete_news\"></a></td> <td>".$r['dates']."</td> <td>".$r['topic']."</td> <td>".$r['newstype']."</td></tr>\n ";
            			}
					echo"</table>";
					echo"</div>";
					// bottom.inc
					$db->close(); 
			require ('_inc/bottom.inc.php');
	
?>
