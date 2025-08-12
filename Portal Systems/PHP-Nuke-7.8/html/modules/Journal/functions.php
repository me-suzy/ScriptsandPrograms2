<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2005 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* Based on Atomic Journal                                              */
/* Copyright (c) by Trevor Scott                                        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!defined('MODULE_FILE')) {
	die ("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);

$debug = "false";

function journalfoot() {
	include("footer.php");
}

function startjournal($sitename, $user) {
	global $module_name;
	if (is_user($user)) {
		$j_user1 = "<center>[ <a href=\"modules.php?name=$module_name\">"._JOURNALDIR."</a> | <a href=\"modules.php?name=$module_name&file=edit\">"._YOURJOURNAL."</a> ]</center>";
		$j_user2 = "";
	} else {
		$j_user1 = "<center>[ <a href=\"modules.php?name=$module_name\">"._JOURNALDIR."</a> | <a href=\"modules.php?name=Your_Account&op=new_user\">"._CREATEACCOUNT."</a> ]</center>";
		$j_user2 = "<br><center><font class=\"tiny\">"._MEMBERSCAN."</font></center>";
	}
	title("$sitename: "._USERSJOURNAL."");
	if (is_user($user)) {
		include("modules/Your_Account/navbar.php");
		OpenTable();
		nav();
		CloseTable();
		echo "<br>";
	}
	OpenTable();
	echo "<center><img src=modules/$module_name/images/bgimage.gif><br><font class=title><b>"._USERSJOURNAL."</b></font></center>";
	echo "$j_user1";
	echo "$j_user2";
	CloseTable();
}

?>