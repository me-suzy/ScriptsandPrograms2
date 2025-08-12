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
require("../include/config.inc.php");

if ($config->error_handler == "enabled") {
	include("$config->error_handler_path");
}

include("$config->path_root/include/accesscontrol.inc.php");

if($_SESSION['prune_db'] != "yes") {
	echo "You do not have the required credentials to view this page.";
	exit();
}

/*
if ($_POST['autopermban'] == "true") {

	//we need to check only non-perm bans...
	$nonpermbans	= mysql_query("SELECT bid, player_id, ban_reason FROM $config->bans WHERE ban_length != '0'") or die (mysql_error());

	// copy and delete each pruned ban
	while ($notperm	= mysql_fetch_object($nonpermbans)) {

		$check_ban	= mysql_query("SELECT COUNT(bhid) as blah FROM $config->ban_histroy WHERE player_id = '$notperm->player_id'") or die (mysql_error());

		while ($checked_ban	= mysql_fetch_object($check_ban)) {
			if ($checked_ban->blah >= $_POST['maxbanned']) {
				echo "ban with more then 3 previous bans found! bid = $notperm->bid, steamID = $notperm->player_id<br>\n";
				//$new_reason = $notperm->ban_reason . " (Ban made permanent by AMXBans, max offences reached)";
				//$edit_ban = mysql_query("UPDATE $config->bans SET ban_length = '0', ban_reason = '$new_reason' WHERE bid = '$notperm->bid'") or die (mysql_error());
			}
		}	
	}

//	if ($i != 0) {
//		$now = date("U");
//		$add_log	= mysql_query("INSERT INTO $config->logs VALUES ('', '$now', '".$_SERVER['REMOTE_ADDR']."', '".$_SESSION['uid']."', 'autoperm ban', 'Made bid $notperm->bid permanent, maxoffences ".$_POST['maxbanned']." reached')") or die (mysql_error());
//	}
}
*/

if ($_POST['submitted'] == "true") {

	//get all bans that need to be pruned...
	$bans	= mysql_query("SELECT * FROM $config->bans WHERE ban_created + ban_length*60 < UNIX_TIMESTAMP() AND ban_length != 0") or die (mysql_error());

	$unban_created	= date("U");
	$i = 0;
	$j = 0;
	$k = 0;

	// copy and delete each pruned ban
	while ($prunedban	= mysql_fetch_object($bans)) {

		$recidivist_found = 0;

		if ($config->autopermban_count != 0) {

			// first check if this steamID has been banned more then 'maxcount' before. if so, make it perm, and shift to the next item
			$check_ban	= mysql_query("SELECT COUNT(bhid) as blah FROM $config->ban_history WHERE player_id = '$prunedban->player_id'") or die (mysql_error());

			//subtract 1 from autopermbancount, since one exists in ban table
			$max_offences = $config->autopermban_count -1;

			while ($checked_ban	= mysql_fetch_object($check_ban)) {
				if ($checked_ban->blah >= $max_offences) {
					$recidivist_found = 1;
				}
			}
		}

		if ($recidivist_found == 0) {
			//echo "ban moved to banhistory table<br>\n";
			$cp_exbans	= mysql_query("INSERT INTO $config->ban_history VALUES ('','$prunedban->player_ip','$prunedban->player_id','$prunedban->player_nick','$prunedban->admin_ip','$prunedban->admin_id','$prunedban->admin_nick','$prunedban->ban_type','$prunedban->ban_reason','$prunedban->ban_created','$prunedban->ban_length','$prunedban->server_ip','$prunedban->server_name','$unban_created','Bantime expired','$config->admin_nickname')") or die (mysql_error());
			$rm_ban			= mysql_query("DELETE FROM $config->bans WHERE bid = '$prunedban->bid'") or die (mysql_error());
			$i++;
		} else {
			echo "ban with $config->autopermban_count or more previous bans found! bid = $prunedban->bid, steamID = $prunedban->player_id<br>\n";
			$new_reason = $notperm->ban_reason . " (Ban made permanent by AMXBans, max offences reached)";
			$edit_ban = mysql_query("UPDATE $config->bans SET ban_length = '0', ban_reason = '$new_reason' WHERE bid = '$notperm->bid'") or die (mysql_error());
			$j++;
		}
	}

	if ($i != 0) {
		$now = date("U");
		$add_log	= mysql_query("INSERT INTO $config->logs VALUES ('', '$now', '".$_SERVER['REMOTE_ADDR']."', '".$_SESSION['uid']."', 'prune bans', 'Pruned $i bans')") or die (mysql_error());
	}

	if ($j != 0) {
		$now = date("U");
		$k = $i - $j;
		$add_log	= mysql_query("INSERT INTO $config->logs VALUES ('', '$now', '".$_SERVER['REMOTE_ADDR']."', '".$_SESSION['uid']."', 'prune bans', 'Pruded $k bans and made $j bans permanent (max offenses reached)')") or die (mysql_error());
	}
}

//echo "Number of bans with $config->autopermban_count or more bans: $j<br>";
//echo "Number of bans moved to ban history table: $i<br>";


// Check how many (if any) bans are up for pruning

$resource	= mysql_query("SELECT COUNT(bid) AS prune_bans FROM $config->bans WHERE ban_created + ban_length*60 < UNIX_TIMESTAMP() AND ban_length != 0") or die(mysql_error());
$result		= mysql_fetch_object($resource);


/////////////////////////////////////////////////////////////////
//	Template parsing
/////////////////////////////////////////////////////////////////

$title			= "Prune bans";

// Section
$section = "prune";

$smarty = new dynamicPage;

$smarty->assign("meta","");
$smarty->assign("title",$title);
$smarty->assign("section",$section);
$smarty->assign("dir",$config->document_root);
$smarty->assign("this",$_SERVER['PHP_SELF']);
$smarty->assign("bans2prune",$result->prune_bans);

$smarty->display('main_header.tpl');
$smarty->display('prune_db.tpl');
$smarty->display('main_footer.tpl');

?>