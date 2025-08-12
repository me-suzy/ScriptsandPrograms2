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
$jid = intval($jid);
if ($debug == "true") :
echo ("UserName:$username<br>SiteName: $sitename<br>JID: $jid");
endif;

startjournal($sitename,$user);

echo "<br>";
OpenTable();
echo ("<div align=center class=title>"._EDITJOURNAL."</div><br>");
echo ("<div align=center> [ <a href=\"modules.php?name=$module_name&file=add\">"._ADDENTRY."</a> | <a href=\"modules.php?name=$module_name&file=edit&op=last\">"._YOURLAST20."</a> | <a href=\"modules.php?name=$module_name&file=edit&op=all\">"._LISTALLENTRIES."</a> ]</div>");
CloseTable();
echo "<br>";

OpenTable();
$sql = "SELECT * FROM ".$prefix."_journal WHERE jid = '$jid'";
$result = $db->sql_query($sql);
while ($row = $db->sql_fetchrow($result)) {
	if ($username != $row[aid]):
	echo ("<br>");
	openTable();
	echo ("<div align=center>"._NOTYOURS2."</div>");
	closeTable();
	journalfoot();
	die();
	endif;
	print  ("<form action='modules.php?name=$module_name&file=edit' method='post'>");
	print  ("<input type='hidden' name='edit' value='1'>");
	print  ("<input type='hidden' name='jid' value='$jid'>");
	print  ("<table align=center border=0>");
	print  ("<tr>");
	print  ("<td align=right valign=top><strong>"._TITLE.": </strong></td>");
	printf ("<td valign=top><input type='text' value='%s' size=50 maxlength=80 name='title'></td>", $row[title]);
	print  ("</tr>");
	print  ("<tr>");
	print  ("<td align=right valign=top><strong>"._BODY.": </strong></td>");
	printf ("<td valign=top><textarea name='bodytext' wrap=virtual cols=75 rows=10>%s</textarea><br>"._WRAP."</td>", $row[bodytext]);
	print  ("</tr>");
	print  ("<tr>");
	print  ("<td align=right valign=top><strong>"._LITTLEGRAPH.": </strong><br>"._OPTIONAL."</td>");
	echo "<td valign=top><table cellpadding=3><tr>";
	$tempcount = 0;
	$direktori = "modules/$module_name/images/moods";
	$handle=opendir($direktori);
	while ($file = readdir($handle)) {
		$filelist[] = $file;
	}
	asort($filelist);
	while (list ($key, $file) = each ($filelist)) {
		ereg(".gif|.jpg",$file);
		if ($file == "." || $file == "..") {
			$a=1;
		} else {
			if ($file == $row[mood]) {
				$checked = "checked";
			} else {
				$checked = "";
			}
			if ($tempcount == 6):
			echo "</tr><tr>";
			echo "<td><input type='radio' name='mood' value='$file' $checked></td><td><img src=\"modules/$module_name/images/moods/$file\" alt=\"$file\" title=\"$file\"></td>";
			$tempcount = 0;
			else :
			echo "<td><input type='radio' name='mood' value='$file' $checked></td><td><img src=\"modules/$module_name/images/moods/$file\" alt=\"$file\" title=\"$file\"></td>";
			endif;
			$tempcount = $tempcount + 1;
		}
	}
	echo "</tr></table>";
	print  ("</td>");
	print  ("</tr>");
	print  ("<tr>");
	print  ("<td align=right valign=top><strong>"._PUBLIC.": </strong></td>");
	print  ("<td align=left valign=top>");
	print  ("<select name='status'>");
	if ($row[status] == 'yes'):
	print  ("<option value=\"yes\" SELECTED>"._YES."</option>");
	else :
	print  ("<option value=\"yes\">"._YES."</option>");
	endif;
	if ($row[status] == 'no'):
	print  ("<option value=\"no\" SELECTED>"._NO."</option>");
	else :
	print  ("<option value=\"no\">"._NO."</option>");
	endif;
	print  ("</select>");
	print  ("</td>");
	print  ("</tr>");
	print  ("<td colspan=2 align=center><input type='submit' name='submit' value='"._MODIFYENTRY."'><br><br>"._TYPOS."</td>");
	print  ("</tr>");
	print  ("</table>");
	print  ("</form>");
}

CloseTable();
journalfoot();

?>