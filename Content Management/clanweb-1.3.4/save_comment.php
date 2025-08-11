<?php
/********************************************************
								save_comment.php
								----------------
					$Copyright: (c) ClanAdmin Tools 2003, 2004
					$Last modified: 2004-12-14 by ArreliuS
					$email: support@clanadmintools.com

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/
      require ('cfg.php');

				$date = date("y-m-d H:i");
      			$ip = $_SERVER['REMOTE_ADDR'];
      			
				$sql = "INSERT INTO " .$db_prefix. "comments (pid, names, email, comment, date, ip) 
					   	VALUES ('".$_POST['pid']."', '".$_POST['names']."', '".$_POST['email']."', '".$_POST['comment']."', '$date', '$ip')";
				
				mysql_query($sql) or exit('An error occured while saving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.mysql_error().'');
			  
			  header("Location: ".$_SERVER['HTTP_REFERER']."");
			  echo"<div class=\"welcome\"><h3>$lang_comment_added</h3></div>";




 ?>
