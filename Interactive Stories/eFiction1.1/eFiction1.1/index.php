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

function main($offset, $limit)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $itemsperpage, $columns, $numupdated, $dateformat, $newscomments;
	include ("header.php");
	$result = mysql_query("SELECT welcome, copyright from ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);

	//make a new TemplatePower object
	$tpl = new TemplatePower( "skins/$skin/index.tpl" );
	$tpl->assignInclude( "newsbox", "./skins/$skin/newsbox.tpl" );

	//let TemplatePower do its thing, parsing etc.
	$tpl->prepare();

	//assign a value to {name}
	$tpl->assign( "welcome", $settings[welcome] );
	$tpl->assign( "footer", $settings[copyright] );
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

	if (empty($offset) || $offset < 0)
		{
			$offset=0;
		}
		if (empty($index)) $index=0;
		$limit = $itemsperpage;

	include ("timefunctions.php");

	$result2 = mysql_query("SELECT *,DATE_FORMAT(time, '$datum - %h:%i%p') as date FROM ".$tableprefix."fanfiction_news ORDER BY nid DESC LIMIT $offset,$limit");
	$count =  mysql_query("SELECT count(nid) FROM ".$tableprefix."fanfiction_news ORDER BY nid DESC");
	list($numrows)= mysql_fetch_array($count);
	$index++;
	while($stories = mysql_fetch_array($result2))
		{

		//create a new number_row block
		$tpl->newBlock("newsbox");

		if($newscomments == "1")
		{
			$query = mysql_query("SELECT count(nid) as num FROM ".$tableprefix."fanfiction_comments WHERE nid = '$stories[nid]'");
			$numcomments = mysql_fetch_array($query);
		}
		
		//assign values
		$tpl->assign("newstitle"   , $stories[title] );
		$tpl->assign("newsstory"   , $stories[story] );
		$tpl->assign("newsauthor", $stories[author] );
		$tpl->assign("newsdate", $stories[date] );
		if($newscomments == "1")
			$tpl->assign("newscomments", "<a href=\"index.php?action=newsstory&nid=$stories[nid]\">" . $numcomments[num] . " "._COMMENTS."</a>");
		}

		$tpl->gotoBlock( "_ROOT" );
		$index++; /* Increment the line index by 1 */

		if ($numrows>$limit) {
		if ($offset>0)
		{
			$newspageprev .= '<a href="index.php?offset='.($offset-$limit).'">['._LESS.']</a> ';
		}
		else
			$newspageprev .=  '['._LESS.']';
		$totpages=ceil($numrows/$limit);
		$curpage=floor($offset/$limit)+1;
		if ($curpage<$totpages)
		{
			$newspagenext .=  '<a href="index.php?offset='.($offset+$limit).'">['._MORE.']</a>';
		}
		else
			$newspagenext .=  '['._MORE.']';
		}
	$tpl->assign("newspagenext", $newspagenext );
	$tpl->assign("newspageprev", $newspageprev );

	//May want to comment this out if it takes too much system resources

	$storyquery = mysql_query("SELECT COUNT(sid) as totals FROM ".$tableprefix."fanfiction_stories WHERE sid = psid AND validated = '1'");
	$storyresult = mysql_fetch_array($storyquery);

	$tpl->assign("totalstories", $storyresult[totals] );

	$authorquery = mysql_query("SELECT COUNT(uid) as totala FROM ".$tableprefix."fanfiction_authors");
	$authorresult = mysql_fetch_array($authorquery);

	$tpl->assign("totalauthors", $authorresult[totala] );

	include("blocks.php");

	$tpl->printToScreen();

}

function newsstory($nid, $submit, $uname, $comment, $del, $cid)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $itemsperpage, $columns, $numupdated, $userpenname, $adminloggedin;
	include ("header.php");
	$result = mysql_query("SELECT welcome, copyright from ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);

	//make a new TemplatePower object
	$tpl = new TemplatePower( "skins/$skin/default.tpl" );

	//let TemplatePower do its thing, parsing etc.
	$tpl->prepare();

	//assign a value to {name}
	$tpl->assign( "welcome", $settings[welcome] );
	$tpl->assign( "footer", $settings[copyright] );
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
	
	if($submit)
	{
		$comment = nl2br(strip_tags($comment, '<b><i><u><center><img><a><hr><p><ul><li><ol>'));
		
		mysql_query("INSERT INTO ".$tableprefix."fanfiction_comments (nid, uname, comment, time) VALUES ('$nid', '$uname', '$comment', now())");
	}
	if(($del == "1") && ($adminloggedin == "1"))
	{
		mysql_query("DELETE FROM ".$tableprefix."fanfiction_comments WHERE cid = '$cid'");
	}
	include ("timefunctions.php");
	$query = mysql_query("SELECT *,DATE_FORMAT(time, '$datum - %h:%i%p') as date FROM ".$tableprefix."fanfiction_news WHERE nid = '$nid'");
	$stories = mysql_fetch_array($query);
	
	$output .= "<br><b>$stories[title]</b><br>";
	$output .= "$stories[story]<br>";
	$output .= "<i>($stories[author] - $stories[date])</i><br><hr>";
	$output .= "<blockquote>";
	$query2 = mysql_query("SELECT *,DATE_FORMAT(time, '$datum - %h:%i%p') as date FROM ".$tableprefix."fanfiction_comments WHERE nid = '$nid' ORDER BY time");
	while($comments = mysql_fetch_array($query2))
	{
		$output .= "$comments[comment] - <i>$comments[uname] ($comments[date])</i>";
		if($adminloggedin == "1")
		{
			$output .= " [<a href=\"index.php?action=newsstory&cid=$comments[cid]&del=1&nid=$nid\">"._DELETE."</a>]";
		}
		$output .= "<br><br>";
	}
	if($_SESSION['loggedin'] == "1")
	{
		$output .= "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"index.php?action=newsstory\">";
		$output .= "<table align=\"center\"><tr><td><b>"._PENNAME.":</b></td><td>$userpenname<INPUT type=\"hidden\" name=\"uname\" value=\"$userpenname\"></td></tr>
		<tr><td><b>"._COMMENTS.":</b></td><td><TEXTAREA name=\"comment\" cols=\"35\" rows=\"6\"></TEXTAREA></td></tr>
		<tr><td><INPUT type=\"hidden\" name=\"nid\" value=\"$nid\"><INPUT name=\"submit\" type=\"submit\" value=\""._SUBMIT."\"></td></tr></table></form>";
	}
	else
	{
		$output .= "<br><center>"._MUSTBELOGGEDIN."</center>";
	}
	$output .= "</blockquote>";
	$tpl->assign("output"   , $output );
	
	$tpl->printToScreen();
}

switch ($action)
{

	case "newsstory":
		newsstory($nid, $submit, $uname, $comment, $del, $cid);
	break;
	
	default:
		main($offset, $limit);
		break;
}

?>