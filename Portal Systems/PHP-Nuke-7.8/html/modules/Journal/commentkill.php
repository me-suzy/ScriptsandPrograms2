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

$pagetitle = "- "._USERSJOURNAL."";

include("header.php");
include("modules/$module_name/functions.php");

cookiedecode($user);
$username = $cookie[1];

if ($debug == "true") :
echo ("UserName:$username<br>SiteName: $sitename");
endif;

startjournal($sitename,$user);
$onwhat = intval($onwhat);
$sql = "DELETE FROM ".$prefix."_journal_comments WHERE cid = '$onwhat'";
$db->sql_query($sql);

echo "<br>";
openTable();
echo ("<div align=center>"._COMMENTDELETED."<br><br>");
echo ("[ <a href=\"modules.php?name=$module_name&file=display&jid=$ref\">"._RETURNJOURNAL."</a> ]</div>");
closeTable();

journalfoot();

?>