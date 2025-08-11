<?php
/********************************************************
								save_edit_news.php
								------------------
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
      
	    	  echo"<div class=\"welcome\">";

              	$nickname 		= $_POST['nickname'];
              	$topic 		= $_POST['topic'];
              	$newspost 	= htmlentities($_POST['newspost']);
              	$newstype 	= $_POST['newstype'];
              	
				$sql = "UPDATE " .$db_prefix. "news 
						SET name='".$_POST['nickname']."', 
						topic='$topic', 
						newspost='$newspost', 
						newstype='$newstype' 
						WHERE id = '".$_GET['id']."'";
				
				$db->query($sql) or exit('An error occured while saving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');

				echo"<h3>$lang_news_updated</h3>";
				echo"</div>";
 
	require ('_inc/bottom.inc.php');
	
?>
