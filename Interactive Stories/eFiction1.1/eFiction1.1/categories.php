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

function main($catid, $parentcatid, $charlist1, $charlist2, $genrelist, $ratinglist, $warninglist, $go, $offset, $index, $optionslist)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $itemsperpage, $ratings, $itemsperpage, $reviewsallowed, $columns;
	include ("header.php");
	$result = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($result);

	//make a new TemplatePower object
	$tpl = new TemplatePower( "skins/$skin/categories.tpl" );
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

	$query = mysql_query("SELECT category FROM ".$tableprefix."fanfiction_categories WHERE catid = '$catid'");
	$thiscategory = mysql_fetch_array($query);

	$tpl->assign( "topcategorytitle", $thiscategory[category] );


	if((!$catid) && (!$parentcatid))
		$result2 = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_categories WHERE parentcatid = '-1' ORDER BY displayorder ASC");
	else if($parentcatid)
		$result2 = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_categories WHERE parentcatid = '$parentcatid' ORDER BY displayorder ASC");
	else
		$result2 = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_categories WHERE catid = '$catid' AND parentcatid = '$parentcatid' ORDER BY displayorder ASC");

	$count = 1;
	while($category = mysql_fetch_array($result2))
	{
		$tpl->newBlock("categoriesblock");
		$catimage = "skins/$skin/images/$category[image]";
		$categoryname = "<a href=\"categories.php?catid=$category[catid]&parentcatid=$category[catid]\">$category[category]</a>";
		//$query = mysql_query("SELECT count(sid) FROM ".$tableprefix."fanfiction_stories WHERE catid = '$category[catid]' AND sid = psid AND validated = '1'");
		//$numstories = mysql_fetch_array($query);
		$tpl->assign( "categorytitle", $categoryname );
		$tpl->assign( "categorydescription", $category[description] );
		//if($numstories[0] != "0")
		$tpl->assign( "numstories", "(" .$category[numitems]. ")" );
		$tpl->assign( "categoryimage", $catimage );
		if ($count == $columns)
		{
			$categorycolumn = "</tr><tr>";
			$tpl->assign( "categorycolumn", $categorycolumn );
			$count -= $columns;
		}
		$count++;
	}
	$tpl->gotoBlock( "_ROOT" );

	$charsquery1 = mysql_query("SELECT charname,charid FROM ".$tableprefix."fanfiction_characters WHERE catid = '$catid' ORDER BY charname");
	$charactermenu1 = "<select name=\"charlist1\">";
	$charactermenu1 .= "<option value=\"\">"._ALLCHARACTERS."</option>";
	while($chars1 = mysql_fetch_array($charsquery1))
	{
		$charactermenu1 .= "<option value=\"$chars1[charname]\"";
		if($charlist1 == $chars1[charname])
			$charactermenu1 .= " selected";
		$charactermenu1 .= ">$chars1[charname]</option>";
	}
	$charactermenu1 .= "</select>";

	$charsquery2 = mysql_query("SELECT charname,charid FROM ".$tableprefix."fanfiction_characters WHERE catid = '$catid' ORDER BY charname");
	$charactermenu2 = "<select name=\"charlist2\">";
	$charactermenu2 .= "<option value=\"\">"._ALLCHARACTERS."</option>";
	while($chars2 = mysql_fetch_array($charsquery2))
	{
		$charactermenu2 .= "<option value=\"$chars2[charname]\"";
		if($charlist2 == $chars2[charname])
			$charactermenu2 .= " selected";
		$charactermenu2 .= ">$chars2[charname]</option>";
	}
	$charactermenu2 .= "</select>";

	$genresquery = mysql_query("SELECT genre,gid FROM ".$tableprefix."fanfiction_genres ORDER BY genre");
	$genremenu = "<select name=\"genrelist\">";
	$genremenu .= "<option value=\"\">"._ALLGENRES."</option>";
	while($genres = mysql_fetch_array($genresquery))
	{
		$genremenu .= "<option value=\"$genres[genre]\"";
		if($genrelist == $genres[genre])
			$genremenu .= " selected";
		$genremenu .= ">$genres[genre]</option>";
	}
	$genremenu .= "</select>";

	$ratingsquery = mysql_query("SELECT rating,rid FROM ".$tableprefix."fanfiction_ratings");
	$ratingmenu = "<select name=\"ratinglist\">";
	$ratingmenu .= "<option value=\"\">"._ALLRATINGS."</option>";
	while($ratingresults = mysql_fetch_array($ratingsquery))
	{
		$ratingmenu .= "<option value=\"$ratingresults[rating]\"";
		if($ratinglist == $ratingresults[rating])
			$ratingmenu .= " selected";
		$ratingmenu .= ">$ratingresults[rating]</option>";
	}
	$ratingmenu .= "</select>";

	$warningsquery = mysql_query("SELECT warning,wid FROM ".$tableprefix."fanfiction_warnings ORDER BY warning");
	$numrows = mysql_num_rows($warningsquery);
	if($numrows != 0)
	{
		$warningmenu = "<select name=\"warninglist\">";
		$warningmenu .= "<option value=\"\">"._ALLWARNINGS."</option>";
		while($warnings = mysql_fetch_array($warningsquery))
		{
			$warningmenu .= "<option value=\"$warnings[warning]\"";
			if($warninglist == $warnings[warning])
				$warningmenu .= " selected";
			$warningmenu .= ">$warnings[warning]</option>";
		}
		$warningmenu .= "</select>";
	}

	$chaptersmenu = "<select name=\"optionslist\">";
	$chaptersmenu .= "<option value=\"all\"";
	if($optionslist == "all")
		$chaptersmenu .= " selected";
	$chaptersmenu .= ">"._ALLCHAPTERS."</option>";
	$chaptersmenu .= "<option value=\"first\"";
	if($optionslist == "first")
		$chaptersmenu .= " selected";
	$chaptersmenu .= ">"._FIRSTCHAPTERS."</option>";
	$chaptersmenu .= "<option value=\"sub\"";
	if($optionslist == "sub")
		$chaptersmenu .= " selected";
	$chaptersmenu .= ">"._SUBCHAPTERS."</option>";
	$chaptersmenu .= "</select>";

	if (empty($offset) || $offset < 0)
	{
		$offset=0;
	}
	if (empty($index)) $index=0;
	$limit = $itemsperpage;
	include ("timefunctions.php");
	if(isset($go))
	{
		
		$query = "select title, psid, sid, catid, numreviews, uid, summary, rr, rid, gid, wid, charid, completed,wordcount,DATE_FORMAT(date, '$datim')as date, DATE_FORMAT(updated, '$datim') as updated from ".$tableprefix."fanfiction_stories WHERE catid = '$catid'";
		$countquery = "select count(sid) from ".$tableprefix."fanfiction_stories WHERE catid = '$catid'";

		if($optionslist == "first")
		{
			$query = $query." AND sid = psid";
			$countquery = $countquery." AND sid = psid";
		}
		else if($optionslist == "sub")
		{
			$query = $query." AND sid != psid";
			$countquery = $countquery." AND sid != psid";
		}

		if($ratinglist != "")
		{
			$query = $query." AND rid = '$ratinglist'";
			$countquery = $countquery." AND rid = '$ratinglist'";
		}
		if(($charlist1 != "") || ($charlist2 != ""))
		{
		// characters are a little more complicated since order doesn't matter
			if($charlist1 == $charlist2)
			{
				$charlist2 = "";
			}
			if($charlist1 == "")
			{
				// I only care about 2
				$query = $query." AND charid LIKE '%$charlist2%'";
				$countquery = $countquery." AND charid LIKE '%$charlist2%'";
			}
			else if ($charlist2 == "")
			{
				// I only care about 1
				$query = $query." AND charid LIKE '%$charlist1%'";
				$countquery = $countquery." AND charid LIKE '%$charlist1%'";
			}
			else
			{
				// If I care about both
				$query = $query." AND charid LIKE '%$charlist1%' AND charid LIKE '%$charlist2%'";
				$countquery = $countquery." AND charid LIKE '%$charlist1%' AND charid LIKE '%$charlist2%'";
			}
		}
		if( $genrelist != "")
		{
			$query = $query." AND gid LIKE '%$genrelist%'";
			$countquery = $countquery." AND gid LIKE '%$genrelist%'";
		}
		
		if( $warninglist != "")
		{
			$query = $query." AND wid LIKE '%$warninglist%'";
			$countquery = $countquery." AND wid LIKE '%$warninglist%'";
		}

		$query = $query." AND validated = '1' ORDER BY updated DESC LIMIT $offset,$limit";
		$countquery = $countquery." AND validated = '1' ORDER BY updated";
		$result3 = mysql_query($query);

		
		$count = mysql_query($countquery);
		list($numrows)= mysql_fetch_array($count);
	}
	else
	{
		$result3 = mysql_query("SELECT title, psid, sid, catid, rr, numreviews, uid, summary, rid, gid, wid, charid, completed,wordcount,DATE_FORMAT(date, '$datim')as date, DATE_FORMAT(updated, '$datim') as updated FROM ".$tableprefix."fanfiction_stories WHERE catid = '$catid' AND sid = psid AND validated = '1' ORDER BY updated DESC LIMIT $offset,$limit");
		$count =  mysql_query("SELECT count(sid) FROM ".$tableprefix."fanfiction_stories WHERE catid = '$catid' AND sid = psid AND validated = '1'");
		list($numrows)= mysql_fetch_array($count);
	}

	$index++;

	while($stories = mysql_fetch_array($result3))
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
	if(($numrows == "0") && ($catid != ""))
	{
		$nostories = ""._NOSTORIES."";
		$tpl->assign("output"   , $nostories );
	}
	else if($numrows != "0")
	{

		$sortbegin = "<form style=\"margin:0\" method=\"POST\" enctype=\"multipart/form-data\" action=\"categories.php?catid=$catid&parentcatid=$parentcatid\">";
		$sortend = "<INPUT type=\"submit\" name=\"go\" value=\""._GO."\"><INPUT type=\"hidden\" name=\"catid\" value=\"$catid\"></form>";

		$tpl->assign("charactermenu1"   , $charactermenu1 );
		$tpl->assign("charactermenu2"   , $charactermenu2 );
		$tpl->assign("genremenu"   , $genremenu );
		$tpl->assign("ratingmenu"   , $ratingmenu );
		$tpl->assign("warningmenu"   , $warningmenu );
		$tpl->assign("chaptersmenu"   , $chaptersmenu );
		$tpl->assign("sortbegin"   , $sortbegin );
		$tpl->assign("sortend"   , $sortend );

		$index++; /* Increment the line index by 1 */

		if ($numrows>$limit) {
		if ($offset>0)
		{
			$pagelinks .= '<a href="categories.php?catid='.$catid.'&parentcatid='.$parentcatid.'&offset='.($offset-$limit).'">['._PREVIOUS.']</a> ';
		}
		else
			$pagelinks .=  '['._PREVIOUS.'] ';
		$totpages=ceil($numrows/$limit);
		$curpage=floor($offset/$limit)+1;
		for ($i=0;$i<$totpages;$i++)
		{
			if ($i+1!=$curpage)
				$pagelinks .=  '<a href="categories.php?catid='.$catid.'&parentcatid='.$parentcatid.'&action=recent&offset='.($i*$limit).'">'.($i+1).'</a> ';
			else $pagelinks .=  ($i+1).' ';
		}
		if ($curpage<$totpages)
		{
			$pagelinks .=  '<a href="categories.php?catid='.$catid.'&parentcatid='.$parentcatid.'&action=recent&offset='.($offset+$limit).'">['._NEXT.']</a>';
		}
		else
			$pagelinks .=  '['._NEXT.']';
		}
		$tpl->assign("pagelinks"   , $pagelinks );
	}
	$tpl->printToScreen();

}

switch ($action)
{

	default:
		main($catid, $parentcatid, $charlist1, $charlist2, $genrelist, $ratinglist, $warninglist, $go, $offset, $index, $optionslist);
		break;
}

?>