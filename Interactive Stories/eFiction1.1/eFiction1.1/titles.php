<?php

// ----------------------------------------------------------------------
// Fanfiction Program
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

function main()
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $itemsperpage, $reviewsallowed;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);

	//make a new TemplatePower object
	$tpl = new TemplatePower( "skins/$skin/search.tpl" );
	$tpl->assignInclude( "storyblock", "./skins/$skin/storyblock.tpl" );

	 //let TemplatePower do its thing, parsing etc.
	$tpl->prepare();

	 //assign a value to {name}
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

	$output .= "<center><h4>"._VIEWTITLES."</h4>";

		$links = array( A, B, C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z, _OTHER );
		foreach( $links as $link )
		{
			// Build a link that calls a function with ($link and 1 (page number) )
			$output .= "<a href=\"titles.php?action=viewlist&let=$link\">$link</a> ";
		}
		$output .= "</center><br>";

		if (empty($offset) || $offset < 0)
		{
			$offset=0;
		}
		if (empty($index)) $index=0;
		$limit = $itemsperpage;

		$count = mysql_query("select count(sid) from ".$tableprefix."fanfiction_stories WHERE title LIKE 'a%' AND sid=psid AND validated = '1'");
		list($numrows)= mysql_fetch_array($count);

		include ("timefunctions.php");
		
		$result2 = mysql_query("SELECT title, psid, sid, catid, numreviews, uid, summary, rid, gid, wid, charid, completed,wordcount,DATE_FORMAT(date, '$datim')as date, DATE_FORMAT(updated, '$datim') as updated FROM ".$tableprefix."fanfiction_stories WHERE title LIKE 'a%' AND sid=psid AND validated = '1' ORDER BY title LIMIT $offset,$limit");
		$index++;
		while($stories = mysql_fetch_array($result2))
		{

			$tpl->newBlock("storyblock");

			include("storyblock.php");

			$summary = stripslashes($stories[summary]);
			$tpl->assign("title"   , $title );
			$tpl->assign("author"   , $author );
			$tpl->assign("summary"   , $summary );
			$tpl->assign("rating"   , $stories[rid] );
			$tpl->assign("genres"   , $stories[gid] );
			$tpl->assign("warnings"   , $warning );
			$tpl->assign("characters"   , $stories[charid] );
			$tpl->assign("category"   , $categorylinks );
			$tpl->assign("completed"   , $completed );
			$tpl->assign("roundrobin"   , $roundrobin );
			$tpl->assign("ratingpics"   , $ratingpics );
			$tpl->assign("reviews"   , $reviews );
			$tpl->assign("numchapters"   , $numchapters[chapters] );
			$tpl->assign("updated"   , $stories[updated] );
			$tpl->assign("published"   , $stories[date] );
			$tpl->assign("wordcount"   , $numchapters[words] );
			$tpl->assign("numreviews"   , $numreviews );
		}
	$tpl->gotoBlock( "_ROOT" );
	$index++; /* Increment the line index by 1 */

	$output .= "<br><center>";
	if ($numrows>$limit) {
	if ($offset>0) {
	$output .= "<a href=\"titles.php?action=viewlist&let=a&offset=".($offset-$limit)."\">["._PREVIOUS."]</a> ";
	} else $output .= "["._PREVIOUS."]";
	$totpages=ceil($numrows/$limit);
	$curpage=floor($offset/$limit)+1;
	for ($i=0;$i<$totpages;$i++) {
	if ($i+1!=$curpage) $output .= "<a href=\"titles.php?action=viewlist&let=a&offset=".($i*$limit)."\">".($i+1)."</a> ";
	else $output .= ($i+1).' ';
	}
	if ($curpage<$totpages) {
	$output .= "<a href=\"titles.php?action=viewlist&let=a&offset=".($offset+$limit)."\">["._NEXT."]</a>";
	} else $output .= "["._NEXT."]";
	}

	$output .= "</center>";

	$tpl->assign( "output", $output );
	$tpl->printToScreen();

}

