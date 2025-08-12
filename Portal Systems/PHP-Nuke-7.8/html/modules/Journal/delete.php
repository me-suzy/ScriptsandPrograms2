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

if (!eregi("modules.php", $_SERVER['PHP_SELF'])) {
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
echo ("<br>");
OpenTable();
echo ("<div align=center class=title><strong>"._ABOUTTODELETE."</strong><br><br><img src=\"modules/$module_name/images/trash.gif\">&nbsp;&nbsp;&nbsp;<img src=\"modules/$module_name/images/trash.gif\">&nbsp;&nbsp;&nbsp;<img src=\"modules/$module_name/images/trash.gif\"></div>");
echo ("<br><div align=center>"._SUREDELJOURNAL."<br><br>[ <a href=\"modules.php?name=$module_name&file=deleteyes&jid=$jid\">"._YES."</a> | <a href=\"modules.php?name=$module_name&file=edit\">"._NO."</a> ]</div><br><br>");
echo ("<div align=center>"._YOUCANTSAVE."</div>");
CloseTable();

journalfoot();

?>