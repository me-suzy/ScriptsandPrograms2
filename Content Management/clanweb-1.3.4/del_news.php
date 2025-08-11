<?php
/********************************************************
								del_news.php
								-----------
					$Copyright: (c) ClanAdmin Tools 2003-2005
					$Last modified: 2005-01-03
					$email: support@clanadmintools.com

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/
      require ('auth.php');
	  require ('_inc/top.inc.php');

	  // Content 
	  echo"<div class=\"welcome\">";

				$sql = "DELETE 
						FROM ".$db_prefix. "news 
						WHERE id = '".$_GET['id']."'";
				
				$db->query($sql) or exit('An error occured while deleting data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
			
			echo "<h3>$lang_news_deleted</h3>";
			echo"</div>";
	  // bottom.inc
	  require ('_inc/bottom.inc.php');

?>				  
				  
