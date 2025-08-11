<?php
/********************************************************
								save_news.php
								-------------
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
	
					$name 		= $_POST['name'];
					$topic 		= $_POST['topic'];
					$newspost 	= $_POST['newspost'];
					$newstype 	= $_POST['newstype'];
					
					$dates = date("y-m-d H:i");
					$sql = "INSERT INTO `" .$db_prefix. "news` (`dates`, `name`, `topic`, `newspost`, `newstype`) 
						   	VALUES ('$dates', '$name', '$topic', '$newspost', '$newstype')";
						   	
					$db->query($sql) or exit('An error occured while saving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.$db->error().'');
       
			 	 	echo"<div class=\"welcome\"><h3>$lang_news_added</h3></div>";	
					
		require ('_inc/bottom.inc.php');
	
 ?>
