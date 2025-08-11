<?php
		require ("cfg.php");

			$sql = "SELECT * 
							FROM " .$db_prefix. "news 
							ORDER BY id DESC";
					
					$sql = mysql_query($sql) or exit('An error occured while retreiving data.<br/><strong>Line:</strong>'. __LINE__ .'<br/><strong>File:</strong>'. __FILE__ .'<br/><strong>Cause:</strong>'.mysql_error().'');
						while ($r=mysql_fetch_array($sql))
						{
						  $id = $r["id"];
						  $nick = $r["nickname"];
						  $topic = $r["topic"];
						  $newstype = $r["newstype"];
						  $date = $r["dates"];

			$newspost=$r["newspost"];
			$newspost=substr($newspost , 0, 200);
	
	echo"<u>$topic by $nick $date</u> <br/>\n";
	echo"\t ".$newspost."<br/><a href=\"?p=news&id=$id\" class=\"meny\">Read more &#155;&#155;</a>\n";
	echo"\t <br/><br/> \n";
	}
	?>