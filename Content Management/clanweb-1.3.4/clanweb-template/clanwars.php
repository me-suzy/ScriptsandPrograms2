<h2>Clan Wars</h2>
<?php
		require('cfg.php');
			$result=mysql_query("select * from ".$db_prefix."game order by id DESC");
			while ($read=mysql_fetch_array($result)) 
			{
				$id = $read["id"];
				$dates=$read["dates"];
				$team1=$read["team1"];
				$team2=$read["team2"];
				$point1=$read["point1"];
				$point2=$read["point2"];
				
            	echo "<a href=\"?p=match&amp;id=$id\">$team1 vs. $team2</a> ";
            	if($point1 > $point2)
            	{ 
            	  echo "<span style=\"color:green\">$point1-$point2</span>"; 
            	}
            	elseif($point1 < $point2)
            	{ 
            	  echo "<span style=\"color:red\">$point1-$point2</span>"; 
            	}
            	elseif($point1 == $point2)
            	{ 
            	  echo "<span style=\"color:#FFA902\">$point1-$point2</span>"; 
            	} 
            	echo"<br/>";
			}
?> 