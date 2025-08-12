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

// Require basic site files
require("../include/config.inc.php"); 	// General configuration settings for the site

if ($config->error_handler == "enabled") {
	include("$config->error_handler_path");
}

include("$config->path_root/include/accesscontrol.inc.php");

if($_SESSION['servers_edit'] != "yes") {
	echo "You do not have the required credentials to view this page.";
	exit();
}

if ($_POST['action'] == " apply ") {
	$resource = mysql_query("INSERT INTO $config->reasons VALUES('', '".$_POST['address']."', '".$_POST['reason']."')") or die (mysql_error());
} else if ($_POST['action'] == " remove ") {
	$resource = mysql_query("DELETE FROM $config->reasons WHERE id = '".$_POST['id']."'") or die (mysql_error());
} else if ($_POST['action'] == " edit ") {
	$resource = mysql_query("UPDATE $config->reasons SET reason = '".$_POST['reason']."' WHERE id = '".$_POST['id']."'") or die (mysql_error());
}

if (isset($_POST['remove'])) {

	//remove the server with given serverID
	$resource2 = mysql_query("DELETE FROM $config->servers WHERE id = '".$_POST['id']."'") or die (mysql_error());

	$now = date("U");
	$add_log	= mysql_query("INSERT INTO $config->logs VALUES ('', '$now', '".$_SERVER['REMOTE_ADDR']."', '".$_SESSION['uid']."', 'server management', 'Removed ServerID ".$_POST['server_id']."')") or die (mysql_error());

} else if (isset($_POST['apply'])) {

	// update server details server...
	$resource3	= mysql_query("UPDATE $config->servers SET gametype = '".$_POST['mod']."', rcon = '".$_POST['rcon']."', amxban_motd = '".$_POST['amxban_motd']."', motd_delay = '".$_POST['motd_delay']."' WHERE id = '".$_POST['id']."'") or die (mysql_error());

	$now = date("U");
	$add_log	= mysql_query("INSERT INTO $config->logs VALUES ('', '$now', '".$_SERVER['REMOTE_ADDR']."', '".$_SESSION['uid']."', 'server management', 'Edited ServerID ".$_POST['server_id']."')") or die (mysql_error());
}

if (isset($_POST['list_reasons'])) {

	// fetch all the reasons set for this server...
	$resource4		= mysql_query("SELECT * FROM $config->reasons WHERE address = '".$_POST['address']."'") or die (mysql_error());
	$reason_array = array();

	while($result = mysql_fetch_object($resource4)) {
		$reason_info = array(
				id			=> $result->id,
				reason	=> $result->reason
				);

		$reason_array[] = $reason_info;
	}
}

//make an array for the servers...
$resource = mysql_query("SELECT * FROM $config->servers ORDER BY hostname ASC") or die (mysql_error());

$server_array	= array();

while($result = mysql_fetch_object($resource)) {
	$plugver		= explode("_", $result->amxban_version);
	$outdated		= CheckAMXPlugVersion($plugver[0],$plugver[1]);

	// Asign variables to the array used in the template
	$server_info = array(
		"id"							=> $result->id,
		"timestamp"				=> $result->timestamp,
		"hostname"				=> $result->hostname,
		"address"					=> $result->address,
		"gametype"				=> $result->gametype,
		"rcon"						=> $result->rcon,
		"plugin"					=> $plugver[0],
		"version"					=> $plugver[1],
		"amxban_version"	=> $result->amxban_version,
		"amxban_motd"			=> $result->amxban_motd,
		"motd_delay"			=> $result->motd_delay,
		"amxban_menu"			=> $result->amxban_menu,
		"outdated"				=> $outdated,
		);

	$server_array[] = $server_info;
}

/////////////////////////////////////////////////////////////////
//	Template parsing
/////////////////////////////////////////////////////////////////

$title		= "Banlist";
$section	= "servers";

$smarty = new dynamicPage;

$smarty->assign("meta","");
$smarty->assign("title",$title);
$smarty->assign("section",$section);
$smarty->assign("dir",$config->document_root);
$smarty->assign("this",$_SERVER['PHP_SELF']);

$smarty->assign("servers",$server_array);
$smarty->assign("reasons",$reason_array);
$smarty->assign("version_checking",$config->version_checking);
$smarty->assign("edit",$_POST['edit']);
$smarty->assign("serverdetails",$serverdetails_array);
$smarty->assign("list_reasons",$_POST['list_reasons']);
$smarty->assign("action",$_POST['action']);

$smarty->assign("id",$_POST['id']);
$smarty->assign("gametype",$_POST['gametype']);
$smarty->assign("rcon",$_POST['rcon']);
$smarty->assign("hostname",$_POST['hostname']);
$smarty->assign("address",$_POST['address']);
$smarty->assign("amxban_motd",$_POST['amxban_motd']);
$smarty->assign("motd_delay",$_POST['motd_delay']);

$smarty->display('main_header.tpl');
$smarty->display('servers.tpl');
$smarty->display('main_footer.tpl');

?>