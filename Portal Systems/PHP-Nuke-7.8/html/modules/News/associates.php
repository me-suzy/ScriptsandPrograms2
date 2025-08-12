<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2005 by Francisco Burzi                                */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!defined('MODULE_FILE')) {
	die("You can't access this file directly...");
}

require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
get_lang($module_name);
$sid = intval($sid);
$arow = $db->sql_fetchrow($db->sql_query("SELECT associated FROM ".$prefix."_stories WHERE sid='$sid'"));

if ($arow[associated] != "") {
	OpenTable();
	echo "<center><b>"._ASSOTOPIC."</b><br><br>";
	$asso_t = explode("-",$arow[associated]);
	for ($i=0; $i<sizeof($asso_t); $i++) {
		if ($asso_t[$i] != "") {
			$atop = $db->sql_fetchrow($db->sql_query("SELECT topicimage, topictext from ".$prefix."_topics WHERE topicid='$asso_t[$i]'"));
			echo "<a href=\"modules.php?name=$module_name&new_topic=$asso_t[$i]\"><img src=\"$tipath$atop[topicimage]\" border=\"0\" hspace=\"10\" alt=\"$atop[topictext]\" title=\"$atop[topictext]\"></a>";
		}
	}
	echo "</center>";
	CloseTable();
	echo "<br>";
}

?>