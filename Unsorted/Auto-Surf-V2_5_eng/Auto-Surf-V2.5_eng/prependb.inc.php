<?php
include("header.inc.php");

$result9 = mysql_query("SELECT url, seitenname, email, loginpoints, reportpoints, bannerklick, startcredits, jackport, refjackport, ratio, time, defaultbanner, defaultbannerurl, defaulturl, emailmodi, logeout, registriert, referview, frequency, starten, tausch  FROM `demo_a_admin`");
$myrow2 = mysql_fetch_row($result9);

$points_view = $myrow2[9];

$points_referer_view = $myrow2[17];   

$points_referer_jackpot_views = $myrow2[7];      
$points_referer_jackpot_points = $myrow2[8];      
$points_hit=1;    
$points_banner_click = rand($zufc,$zufd);  
$points_report = $myrow2[4];     
$points_login = rand($zufa,$zufb);       
$seitenname = $myrow2[1];  
$emailadresse = $myrow2[2];
$seitenstart = $myrow2[19];   
$tausch = $myrow2[20]; 

$url_index = $myrow2[0];  
$url_logout_succesfull = $myrow2[15];
$url_register_succesfull = $myrow2[16];
$url_email_modified = $myrow2[14];
$url_default = $myrow2[13];

$banner_default_source = $myrow2[11];
$banner_default_target = $myrow2[12];
$banner_default_alt ='';

$showup_frequency = $myrow2[18];;
$showup_time = $myrow2[10];

$result99 = mysql_query("SELECT gampoints, gamchance, gampointszu FROM `demo_a_gambleadmin`");
$myrow99 = mysql_fetch_row($result99);

$gamble_points = $myrow99[0];
$gamble_chance = $myrow99[1];
$gamble_points_add = $myrow99[2];

function viewpage()
{
	global $userid, $showup_frequency, $points_hit, $points_view, $showup_time, $url_default, $points_referer_view, $points_referer_jackpot_views, $points_referer_jackpot_points;
	if($userid)
	{
		$query="SELECT recently, refererid, views FROM demo_a_accounts WHERE id='$userid';";
		$result=mysql_query($query);
		if($result)
		{
			$result = @mysql_fetch_array($result);
			$recently=split(' ', $result[recently]);
			$refererid=$result[refererid];
			$views=$result[views];
			if($points_referer_jackpot_views && $points_referer_jackpot_points && $views==$points_referer_jackpot_views){
				echo $views;
				$query="UPDATE demo_a_accounts SET points=points+$points_referer_jackpot_points WHERE id='$refererid';";
				mysql_query($query);
			}
		}
	}
	
	$query = "SELECT id, url FROM demo_a_accounts WHERE  showup='1' AND id!='$userid' AND points>=$points_hit AND savepoints='0'";
	
	for($i=0; $i<count($recently) && $i<$showup_frequency; $i++)
	{
		$query .= " AND id!='$recently[$i]'";
		$recentlies[]=$recently[$i];
	}
	$query .= ' ORDER BY points DESC';
	$result=mysql_query($query);
	$result=@mysql_fetch_array($result);
	if($result[id] && $result[url])
	{
		$query="UPDATE demo_a_accounts SET hits=hits+1, points=points-$points_hit WHERE id='".$result[id]."';";
		mysql_query($query);
		$recently=implode(" ", $recentlies);
		$recently=$result[id]." ".$recently;
		$query="UPDATE demo_a_accounts SET views=views+1, points=points, recently='$recently', lastview='".time()."' WHERE id='$userid' AND lastview<='".time().-$showup_time."';";
		mysql_query($query);
		if($refererid)
		{
			$query="UPDATE demo_a_accounts SET points=points+".$points_view*$points_referer_view." WHERE id='$refererid';";
			mysql_query($query);
		}
	}else{ 
                $result[url]=$url_default;
                $query="UPDATE demo_a_accounts SET views=views+1, points=points, lastview='".time()."' WHERE id='$userid' AND lastview<='".time().-$showup_time."';"; 
                mysql_query($query);
                if($refererid)
		{
			$query="UPDATE demo_a_accounts SET points=points+".(1)*$points_referer_view." WHERE id='$refererid';";
			mysql_query($query);
		}
        }
	return array(
		url=>$result[url],
		id=>$result[id]
	);
}



function banner_view()
{
	global $id, $banner_default_source, $userid;
	$query="SELECT id, source, alt FROM demo_a_banners WHERE clicks>0 OR views>0 ORDER BY lastview ASC LIMIT 1";
	$result=mysql_query($query);
	$banner=@mysql_fetch_array($result);
	$query="UPDATE demo_a_banners SET views=views-1, lastview='".time()."' WHERE id='".$banner[id]."';";
	mysql_query($query);
	if(!$banner[source])
		$banner[source]=$banner_default_source;
	if(!$banner[alt])
		$banner[alt]=$banner_default_alt;
	echo '<a href="./banner_click.php?bannerid='.$banner[id].'&&userid='.$userid.'" target="_blank"><img src="'.$banner[source].'" border="0" alt="'.$banner[alt].'"></a>';
}
function banner_click()
{
	global $bannerid, $userid, $banner_default_target, $points_banner_click;
	if($bannerid)
	{
		$query="UPDATE demo_a_banners SET clicks=clicks-1 WHERE id='$bannerid';";
		mysql_query($query);
		$query="UPDATE demo_a_accounts SET points=points+$points_banner_click WHERE id='$userid';";
		mysql_query($query);
		$query="SELECT target FROM demo_a_banners WHERE id='$bannerid'";
		$result=mysql_query($query);
		if($result)
			$target=@mysql_result($result, 0);
		
	}
	if(!$target)
		$target=$banner_default_target;
	header("Location: $target");
}
?>