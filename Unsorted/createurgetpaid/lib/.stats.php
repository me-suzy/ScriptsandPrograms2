<?

	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	// This script is copyrighted to CreateYourGetPaid©       \\
	// Duplication, selling, or transferring of this script   \\
	// is a violation of the copyright and purchase agreement.\\
	// Alteration of this script in any way voids any         \\
	// responsibility CreateYourGetPaid© has towards the      \\
	// functioning of the script. Altering the script in an   \\
	// attempt to unlock other functions of the program that  \\
	// have not been purchased is a violation of the          \\
	// purchase agreement and forbidden by CreateYourGetPaid© \\
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\

	class Statistics
	{
	
		function UpdateStats()
		{
			GLOBAL $db;
			
			$now		= explode("-", date("d-m-Y-H"));
			
			$nowHour	= $now[3];
			$nowYear	= $now[2];
			$nowMonth	= $now[1];
			$nowDate	= $now[0];
			
			$db->Query("SELECT year FROM stats_year WHERE year='$nowYear'");
			
			$jml		= $db->NumRows();
			
			if($jml <= 0)
			{
			    $db->Query("INSERT INTO stats_year VALUES ('$nowYear','0')");
			    
			    for($i=1; $i <= 12; $i++)
			    {
					$db->Query("INSERT INTO stats_month VALUES ('$nowYear','$i','0')");
					
					if($i == 1)
						$TotalDay = 31;
					
					if($i == 2)
					{
					    if(date("L") == true)
							$TotalDay = 29;
					    else
							$TotalDay = 28;
					}
					
					if($i == 3) $TotalDay = 31;
					if($i == 4) $TotalDay = 30;
					if($i == 5) $TotalDay = 31;
					if($i == 6) $TotalDay = 30;
					if($i == 7) $TotalDay = 31;
					if($i == 8) $TotalDay = 31;
					if($i == 9) $TotalDay = 30;
					if($i == 10) $TotalDay = 31;
					if($i == 11) $TotalDay = 30;
					if($i == 12) $TotalDay = 31;
					
					for($k = 1; $k <= $TotalDay; $k++)
					{
					    $db->Query("INSERT INTO stats_date VALUES ('$nowYear','$i','$k','0')");
					}
			    }
			}
			
			$db->Query("UPDATE stats_year SET hits=hits+1 WHERE year='$nowYear'");
			$db->Query("UPDATE stats_month SET hits=hits+1 WHERE (year='$nowYear') AND (month='$nowMonth')");
			$db->Query("UPDATE stats_date SET hits=hits+1 WHERE (year='$nowYear') AND (month='$nowMonth') AND (date='$nowDate')");
		}
		
		function Stats()
		{
			GLOBAL $db;
			
			$now			= explode("-", date("d-m-Y-H"));
			
			$nowhour		= $now[3];
			$nowyear		= $now[2];
			$nowmonth		= $now[1];
			$nowdate		= $now[0];
			
			$text			= "<b>Website Statistics for " . _SITE_TITLE . "</b><br>";
			
			$hits			= $db->Fetch("SELECT SUM(hits) AS hits FROM stats_year");
			
		    $text			.= "Total hits: $hits hits<br>";
		    
		    $result			= $db->Fetch("SELECT year, month, hits FROM stats_month ORDER BY hits DESC LIMIT 0,1");
		    
		    $month			= $this->GetMonth($month);
		    
		    $text			.= "Busiest Month: " . $result["month"] . " " . $result["year"] . " (" . $result["hits"] . " hits)<br>";
		
		    $result			= $db->Fetch("SELECT year, month, date, hits FROM stats_date ORDER BY hits DESC LIMIT 0,1");
		    
		    $month			= $this->getmonth($result["month"]);
		    
		    $text			.= "Busiest Day: " . $result["date"] . " " . $result["month"] . " " . $result["year"] . " (" . $result["hits"] . " hits)<br>";
		    
			$l_size			= getimagesize(_BASE_PATH . "inc/img/stats/leftbar.gif");
			$m_size			= getimagesize(_BASE_PATH . "inc/img/stats/mainbar.gif");
			$r_size			= getimagesize(_BASE_PATH . "inc/img/stats/rightbar.gif");
			
			$TotalHitsYear	= $db->Fetch("SELECT SUM(hits) AS TotalHitsYear FROM stats_year");
			
			$db->Query("SELECT year, hits FROM stats_year ORDER BY year");
			
			$text			.= "<br><b>Yearly Statistics</b><br>";
			$text			.= "<table cellspacing=\"1\" cellpadding=\"3\" border=\"0\" width=\"100%\">";
			
			while(list($year, $hits) = $db->NextRow())
			{
				$text			.= "<tr><td width=\"50%\">$year</td><td width=\"50%\">";
				
				$WidthIMG		= round(100 * $hits/$TotalHitsYear);
				
				$text			.= "<img src=\"" . _SITE_URL . "/inc/img/stats/leftbar.gif\" Alt=\"\" width=\"$l_size[0]\" height=\"$l_size[1]\"><img src=\"" . _SITE_URL . "/inc/img/stats/mainbar.gif\" height=\"$m_size[1]\" width=\"" . $WidthIMG * 2 . "\" Alt=\"\"><img src=\"" . _SITE_URL . "/inc/img/stats/rightbar.gif\" Alt=\"\" width=\"$r_size[0]\" height=\"$r_size[1]\"> ($hits)</td></tr>";
			}
			
			$text			.= "</table>";
			
			$TotalHitsMonth	= $db->Fetch("SELECT SUM(hits) AS TotalHitsMonth FROM stats_month WHERE year='$nowyear'");
			$result			= $db->Fetch("SELECT month, hits FROM stats_month WHERE year='$nowyear'");
			
			$text			.= "<br><b>Monthly Statistics $nowyear</b><br>";
			$text			.= "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\">";
			
			while(list($month, $hits) = $db->NextRow())
			{
				$text			.= "<tr><td width=\"50%\">" . $this->GetMonth($month) . "</td><td width=\"50%\">";
				
				$WidthIMG		= round(100 * $hits/$TotalHitsMonth);
				
				$text			.= "<img src=\"" . _SITE_URL . "/inc/img/stats/leftbar.gif\" Alt=\"\" width=\"$l_size[0]\" height=\"$l_size[1]\"><img src=\"" . _SITE_URL . "/inc/img/stats/mainbar.gif\" height=\"$m_size[1]\" width=\"" . $WidthIMG * 2 . "\" Alt=\"\"><img src=\"" . _SITE_URL . "/inc/img/stats/rightbar.gif\" Alt=\"\" width=\"$r_size[0]\" height=\"$r_size[1]\"> ($hits)</td></tr>";
				$text			.= "</td></tr>";
			}
			
			$text			.= "</table>";
			
			$TotalHitsDate	= $db->Fetch("SELECT SUM(hits) AS TotalHitsDate FROM stats_date WHERE year='$nowyear' AND month='$nowmonth'");
			
			$db->Query("SELECT year, month, date, hits FROM stats_date WHERE year='$nowyear' AND month='$nowmonth' ORDER BY date");
			
			$total			= $db->NumRows();
			
			$text			.= "<br><b>Daily Statistics " . $this->GetMonth($nowmonth) . ", $nowyear</b><br>";
			$text			.= "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\">";
			
			while(list($year, $month, $date, $hits) = $db->NextRow())
			{
				$text			.= "<tr><td width=\"50%\">$date</td><td>";
				
				if($hits == 0)
				{
				    $WidthIMG		= 0;
				    $d_percent		= 0;
				}
				else
				{
				    $WidthIMG		= round(100 * $hits/$TotalHitsDate,0);
				    $d_percent		= substr(100 * $hits / $TotalHitsDate, 0, 5);
				}
				
				$text			.= "<img src=\"" . _SITE_URL . "/inc/img/stats/leftbar.gif\" Alt=\"\" width=\"$l_size[0]\" height=\"$l_size[1]\"><img src=\"" . _SITE_URL . "/inc/img/stats/mainbar.gif\" height=\"$m_size[1]\" width=\"" . $WidthIMG * 2 . "\" Alt=\"\"><img src=\"" . _SITE_URL . "/inc/img/stats/rightbar.gif\" Alt=\"\" width=\"$r_size[0]\" height=\"$r_size[1]\"> $d_percent% ($hits)</td></tr>";
				$text			.= "</td></tr>";
			}
			
			$text			.= "</table>";
			
		    return $text;
		}
		
		function getmonth($month)
		{
		    if ($month == 1) return "January";
		    if ($month == 2) return "February";
		    if ($month == 3) return "March";
		    if ($month == 4) return "April";
		    if ($month == 5) return "May";
		    if ($month == 6) return "June";
		    if ($month == 7) return "July";
		    if ($month == 8) return "August";
		    if ($month == 9) return "September";
		    if ($month == 10) return "October";
		    if ($month == 11) return "November";
		    if ($month == 12) return "December";
		}
	
	}
	
	$statistics	= new Statistics;

?>