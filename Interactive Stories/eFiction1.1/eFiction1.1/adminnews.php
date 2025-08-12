<?php

// ----------------------------------------------------------------------
// eFiction
// Copyright (C) 2003 by Rebecca Smallwood.
// http://orodruin.sourceforge.net/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

function news($submit, $title, $author, $story, $offset, $index)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level;
	include ("adminheader.php");
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	$tpl->prepare();
	$tpl->assign( "footer", footer() );
	$tpl->assign( "logo", $logo );
	$tpl->assign( "home", $home );
	$tpl->assign( "recent", $recent );
	$tpl->assign( "catslink", $catslink );
	$tpl->assign( "authors", $authors );
	$tpl->assign( "help", $help );
	$tpl->assign( "search", $search );
	$tpl->assign( "login", $login );
	$tpl->assign( "adminarea", $adminarea );
	$tpl->assign( "titles", $titles );
	$tpl->assign( "logout", $logout );
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1') && ($level != '2') && ($level != '3')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._NEWSCENTER."</h4></center>";
		if($submit)
		{
			mysql_query("INSERT INTO ".$tableprefix."fanfiction_news (title, author, story, time) VALUES ('$title', '$author', '$story', now())");
			$output .= "<center>"._NEWSADDED."</center>";
		}
		else
		{
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=news\">
				<table align=\"center\"><tr><td colspan=\"2\"><b>"._ADDNEWS."</b></td></tr>
				<tr><td>"._AUTHOR.": </td><td><INPUT name=\"author\"></td></tr>
				<tr><td>"._TITLE.": </td><td><INPUT name=\"title\"></td></tr>
				<tr><td>"._STORYTEXT.": </td><td><TEXTAREA name=\"story\" COLS=\"45\" ROWS=\"6\"></TEXTAREA></td></tr>
				<tr><td colspan=\"2\" align=\"center\"><INPUT type=\"submit\" name=\"submit\" value=\""._SUBMIT."\"></td></tr></table></form>";
			$output .= "<hr>";

			if (empty($offset) || $offset < 0)
			{
				$offset=0;
			}
			if (empty($index)) $index=0;
			$limit = 10;

			$count = mysql_query("select count(*) from ".$tableprefix."fanfiction_news");
			list($numrows)= mysql_fetch_array($count);
			$index++;
			$result = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_news ORDER BY time DESC LIMIT $offset,$limit");
			$output .= "<table align=\"center\" class=\"tblborder\" cellspacing=\"0\" cellpadding=\"3\"><tr><td class=\"tblborder\"><b>"._TITLE."</b></td><td class=\"tblborder\"><b>"._AUTHOR."</b></td><td class=\"tblborder\"><b>"._DATE."</b></td><td class=\"tblborder\"><b>"._OPTIONS."</b></td></tr>";
			while($news = mysql_fetch_array($result))
			{
				$output .= "<tr><td class=\"tblborder\">$news[title]</td><td class=\"tblborder\">$news[author]</td></td><td class=\"tblborder\">$news[time]</td><td class=\"tblborder\"><a href=\"admin.php?action=newsedit&nid=$news[nid]\">"._EDIT."</a> | <a href=\"admin.php?action=newsdelete&nid=$news[nid]\">"._DELETE."</a></td></tr>";
			}

			$index++; /* Increment the line index by 1 */

			$output .= "<tr><td colspan=\"4\" align=\"center\" class=\"tblborder\">";
			if ($numrows>$limit) {
			if ($offset>0)
			{
				$output .= '<a href="admin.php?action=news&offset='.($offset-10).'">['._PREVIOUS.']</a> ';
			}
			else
				$output .=  '['._PREVIOUS.'] ';
			$totpages=ceil($numrows/$limit);
			$curpage=floor($offset/$limit)+1;
			for ($i=0;$i<$totpages;$i++)
			{
				if ($i+1!=$curpage)
					$output .=  '<a href="admin.php?action=news&offset='.($i*10).'">'.($i+1).'</a> ';
				else $output .=  ($i+1).' ';
			}
			if ($curpage<$totpages)
			{
				$output .=  '<a href="admin.php?action=news&offset='.($offset+10).'">['._NEXT.']</a>';
			}
			else
				$output .=  '['._NEXT.']';
			}

			$output .= "</td></tr></table>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function newsedit($nid, $author, $title, $story, $submit)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level;
	include ("adminheader.php");
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	$tpl->prepare();
	$tpl->assign( "footer", footer() );
	$tpl->assign( "logo", $logo );
	$tpl->assign( "home", $home );
	$tpl->assign( "recent", $recent );
	$tpl->assign( "catslink", $catslink );
	$tpl->assign( "authors", $authors );
	$tpl->assign( "help", $help );
	$tpl->assign( "search", $search );
	$tpl->assign( "login", $login );
	$tpl->assign( "adminarea", $adminarea );
	$tpl->assign( "titles", $titles );
	$tpl->assign( "logout", $logout );
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1') && ($level != '2') && ($level != '3')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._EDITNEWS."</h4></center>";
		if($submit)
		{
			mysql_query("UPDATE ".$tableprefix."fanfiction_news SET title = '$title', author = '$author', story = '$story' WHERE nid = '$nid'");
			$output .= "<center>"._NEWSEDITED."</center>";
		}
		else
		{
			$result = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_news WHERE nid = '$nid'");
			$news = mysql_fetch_array($result);
			$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"admin.php?action=newsedit\">
			<table align=\"center\">
			<tr><td>"._AUTHOR.": </td><td><INPUT name=\"author\" value=\"$news[author]\"></td></tr>
			<tr><td>"._TITLE.": </td><td><INPUT name=\"title\" value=\"$news[title]\"></td></tr>
			<tr><td>"._STORYTEXT.": </td><td><TEXTAREA name=\"story\" COLS=\"45\" ROWS=\"6\">$news[story]</TEXTAREA></td></tr>
			<tr><td colspan=\"2\" align=\"center\"><INPUT type=\"hidden\" name=\"nid\" value=\"$news[nid]\"><INPUT type=\"submit\" name=\"submit\" value=\""._SUBMIT."\"></td></tr></table></form>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

function newsdelete($nid, $delete)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $level;
	include ("adminheader.php");
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );
	$tpl->prepare();
	$tpl->assign( "footer", footer() );
	$tpl->assign( "logo", $logo );
	$tpl->assign( "home", $home );
	$tpl->assign( "recent", $recent );
	$tpl->assign( "catslink", $catslink );
	$tpl->assign( "authors", $authors );
	$tpl->assign( "help", $help );
	$tpl->assign( "search", $search );
	$tpl->assign( "login", $login );
	$tpl->assign( "adminarea", $adminarea );
	$tpl->assign( "titles", $titles );
	$tpl->assign( "logout", $logout );
	if (($_SESSION['adminloggedin'] != "1") || (($level != '1') && ($level != '2') && ($level != '3')))
	{
		$output .= ""._NOTALLOWED."<BR><BR>";
	}
	else
	{
		$output .= adminmenu();
		$output .= "<center><h4>"._DELETENEWS."</h4></center>";
		if($delete == "yes")
		{
			mysql_query("DELETE FROM ".$tableprefix."fanfiction_news where nid = '$nid'");
			$output .= "<center>"._NEWSDELETED."</center>";
		}
		else if ($delete == "no")
		{
			$output .= "<center>"._NEWSNOTDEL."</center>";
		}
		else
		{
			$output .= "<center>"._NEWSSUREDEL."<br><br>";
			$output .= "[ <a href=\"admin.php?action=newsdelete&delete=yes&nid=$nid\">"._YES."</a> | <a href=\"admin.php?action=newsdelete&delete=no\">"._NO."</a> ]</center>";
		}
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}

?>