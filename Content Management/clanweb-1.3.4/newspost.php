<?php require ('cfg.php'); 
// top.inc
   			require ('_inc/top_1.inc.php');

  			// Content
				$result=mysql_query("SELECT * FROM " .$db_prefix. "news ORDER BY id DESC")or die(mysql_error());
				while ($read=mysql_fetch_array($result)) 
				{
					$id = $read["id"];
					$topic = $read["topic"];
					$newstype = $read["newstype"];
					$newspost = $read["newspost"];
					include("replace.php");
				}
					echo"$topic - $newstype \n"; 
          echo"<br>";
					echo"$newspost";
							  
			// bottom.inc 
			mysql_close(); require ('_inc/bottom.inc.php');
?>
