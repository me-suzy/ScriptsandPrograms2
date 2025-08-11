<?php

				require ("cfg.php");

				$result=mysql_query("select * from ".$db_prefix."game where id = '".$_GET['id']."' order by id DESC LIMIT 1");
				while ($read=mysql_fetch_array($result)) 
				{
					$id = $read["id"];
					$dates=$read["dates"];
					$team1=$read["team1"];
					$team2=$read["team2"];
					$point1=$read["point1"];
					$point2=$read["point2"];
					$type=$read["type"];
					$map=$read["map"];
					$lineup=$read["lineup"];
					$report=$read["report"];
				

					echo"<h2>$team1 vs $team2</h2>";
					echo"<strong>Date:</strong> $dates<br/>";
					echo"<strong>Result:</strong> $point1 - $point2 <br/>\n";
					echo"<strong>Map:</strong> $map<br/>";
					echo"<strong>Game type:</strong> $type <br>\n";
					echo"<strong>Line up:</strong> $lineup <br>\n";
					echo"<strong>Game report:</strong>\t $report<br><a href=\"JavaScript:history.go(-1);\">Back</a>\n";
					echo"\t <br/><br/> \n";
				}

?>