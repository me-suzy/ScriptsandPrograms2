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

if ($debug == "true") :
echo ("UserName:$username<br>SiteName: $sitename");
endif;

startjournal($sitename,$user);

function dropcomment($username,$onwhat,$mtime,$ndate) {
	global $module_name;
	echo "<br>";
	OpenTable();
	echo ("<div align=center class=title>"._LEAVECOMMENT."</div><br><br>");
	echo ("<form action='modules.php?name=$module_name&file=commentsave' method='post'><input type='hidden' name='rid' value='$onwhat'>");
	echo ("<div align=center>"._COMMENTBOX.":<br><textarea name=\"comment\" wrap=virtual cols=70 rows=15></textarea><br><br><input type='submit' name='submit' value='"._POSTCOMMENT."'></div>");
	echo ("</form><br>");
	echo ("<center>"._COMMENTSNOTE."</center>");
	CloseTable();
}

if (!is_user($user)) :
echo ("<br>");
openTable();
echo ("<div align=center>"._YOUMUSTBEMEMBER."<br></div>");
closeTable();
journalfoot();
die();
else:
dropcomment($username,$onwhat,$mtime,$ndate);
endif;

journalfoot();

?>