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
$htime = date(h);
$mtime = date(i);
$ntime = date(a);
$mtime = "$htime:$mtime $ntime";
$mdate = date(m);
$ddate = date(d);
$ydate = date(Y);
$ndate = "$mdate-$ddate-$ydate";

startjournal($sitename,$user);

$sql="INSERT INTO ".$prefix."_journal_comments VALUES ('','$rid','$username','$comment','$ndate','$mtime')";
$db->sql_query($sql);
update_points(2);
echo ("<br>");

openTable();
echo ("<div align=center>"._COMMENTPOSTED."<br><br>");
echo ("<a href=\"modules.php?name=$module_name&file=display&jid=$rid\">"._RETURNJOURNAL2."</a><br><br><div class=title>"._THANKS."</div></div>");
closeTable();

journalfoot();

?>