function viewlist($let, $offset)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $itemsperpage;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);

	//make a new TemplatePower object
	$tpl = new TemplatePower( "skins/$skin/search.tpl" );
	$tpl->assignInclude( "storyblock", "./skins/$skin/storyblock.tpl" );

	 //let TemplatePower do its thing, parsing etc.
	$tpl->prepare();

	 //assign a value to {name}
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

	$output .= "<center><h4>"._VIEWTITLES." -- $let</h4>";

		$links = array( A, B, C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z, _OTHER );
		foreach( $links as $link )
		{
			// Build a link that calls a function with ($link and 1 (page number) )
			$output .= "<a href=\"titles.php?action=viewlist&let=$link\">$link</a> ";
		}
		$output .= "</center><br>";

		if (empty($offset) || $offset < 0)
		{
			$offset=0;
		}
		if (empty($index)) $index=0;
		$limit = $itemsperpage;

		if($let == "Other")
		{
			$count = mysql_query("select count(sid) from ".$tableprefix."fanfiction_stories WHERE title REGEXP '^[^a-z]' AND sid=psid AND validated = '1'");
		}
		else
		{
			$count = mysql_query("select count(sid) from ".$tableprefix."fanfiction_stories WHERE title LIKE '$let%' AND sid=psid AND validated = '1'");
		}
		list($numrows)= mysql_fetch_array($count);

		
		include ("timefunctions.php");
		
		if($let == "Other")
		{
			$result2 = mysql_query("SELECT title, psid, sid, catid, numreviews, uid, summary, rid, gid, wid, charid, completed,wordcount,DATE_FORMAT(date, '$datim')as date, DATE_FORMAT(updated, '$datim') as updated FROM ".$tableprefix."fanfiction_stories WHERE title REGEXP '^[^a-z]' AND sid=psid AND validated = '1' ORDER BY title LIMIT $offset,$limit");
		}
		else
		{
			$result2 = mysql_query("SELECT title, psid, sid, catid, numreviews, uid, summary, rid, gid, wid, charid, completed,wordcount,DATE_FORMAT(date, '$datim')as date, DATE_FORMAT(updated, '$datim') as updated FROM ".$tableprefix."fanfiction_stories WHERE title LIKE '$let%' AND sid=psid AND validated = '1' ORDER BY title LIMIT $offset,$limit");
		}
		$index++;
		while($stories = mysql_fetch_array($result2))
		{

			$tpl->newBlock("storyblock");

			include("storyblock.php");

			$summary = stripslashes($stories[summary]);
			$tpl->assign("title"   , $title );
			$tpl->assign("author"   , $author );
			$tpl->assign("summary"   , $summary );
			$tpl->assign("rating"   , $stories[rid] );
			$tpl->assign("genres"   , $stories[gid] );
			$tpl->assign("warnings"   , $warning );
			$tpl->assign("characters"   , $stories[charid] );
			$tpl->assign("category"   , $categorylinks );
			$tpl->assign("completed"   , $completed );
			$tpl->assign("roundrobin"   , $roundrobin );
			$tpl->assign("ratingpics"   , $ratingpics );
			$tpl->assign("reviews"   , $reviews );
			$tpl->assign("numchapters"   , $numchapters[chapters] );
			$tpl->assign("updated"   , $stories[updated] );
			$tpl->assign("published"   , $stories[date] );
			$tpl->assign("wordcount"   , $numchapters[words] );
			$tpl->assign("numreviews"   , $numreviews );
		}
	$tpl->gotoBlock( "_ROOT" );

	$index++; /* Increment the line index by 1 */

	$output .= "<br><center>";
	if ($numrows>$limit) {
	if ($offset>0) {
	$output .= "<a href=\"titles.php?action=viewlist&let=$let&offset=".($offset-$limit)."\">["._PREVIOUS."]</a> ";
	} else $output .= "["._PREVIOUS."]";
	$totpages=ceil($numrows/$limit);
	$curpage=floor($offset/$limit)+1;
	for ($i=0;$i<$totpages;$i++) {
	if ($i+1!=$curpage) $output .= "<a href=\"titles.php?action=viewlist&let=$let&offset=".($i*$limit)."\">".($i+1)."</a> ";
	else $output .= ($i+1).' ';
	}
	if ($curpage<$totpages) {
	$output .= "<a href=\"titles.php?action=viewlist&let=$let&offset=".($offset+$limit)."\">["._NEXT."]</a>";
	} else $output .= "["._NEXT."]";
	}

	$output .= "</center>";
	$tpl->assign( "output", $output );
	$tpl->printToScreen();
}


switch ($action)
{

	case "viewlist":
		viewlist($let, $offset);
	break;

	default:
		main();
		break;
}

?>