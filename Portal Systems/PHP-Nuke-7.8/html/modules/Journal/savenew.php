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
$pdate = $ndate;
$ptime = $mtime;

if ($debug == "true") :
echo ("UserName:$username<br>SiteName: $sitename");
endif;

startjournal($sitename,$user);

echo "<br>";
OpenTable();
echo ("<div align=center class=title>"._ENTRYADDED."</div><br><br>");
echo ("<div align=center> [ <a href=\"modules.php?name=$module_name&file=edit\">"._RETURNJOURNAL."</a> ]</div>");
CloseTable();
$title = stripslashes(FixQuotes($title));
$bodytext = stripslashes(FixQuotes($bodytext));
$sql="INSERT INTO ".$prefix."_journal (jid,aid,title,bodytext,mood,pdate,ptime,status,mtime,mdate) VALUES ('','$username','$title','$bodytext','$mood','$pdate','$ptime','$status','$mtime','$ndate')";
$db->sql_query($sql);

update_points(1);

$sql = "SELECT * FROM ".$prefix."_journal_stats WHERE joid = '$username'";
$result = $db->sql_query($sql);
$row_count = $db->sql_numrows($result);
if ($row_count == 0):
$query = "INSERT INTO ".$prefix."_journal_stats (id,joid,nop,ldp,ltp,micro) VALUES ('','$username','1',now(),'$mtime',now())";
$db->sql_query($query);
else :
$row = $db->sql_fetchrow($result);
$nnop = $row[nop];
$nnnop = ($nnop + 1);
$micro = date(U);
$query = "UPDATE ".$prefix."_journal_stats SET nop='$nnnop', ldp='$ndate', ltp='$mtime' micro='$micro' WHERE joid='$username'";
$db->sql_query($query);
endif;

journalfoot();

?>