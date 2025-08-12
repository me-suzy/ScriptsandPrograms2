<?php

/*
 *
 *  AMXBans, managing bans for Half-Life modifications
 *  Copyright (C) 2003, 2004  Ronald Renes / Jeroen de Rover
 *
 *	web		: http://www.xs4all.nl/~yomama/amxbans/
 *	mail	: yomama@xs4all.nl
 *	ICQ		: 104115504
 *   
 *	This file is part of AMXBans.
 *
 *  AMXBans is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  AMXBans is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with AMXBans; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 */

// Start session
session_start();

// Require basic site files
require("include/config.inc.php");

if ($config->error_handler == "enabled") {
	include("$config->error_handler_path");
}

if ($config->geoip == "enabled") {
	include("$config->path_root/include/geoip.inc");
}


require("$config->path_root/include/functions.inc.php");



// First we get the total number of bans in the date base.
$resource	= mysql_query("SELECT COUNT(bid) AS all_bans FROM $config->bans") or die(mysql_error());
$result		= mysql_fetch_object($resource);

// Get the page number, if no number is defined make default 1
if(isset($_GET["page"]) AND is_numeric($_GET["page"])) {
	$page = $_GET["page"];
	
	if($page < 1) {
		trigger_error("Pagenumbers need to be >= 1.", E_USER_NOTICE);
	}
} else {
	$page = 1;
}

// Get the view number, if no number is defined set to default
if(isset($_GET["view"]) AND is_numeric($_GET["view"])) {
	$view = $_GET["view"];
} else {
	$view = $config->bans_per_page;
}

// Dunno what to say here ;)
if($result->all_bans < $view) {
	$query_start = 0;
	$query_end = $view;
	
	$page_start = 1;
	$page_end = $result->all_bans;
	
	$pages_results = "Results ".$page_start." to ".$page_end;
}

else {
	if($page == 1) {
		$query_start = 0;
		$query_end = $view;
	
		$page_start = 1;
		$page_end = $view;
		
		$pages_results = "displaying ".$page_start." - ".$page_end." of ".$result->all_bans." results";
		
		$next_page = $page + 1;
		
		$previous_button = NULL;
		$next_button = "<b><a href='".$config->document_root."/ban_list.php?view=".$view."&amp;page=".$next_page."' class='hover_black'><img src='$config->document_root/images/right.gif' border='0' alt='next'></a></b>";
	}
	
	else {
		$remaining = $result->all_bans % $view;
		$pages = ($result->all_bans - $remaining) / $view;
		
		$query_start = $view * ($page - 1);
		$query_end = $view;
		
		if($page > $pages + 1) {
			trigger_error("De pagina die je hebt opgegeven bestaat niet.", E_USER_NOTICE);
		}
		
		elseif($page == $pages + 1) {
			$page_start = ($view * ($page - 1)) + 1;
			$page_end = $page_start + $remaining - 1;
			
			$previous_page = $page - 1;
			
			$previous_button = "<b><a href='".$config->document_root."/ban_list.php?view=".$view."&amp;page=".$previous_page."' class='hover_black'><img src='$config->document_root/images/left.gif' border='0' alt='previous'></a></b>";
			$next_button = NULL;
		}
			
		else {
			$page_start = ($view * ($page - 1)) + 1;
			$page_end = $page_start + ($view - 1);
			
			$previous_page = $page - 1;
			$next_page = $page + 1;
			
			$previous_button = "<b><a href='".$config->document_root."/ban_list.php?view=".$view."&amp;page=".$previous_page."' class='hover_black'><img src='$config->document_root/images/left.gif' border='0' alt='previous'></a></b>";
			$next_button = "<b><a href='".$config->document_root."/ban_list.php?view=".$view."&amp;page=".$next_page."' class='hover_black'><img src='$config->document_root/images/right.gif' border='0' alt='next'></a></b>";
		}
		
		$pages_results = "displaying ".$page_start." - ".$page_end." of ".$result->all_bans." results";
	}
}

// Make the array for the ban list
if ($config->fancy_layers != "enabled") {
	if ($config->display_reason == "enabled") {  
		$resource		= mysql_query("SELECT bid, player_nick, admin_nick, ban_reason, ban_created, ban_length, server_ip FROM $config->bans ORDER BY ban_created DESC LIMIT ".$query_start.",".$query_end) or die(mysql_error());
	} else {
		$resource		= mysql_query("SELECT bid, player_nick, admin_nick, ban_reason, ban_created, ban_length, server_ip FROM $config->bans ORDER BY ban_created DESC LIMIT ".$query_start.",".$query_end) or die(mysql_error());
	}
} else {
	$resource		= mysql_query("SELECT * FROM $config->bans ORDER BY ban_created DESC LIMIT ".$query_start.",".$query_end) or die(mysql_error());
}


$ban_array	= array();

