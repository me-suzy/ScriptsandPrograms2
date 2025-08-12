<?php
	class Banner
	{
		var $banner_id;
		var $con;
		var $isExpired;

		function Banner($campaign_id,$theCon)
		{
			$this->isExpired = 0;
			$this->con = $theCon;			
			$this->Expiration($campaign_id);
			$banner = $this->getBanner($campaign_id);
			$this->showBanner($banner);
			if($this->isExpired == 0)
				$this->updateStatistic($this->banner_id);
		}

		function Expiration($campaign_id)
		{
			$SQL = "SELECT clicks,views FROM banner_campaign WHERE id = $campaign_id AND (NOW() BETWEEN start_date AND end_date)";
			$result = mysql_query($SQL,$this->con);
			if(mysql_affected_rows()>0)
			{
				$row = mysql_fetch_array($result);
				$target_clicks = $row['clicks'];
				$target_views = $row['views'];
				mysql_free_result($result);
				$SQL = "SELECT clicks, views FROM banner_stat WHERE campaign_id = $campaign_id";
				$result = mysql_query($SQL,$this->con);
				if(mysql_affected_rows()>0)
				{
					while($row = mysql_fetch_array($result))
					{
						$total_clicks += $row['clicks'];
						$total_views += $row['views'];
					}
				}
				else
				{
					$this->isExpired = 0;
				}
				if((($total_clicks >= $target_clicks) && ($target_clicks != 0)) || (($total_views >= $target_views) && ($target_views !=0)))
				{
					$this->isExpired = 1;
				}
				else
				{
					$this->isExpired = 0;
				}
			}
			else
			{
				$this->isExpired = 1;
			}				

		}

		function getBanner($campaign_id)
		{
			if($this->isExpired == 1)
			{
				$SQL = "SELECT banner.id, graphic, url, alt, banner_size.size, show_text, popup FROM banner,banner_size WHERE campaign_id = $campaign_id AND banner.size=banner_size.size_id AND master='y'";
			}
			else
			{
				$SQL = "SELECT banner.id, graphic, url, alt, banner_size.size, show_text, popup,views FROM banner,banner_size,banner_stat WHERE banner.campaign_id = $campaign_id AND banner_stat.campaign_id = banner.campaign_id AND banner_stat.banner_id = banner.id AND banner.size=banner_size.size_id ORDER BY views ASC";
			}
			$result = mysql_query($SQL,$this->con);
			$row = mysql_fetch_array($result);

			$this->banner_id = $row['id'];
			$dimension = explode('x',$row['size']);
			if($this->isExpired==1)
			{
				$banner = "document.write(\"<a href='http://localhost/banner/redirect.php?bid=".$this->banner_id."&url=".$row['url']."&expired=1'";
				
				if($row['popup'] == 1)
					$banner .= " target='_new'";

				$banner .= "><img src='".$row['graphic']."' border='0' width='".trim($dimension[0])."' height='".trim($dimension[1])."' alt=\\\"".$row['alt']."\\\"></a>";
				
				if($row['show_text'] == 1)
					$banner .= "<br><center><font face='arial' size='1'><a href='http://localhost/banner/redirect.php?bid=".$this->banner_id."&url=".$row['url']."&expired=1'>".$row['alt']."</a></font></center>";
				
				$banner .= "\");";
			}
			else
			{
				$banner = "document.write(\"<a href='http://localhost/banner/redirect.php?bid=".$this->banner_id."&url=".$row['url']."&expired=0'";
				
				if($row['popup'] == 1)
					$banner .= " target='_new'";

				$banner .= "><img src='".$row['graphic']."' border='0' width='".trim($dimension[0])."' height='".trim($dimension[1])."' alt=\\\"".$row['alt']."\\\"></a>";

				if($row['show_text'] == 1)
				{
					$banner .= "<br><center><font face='arial' size='1'><a href='http://localhost/banner/redirect.php?bid=".$this->banner_id."&url=".$row['url']."&expired=0'";

					if($row['popup'] == 1)
					$banner .= " target='_new'";
	
					$banner .= ">".$row['alt']."</a></font></center>";
				}

				$banner .= "\");";
			}
			mysql_free_result($result);			
			return $banner;
		}
	
		function showBanner($banner)
		{
			print $banner;
		}

		function updateStatistic($banner_id)
		{
			$SQL = "UPDATE banner_stat SET views = views + 1 WHERE banner_id = $banner_id";
			mysql_query($SQL,$this->con);					
		}
	}
?>
