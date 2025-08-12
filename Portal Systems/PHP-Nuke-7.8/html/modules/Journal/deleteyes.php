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

$pagetitle = "- "._USERJOURNAL."";
include("header.php");
include("modules/$module_name/functions.php");

cookiedecode($user);
$username = $cookie[1];

startjournal($sitename,$user);

#####################################################
# Check to see if the current username matches the  #
# the name of the author recorded in the database.  #
# If not - inform them that there is a problem and  #
# to check their current username.		    #
#####################################################

$jid = intval($jid);
$sql = "SELECT * FROM ".$prefix."_journal WHERE jid = '$jid'";
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result)) {
	$owner = $row[aid];
	if ($owner != $username):
	openTable();
	echo ("<div align=center>"._YOUWRONG."</div>");
	closeTable();
	echo ("<br>");
	journalfoot();
	die();
	endif;
	$sql = "DELETE FROM ".$prefix."_journal WHERE jid = '$jid'";
	$db->sql_query($sql);
	$sql = "DELETE FROM ".$prefix."_journal_comments WHERE rid = '$jid'";
	$db->sql_query($sql);
	echo ("<br>");
	openTable();
	echo ("<div align=center>"._ENTRYREMOVED."<br><br>");
	echo ("<a href=\"modules.php?name=$module_name&file=edit\">"._RETURNJOURNAL."</a></div>");
	closeTable();
}

journalfoot();

?>