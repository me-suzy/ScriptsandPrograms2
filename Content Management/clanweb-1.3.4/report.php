<?php
/********************************************************
								report.php
								-----------
					$Copyright: (c) ClanAdmin Tools 2003, 2004
					$Last modified: 2005-02-21 by ArreliuS
					$email: cat@error-404.se

	   ClanAdmin Tools is free software; you can redistribute it and/or modify
	   it under the terms of the GNU General Public License as published by
	   the Free Software Foundation; either version 2 of the License, or
	   (at your option) any later version.

**********************************************************/
      require ('cfg.php');
     	require ('_inc/top_1.inc.php');
     	
        $sql = "SELECT id, names, comment, ip 
				FROM " .$db_prefix. "comments 
				WHERE id='".$_GET['id']."' LIMIT 1";
        $sql = mysql_query($sql) or exit('An error occured while retreiving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.mysql_error().'');
        while ($r = mysql_fetch_array($sql))
        {
          $id = $r["id"];
          $names = $r["names"];
          $comment = $r["comment"];
          $ip = $r["ip"];
        }

				$date = date("y-m-d H:i");
				$sql = "INSERT INTO " .$db_prefix. "reported (rid, names, comment, date, ip) 
				VALUES ('$id', '$names', '$comment', '$date', '$ip')";
				mysql_query($sql) or exit('An error occured while saving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.mysql_error().'');
			  echo"<table cellspacing=\"2px\" cellpadding=\"0\" class=\"welcome\">\n<tr>
              <td><h3>Rapporterad</h3></td>\n</tr></table>";

			mysql_close();
      require ('_inc/bottom.inc.php');

 ?>
