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

require("$config->path_root/include/functions.inc.php");

// Make the array for the admin list
$query			= "SELECT DISTINCT steamid, nickname FROM $config->amxadmins ORDER BY id ASC";
$resource		= mysql_query($query) or die(mysql_error());
	
$admin_array	= array();

while($result = mysql_fetch_object($resource)) {
	$steamid	= $result->steamid;
	$nickname	= htmlentities($result->nickname, ENT_QUOTES);


	// Asign variables to the array used in the template
	$admin_info = array(
		"steamid"		=> $steamid,
		"nickname"	=> $nickname
		);
	
	$admin_array[] = $admin_info;
}

// Make the array for the server list
$query2			= "SELECT address, hostname FROM $config->servers ORDER BY hostname ASC";
$resource2	= mysql_query($query2) or die(mysql_error());
	
$server_array	= array();

while($result2 = mysql_fetch_object($resource2)) {
	$address	= $result2->address;
	$hostname	= htmlentities($result2->hostname, ENT_QUOTES);


	// Asign variables to the array used in the template
	$server_info = array(
		"address"		=> $address,
		"hostname"	=> $hostname
		);
	
	$server_array[] = $server_info;
}


if ((isset($_POST['nick'])) || (isset($_POST['steamid'])) || (isset($_POST['reason'])) || (isset($_POST['date'])) || (isset($_POST['timesbanned'])) || (isset($_POST['admin'])) || (isset($_POST['server']))) {

	// Make the array for the active bans list
	if (isset($_POST['nick'])) {
		$resource3	= mysql_query("SELECT bid, player_nick, admin_nick, ban_length, ban_created, server_ip FROM $config->bans WHERE player_nick LIKE '%".$_POST['nick']."%'") or die(mysql_error());
	} else if (isset($_POST['steamid'])) {
		$resource3	= mysql_query("SELECT bid, player_nick, admin_nick, ban_length, ban_created, server_ip FROM $config->bans WHERE player_id = '".$_POST['steamid']."'") or die(mysql_error());
	} else if (isset($_POST['reason'])) {
		$resource3	= mysql_query("SELECT bid, player_nick, admin_nick, ban_length, ban_created, server_ip FROM $config->bans WHERE ban_reason LIKE '%".$_POST['reason']."%'") or die(mysql_error());
	} else if (isset($_POST['date'])) {
		$date		= substr_replace($_POST['date'], '', 2, 1);
		$date		= substr_replace($date, '', 4, 1);
		$resource3	= mysql_query("SELECT bid, player_nick, admin_nick, ban_length, ban_created, server_ip FROM $config->bans WHERE FROM_UNIXTIME(ban_created,'%d%m%Y') LIKE '$date'") or die(mysql_error());
	} else if (isset($_POST['timesbanned'])) {
		$resource3	= mysql_query("SELECT bid, player_nick, admin_nick, ban_length, ban_created, server_ip, COUNT(*) FROM $config->bans GROUP BY player_id HAVING COUNT(*) >= '".$_POST['timesbanned']."'") or die (mysql_error());
	} else if (isset($_POST['admin'])) {
		$resource3	= mysql_query("SELECT bid, player_nick, admin_nick, ban_length, ban_created, server_ip FROM $config->bans WHERE admin_id = '".$_POST['admin']."'") or die(mysql_error());
	} else if (isset($_POST['server'])) {
		$resource3	= mysql_query("SELECT bid, player_nick, admin_nick, ban_length, ban_created, server_ip FROM $config->bans WHERE server_ip = '".$_POST['server']."'") or die(mysql_error());
	} else  {
		echo "KOE";
	}
	$ban_array	= array();

	while($result3 = mysql_fetch_object($resource3)) {
		$bid			= $result3->bid;
		$date			= dateShortYear($result3->ban_created);
		$player		= htmlentities($result3->player_nick, ENT_QUOTES);
		$admin		= htmlentities($result3->admin_nick, ENT_QUOTES);
		$duration = $result3->ban_length;
		$serverip	= $result3->server_ip;

		if ($serverip != "") {

			// Get the gametype for each ban
			$query4			= "SELECT gametype FROM $config->servers WHERE address = '$serverip'";
			$resource4	= mysql_query($query4) or die(mysql_error());
			while($result4 = mysql_fetch_object($resource4)) {
				$gametype = $result4->gametype;
			}
		} else {
			$gametype = "html";
		}

		if(empty($duration)) {
			$duration = "Permanent";
		}	else {
			$duration = $duration." mins";
		}
	
		// Asign variables to the array used in the template
		$ban_info = array(
			"gametype"	=> $gametype,
			"bid"				=> $bid,
			"date"			=> $date,
			"player"		=> $player,
			"admin"			=> $admin,
			"duration"	=> $duration
			);
	
		$ban_array[] = $ban_info;
	}

	// Make the array for the expired bans list

	if (isset($_POST['nick'])) {
		$query5	= "SELECT bhid, player_nick, admin_nick, ban_length, ban_created, server_ip FROM $config->ban_history WHERE player_nick like '%".$_POST['nick']."%'";
	} else if (isset($_POST['steamid'])) {
		$query5	= "SELECT bhid, player_nick, admin_nick, ban_length, ban_created, server_ip FROM $config->ban_history WHERE player_id = '".$_POST['steamid']."'";
	} else if (isset($_POST['reason'])) {
		$query5	= "SELECT bhid, player_nick, admin_nick, ban_length, ban_created, server_ip FROM $config->ban_history WHERE ban_reason LIKE '%".$_POST['reason']."%'";
	} else if (isset($_POST['date'])) {
		$query5	= "SELECT bhid, player_nick, admin_nick, ban_length, ban_created, server_ip FROM $config->ban_history WHERE FROM_UNIXTIME(ban_created,'%d%m%Y') LIKE '$date'";
	} else if (isset($_POST['timesbanned'])) {
		$query5	= "SELECT bhid, player_nick, admin_nick, ban_length, ban_created, server_ip, COUNT(*) FROM $config->ban_history GROUP BY player_id HAVING COUNT(*) >= '".$_POST['timesbanned']."'";
	} else if (isset($_POST['admin'])) {
		$query5	= "SELECT bhid, player_nick, admin_nick, ban_length, ban_created, server_ip FROM $config->ban_history WHERE admin_id = '".$_POST['admin']."'";
	} else if (isset($_POST['server'])) {
		$query5	= "SELECT bhid, player_nick, admin_nick, ban_length, ban_created, server_ip FROM $config->ban_history WHERE server_ip = '".$_POST['server']."'";
}

	$resource5	= mysql_query($query5) or die(mysql_error());
	$exban_array	= array();

	while($result5 = mysql_fetch_object($resource5)) {
		$bhid					= $result5->bhid;
		$ex_date			= dateShortYear($result5->ban_created);
		$ex_player		= htmlentities($result5->player_nick, ENT_QUOTES);
		$ex_admin			= htmlentities($result5->admin_nick, ENT_QUOTES);
		$ex_duration	= $result5->ban_length;
		$ex_serverip	= $result5->server_ip;

		if ($ex_serverip != "") {

			// Get the gametype for each ban
			$query6			= "SELECT gametype FROM $config->servers WHERE address = '$ex_serverip'";
			$resource6	= mysql_query($query6) or die(mysql_error());

			while($result6 = mysql_fetch_object($resource6)) {
				$ex_gametype = $result6->gametype;
			}
		} else {
			$ex_gametype = "html";
		}

		if(empty($ex_duration)) {
			$ex_duration = "Permanent";
		}	else {
			$ex_duration = $ex_duration." mins";
		}
	
		// Asign variables to the array used in the template
		$exban_info = array(
			"ex_gametype"	=> $ex_gametype,
			"bhid"				=> $bhid,
			"ex_date"			=> $ex_date,
			"ex_player"		=> $ex_player,
			"ex_admin"		=> $ex_admin,
			"ex_duration"	=> $ex_duration
			);
	
		$exban_array[] = $exban_info;
	}
}

/****************************************************************
* Template parsing
****************************************************************/

// Header
$title = "Search bans";

// Section
$section = "search";

// Parsing
$smarty = new dynamicPage;

$smarty->assign("meta","");
$smarty->assign("title",$title);
$smarty->assign("dir",$config->document_root);
$smarty->assign("this",$_SERVER['PHP_SELF']);
$smarty->assign("section",$section);
$smarty->assign("admins",$admin_array);
$smarty->assign("servers",$server_array);
$smarty->assign("bans",$ban_array);
$smarty->assign("exbans",$exban_array);
$smarty->assign("nick",$_POST['nick']);
$smarty->assign("steamid",$_POST['steamid']);
$smarty->assign("reason",$_POST['reason']);
$smarty->assign("date",$_POST['date']);
$smarty->assign("timesbanned",$_POST['timesbanned']);
$smarty->assign("admin",$_POST['admin']);
$smarty->assign("server",$_POST['server']);
$smarty->display('main_header.tpl');
$smarty->display('ban_search.tpl');
$smarty->display('main_footer.tpl');

?>