while($result = mysql_fetch_object($resource)) {
	$bid			= $result->bid;
	$date			= dateShort($result->ban_created);
	$player		= $result->player_nick;
	$admin		= $result->admin_nick;
	$duration = $result->ban_length;
	$serverip	= $result->server_ip;

	if ($config->fancy_layers == "enabled") {
		$steamid		= $result->player_id;
		$ipaddress	= $result->player_ip;

		if(!empty($result->player_ip)) {
			$player_ip = htmlentities($result->player_ip, ENT_QUOTES);
		} else {
			$player_ip = "<i><font color='#677882'>no IP address present</font></i>";
		}

		$ldate			= dateShorttime($result->ban_created);
		$banlength	= $result->ban_length;
	
		if(empty($result->ban_length) OR $result->ban_length == 0) {
			$ban_duration = "Permanent";
			$ban_end = "<i><font color='#677882'>Not applicable</font></i>";
		} else {
			$ban_duration = $result->ban_length." mins";
			$date_and_ban = $result->ban_created + ($result->ban_length * 60);

			$now = date("U");
			if($now >= $date_and_ban) {
				$ban_end = dateShorttime($date_and_ban)."&nbsp;(allready expired)";
			} else {
				$ban_end = dateShorttime($date_and_ban)."&nbsp;(".timeleft($now,$date_and_ban)." remaining)";
			}
		}
		
		if($result->ban_type == "SI") {
			$ban_type = "SteamID and/or IP address";
		} else {
			$ban_type = "SteamID";
		}
		
		if($result->server_name <> "website") {
			$query2 = "SELECT nickname FROM $config->amxadmins WHERE steamid = '".$result->admin_id."'";	
			$resource2 = mysql_query($query2) or die(mysql_error());	
			$result2 = mysql_fetch_object($resource2);
			
			$admin_name = htmlentities($result->admin_nick, ENT_QUOTES);
			$web_admin_name = htmlentities($result2->nickname, ENT_QUOTES);
			$server_name = $result->server_name;
		} else {
			$admin_name = htmlentities($result->admin_nick, ENT_QUOTES);
			$web_admin_name = $admin_name;
			$server_name = "Website";
		}
	}

	$ban_reason = htmlentities($result->ban_reason, ENT_QUOTES);

	if ($serverip != "") {

		// Get the gametype for each ban
		$resource2	= mysql_query("SELECT gametype FROM $config->servers WHERE address = '$serverip'") or die(mysql_error());

		while($result2 = mysql_fetch_object($resource2)) {
			$gametype = $result2->gametype;
		}
	} else {
		$gametype = "html";
	}

	// get previous offences if any
	$resource4	= mysql_query("SELECT count(player_id) AS repeat FROM $config->ban_history WHERE player_id = '$steamid'") or die(mysql_error());
	while($result4 = mysql_fetch_object($resource4)) {
		$bancount = $result4->repeat;
	}

	if(empty($duration)) {
		$duration = "Permanent";
	}	else {
		if ($duration >= 10000) {
			$duration = round($duration / 1440);
			$duration = $duration." days";
		} else {
			$duration = $duration." mins";
		}
	}

if ($config->geoip == "enabled") {
	$gi = geoip_open("$config->path_root/include/GeoIP.dat",GEOIP_STANDARD);
	$cc = geoip_country_code_by_addr($gi, $player_ip);
	$cn = geoip_country_name_by_addr($gi, $player_ip);
	geoip_close($gi);
} else {
	$cc = "";
	$cn = "";
}

	// Asign variables to the array used in the template
	if ($config->fancy_layers == "enabled") {
		$ban_info = array(
		"gametype"			=> $gametype,
		"bid"						=> $bid,
		"date"					=> $date,
		"player"				=> $player,
		"cc"						=> $cc,
		"cn"						=> $cn,
		"admin"					=> $admin_name,
		"webadmin"			=> $web_admin_name,
		"duration"			=> $duration,
		"player_id"			=> $steamid,
		"player_ip"			=> $player_ip,
		"ban_start"			=> $ldate,
		"ban_duration"	=> $ban_duration,
		"ban_end"				=> $ban_end,
		"ban_type"			=> $ban_type,
		"ban_reason"		=> $ban_reason,
		"server_name"		=> $server_name,
		"bancount"			=> $bancount
		);
	} else {
		if ($config->display_reason == "enabled") {
			$ban_info = array(
				"gametype"		=> $gametype,
				"bid"					=> $bid,
				"date"				=> $date,
				"player"			=> $player,
				"cc"					=> $cc,
				"cn"					=> $cn,
				"admin"				=> $admin,
				"duration"		=> $duration,
				"ban_reason"	=> $ban_reason
			);
		} else {
			$ban_info = array(
				"gametype"		=> $gametype,
				"bid"					=> $bid,
				"date"				=> $date,
				"player"			=> $player,
				"cc"					=> $cc,
				"cn"					=> $cn,
				"admin"				=> $admin,
				"duration"		=> $duration
			);
		}
	}
	
	$ban_array[] = $ban_info;
}

if ($config->version_checking == "enabled") {
	$new_version_exists = CheckAMXWebVersion();
} else {
	$new_version_exists = 0;
}


/*
 * Template parsing
 */


$title			= "Banlist";

// Section
$section = "banlist";

$smarty = new dynamicPage;

$smarty->assign("meta","");
$smarty->assign("title",$title);
$smarty->assign("section",$section);
$smarty->assign("dir",$config->document_root);
$smarty->assign("this",$_SERVER['PHP_SELF']);
$smarty->assign("fancy_layers", $config->fancy_layers);
$smarty->assign("display_reason", $config->display_reason);
$smarty->assign("geoip", $config->geoip);
$smarty->assign("bans",$ban_array);
$smarty->assign("pages_results",$pages_results);
$smarty->assign("previous_button",$previous_button);
$smarty->assign("next_button",$next_button);
$smarty->assign("new_version",$new_version_exists);
$smarty->assign("update_url",$config->update_url);

$smarty->display('main_header.tpl');
$smarty->display('ban_list.tpl');
$smarty->display('main_footer.tpl');

?>