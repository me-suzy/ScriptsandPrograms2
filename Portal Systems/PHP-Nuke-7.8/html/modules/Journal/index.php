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

startjournal($sitename,$user);

function last20($bgcolor1, $bgcolor2, $bgcolor3, $username) {
	global $prefix, $user_prefix, $db, $module_name;
	OpenTable();
	echo ("<div align=\"center\" class=title>"._20ACTIVE."</div><br>");
	echo ("<table align=center border=1 cellpadding=0 cellspacing=0>");
	echo ("<tr>");
	echo ("<td bgcolor=$bgcolor1 width=150>&nbsp;<strong>"._MEMBER."</strong> "._CLICKTOVIEW."</td>");
	echo ("<td bgcolor=$bgcolor1 width=70 align=center><strong>"._VIEWJOURNAL."</strong></td>");
	echo ("<td bgcolor=$bgcolor1 width=70 align=center><strong>"._MEMBERPROFILE."</strong></td>");
	if ($username == "") {
		echo "<td bgcolor=$bgcolor1 width=70 align=center><strong>"._CREATEACCOUNT2."</strong></td>";
	} else {
		if (is_active("Private_Messages")) {
			echo "<td bgcolor=$bgcolor1 width=70 align=center><strong>"._PRIVMSGJ."</strong></td>";
		}
	}
	echo "</tr>";
	$sql = "SELECT j.id, j.joid, j.nop, j.ldp, j.ltp, j.micro, u.user_id, u.username FROM ".$prefix."_journal_stats j, ".$user_prefix."_users u where u.username=j.joid ORDER BY 'ldp' DESC";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
		if ($dcount >= 21) {
			echo "</table>";
			CloseTable();
			journalfoot();
			die();
		} else {
			$dcount = $dcount + 1;
			print  ("<tr>");
			printf ("<td bgcolor=$bgcolor2>&nbsp;&nbsp;<a href=\"modules.php?name=$module_name&file=search&bywhat=aid&exact=1&forwhat=%s\">%s</a></td>", $row[joid], $row[joid]);
			printf ("<td bgcolor=$bgcolor2 align=center><div class=title><a href=\"modules.php?name=$module_name&file=search&bywhat=aid&exact=1&forwhat=%s\"><img src=\"modules/$module_name/images/binocs.gif\" border=0 alt=\""._VIEWJOURNAL2."\" title=\""._VIEWJOURNAL2."\"></a></td>", $row[joid], $row[joid]);
			printf ("<td bgcolor=$bgcolor2 align=center><a href=\"modules.php?name=Your_Account&op=userinfo&username=%s\"><img src=\"modules/$module_name/images/nuke.gif\" alt=\""._USERPROFILE2."\" title=\""._USERPROFILE2."\" border=0></a></td>", $row[joid], $row[joid], $row[joid]);
			if ($username == "") {
				print ("<td align=center bgcolor=$bgcolor2><a href=\"modules.php?name=Your_Account&op=new_user\"><img src=\"modules/$module_name/images/folder.gif\" border=0 alt=\""._CREATEACCOUNT."\" title=\""._CREATEACCOUNT."\"></a></td>");
			} else {
				if (is_active("Private_Messages")) {
					printf ("<td align=center bgcolor=$bgcolor2><a href=\"modules.php?name=Private_Messages&mode=post&u=$row[user_id]\"><img src='modules/$module_name/images/chat.gif' border='0' alt='"._PRIVMSGJ2."'></a></td>", $row[joid], $row[joid]);
				}
			}
			echo "</tr>";
		}
	}
	echo "</table>";
	CloseTable();
}


function all($bgcolor1,$bgcolor2,$bgcolor3,$sitename, $username) {
	global $prefix, $user_prefix, $db, $module_name;
	OpenTable();
	echo ("<div align=\"center\" class=title>"._ALPHABETICAL."</div><br>");
	echo ("<table align=center border=1 cellpadding=0 cellspacing=0>");
	echo ("<tr>");
	echo ("<td bgcolor=$bgcolor1 width=150>&nbsp;<strong>"._MEMBER."</strong> "._CLICKTOVIEW."</td>");
	echo ("<td bgcolor=$bgcolor1 width=70 align=center><strong>"._VIEWJOURNAL."</strong></td>");
	echo ("<td bgcolor=$bgcolor1 width=70 align=center><strong>"._MEMBERPROFILE."</strong></td>");
	if ($username == "") {
		echo ("<td bgcolor=$bgcolor1 width=70 align=center><strong>"._CREATEACCOUNT2."</strong></td>");
	} else {
		echo ("<td bgcolor=$bgcolor1 width=70 align=center><strong>"._PRIVMSGJ."</strong></td>");
	}
	echo ("</tr>");
	$sql = "SELECT j.id, j.joid, j.nop, j.ldp, j.ltp, j.micro, u.user_id FROM ".$prefix."_journal_stats j, ".$user_prefix."_users u where u.username=j.joid ORDER BY 'joid'";
	$result = $db->sql_query($sql);
	while ($row = $db->sql_fetchrow($result)) {
		print  ("<tr>");
		printf ("<td bgcolor=$bgcolor2>&nbsp;&nbsp;<a href=\"modules.php?name=$module_name&file=search&bywhat=aid&forwhat=%s\">%s</a></td>", $row[joid], $row[joid]);
		printf ("<td bgcolor=$bgcolor2 align=center><div class=title><a href=\"modules.php?name=$module_name&file=search&bywhat=aid&forwhat=%s\"><img src=\"modules/$module_name/images/binocs.gif\" border=0 alt=\""._VIEWJOURNAL2."\" title=\""._VIEWJOURNAL2."\"></a></td>", $row[joid], $row[joid]);
		printf ("<td bgcolor=$bgcolor2 align=center><a href=\"modules.php?name=Your_Account&op=userinfo&username=%s\"><img src=\"modules/$module_name/images/nuke.gif\" alt=\""._USERPROFILE2."\" title=\""._USERPROFILE2."\" border=0></a></td>", $row[joid], $row[joid], $row[joid]);
		if ($username == "") {
			print ("<td align=center bgcolor=$bgcolor2><a href=\"modules.php?name=Your_Account&op=new_user\"><img src=\"modules/$module_name/images/folder.gif\" border=0 alt=\""._CREATEACCOUNT."\" title=\""._CREATEACCOUNT."\"></a></td>");
		} elseif ($username != "" AND is_active("Private_Messages")) {
			printf ("<td align=center bgcolor=$bgcolor2><a href=\"modules.php?name=Private_Messages&mode=post&u=$row[user_id]\"><img src='modules/$module_name/images/chat.gif' border='0' alt='"._PRIVMSGJ2."'></a></td>", $row[aid], $row[aid]);
		}
		echo "</tr>";
	}
	echo "</table>";
	CloseTable();
}

echo "<br>";
OpenTable();
echo ("<div align=center> [ <a href=\"modules.php?name=$module_name&op=last\">"._20AUTHORS."</a> | <a href=\"modules.php?name=$module_name&op=all\">"._LISTALLJOURNALS."</a> | <a href=\"modules.php?name=$module_name&file=search&disp=showsearch\">"._SEARCHMEMBER."</a> ]</div>");
CloseTable();
echo "<br>";

switch($op) {
	case "last":
	last20($bgcolor1,$bgcolor2,$bgcolor3, $username);
	break;


	case "all":
	all($bgcolor1,$bgcolor2,$bgcolor3,$sitename, $username);
	break;


	default:
	last20($bgcolor1,$bgcolor2,$bgcolor3, $username);
	break;
}

journalfoot();

?>