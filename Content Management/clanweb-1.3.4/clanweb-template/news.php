<?php
				require ('cfg.php');
				// Get info
				$result=mysql_query("select * from " .$db_prefix. "news where id = '".$_GET['id']."' order by id DESC");
				while ($read=mysql_fetch_array($result)) 
				{
					$id = $read["id"];
					$topic=$read["topic"];
					$date=$read["dates"];
					$newspost=$read["newspost"];
					$nick=$read["nickname"];
				
					// Print info
					echo"<h2>$topic</h2>";
					echo"<u>By $nick $date</u> <br/>\n";
					echo"\t $newspost<br/>\n";
					echo"\t <br/><br/><a href=\"JavaScript:history.go(-1);\">Back</a> \n";
				}

?>