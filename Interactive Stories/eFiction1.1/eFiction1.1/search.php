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

function main($submit, $searchtype, $searchterm, $searchkind, $catid, $gid, $wid, $rid, $charname)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $store;
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
	$output .= "<center><h4>"._SEARCH."</h4></center>";
	$tpl->assign( "output", $output );
	
	include ("timefunctions.php");
	
	if($searchkind)
	{
		if($searchkind == "simple")
		{
			if($searchtype == "penname")
			{
				$authorquery = mysql_query("SELECT uid,penname FROM ".$tableprefix."fanfiction_authors WHERE penname LIKE '%$searchterm%'");
				$author = mysql_fetch_array($authorquery);
				if($author[penname] != "")
				{
					$result3 = mysql_query("SELECT title, psid, sid, catid, numreviews, uid, summary, rid, gid, wid, charid, completed,wordcount,DATE_FORMAT(date, '$datim')as date, DATE_FORMAT(updated, '$datim') as updateddate FROM ".$tableprefix."fanfiction_stories WHERE uid = '$author[uid]' AND validated = '1' ORDER BY title");
					$numrows = mysql_num_rows($result3);
				}
			}
			if($searchtype == "storytitle")
			{
				$result3 = mysql_query("SELECT title, psid, sid, rr, catid, numreviews, uid, summary, rid, gid, wid, charid, completed,wordcount,DATE_FORMAT(date, '$datim')as date, DATE_FORMAT(updated, '$datim') as updateddate FROM ".$tableprefix."fanfiction_stories WHERE title LIKE '%$searchterm%' AND psid = sid AND validated = '1' ORDER BY title");
			}
			if($searchtype == "chaptertitle")
			{
				$result3 = mysql_query("SELECT title, psid, sid, rr, catid, numreviews, uid, summary, rid, gid, wid, charid, completed,wordcount,DATE_FORMAT(date, '$datim')as date, DATE_FORMAT(updated, '$datim') as updateddate FROM ".$tableprefix."fanfiction_stories WHERE chapter LIKE '%$searchterm%' AND validated = '1' ORDER BY title,inorder");
			}
			if($searchtype == "summary")
			{
				$result3 = mysql_query("SELECT title, psid, sid, rr, catid, numreviews, uid, summary, rid, gid, wid, charid, completed,wordcount,DATE_FORMAT(date, '$datim')as date, DATE_FORMAT(updated, '$datim') as updateddate FROM ".$tableprefix."fanfiction_stories WHERE summary LIKE '%$searchterm%' AND validated = '1' ORDER BY title");
			}
			if($searchtype == "fulltext")
			{
				$result3 = mysql_query("SELECT title, psid, sid, rr, catid, numreviews, uid, summary, rid, gid, wid, charid, completed,wordcount,DATE_FORMAT(date, '$datim')as date, DATE_FORMAT(updated, '$datim') as updateddate FROM ".$tableprefix."fanfiction_stories WHERE storytext LIKE '%$searchterm%' AND validated = '1' ORDER BY title");
			}
		}
		else if($searchkind == "advanced")
		{
			$querystring = "SELECT title, psid, sid, rr, catid, numreviews, uid, summary, rid, gid, wid, charid, completed,wordcount,DATE_FORMAT(date, '$datim')as date, DATE_FORMAT(updated, '$datim') as updateddate FROM ".$tableprefix."fanfiction_stories ";
			$clauses = 0;
			if(($catid != "") && ($catid != "all"))
			{
				$querystring .= "WHERE ";
				$querystring .= "catid = '$catid' ";
				$clauses ++;
			}

			if($charname != "")
			{
				if($clauses > 0)
					$querystring .= "AND ";
				else
					$querystring .= "WHERE ";
				$querystring .= "charid LIKE '%$charname%' ";
				$clauses ++;
			}

			if($gid != "")
			{
				if($clauses > 0)
					$querystring .= "AND ";
				else
					$querystring .= "WHERE ";
				$querystring .= "gid LIKE '%$gid%' ";
				$clauses ++;
			}

			if($rid != "")
			{
				if($clauses > 0)
					$querystring .= "AND ";
				else
					$querystring .= "WHERE ";
				$querystring .= "rid = '$rid' ";
				$clauses ++;
			}

			if($wid != "")
			{
				if($clauses > 0)
					$querystring .= "AND ";
				else
					$querystring .= "WHERE ";
				$querystring .= "wid LIKE '%$wid%' ";
				$clauses ++;
			}
			if($searchterm != "")
			{
				if($clauses > 0)
					$querystring .= "AND ";
				else
					$querystring .= "WHERE ";
				$querystring .= "(summary LIKE '%$searchterm%' OR title LIKE '%$searchterm%' OR chapter LIKE '%$searchterm%' OR storytext LIKE '%$searchterm%') ";
				$clauses ++;
			}

			if($clauses > 0)
				$querystring .= "AND ";
			else
				$querystring .= "WHERE ";
			$querystring .= "validated = '1' GROUP BY psid ORDER BY title";

			$result3 = mysql_query($querystring);
		}
		if($searchtype != "penname")
			$numrows = mysql_num_rows($result3);
		if($numrows == 0)
		{
			$output .= "<center>"._NORESULTS."</center>";
		}
		else if($numrows > 50)
		{
			$output .= "<center>"._TOOMANYRESULTS."</center>";
		}
		else
		{
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
				$tpl->assign("updated"   , $stories[updateddate] );
				$tpl->assign("published"   , $stories[date] );
				$tpl->assign("wordcount"   , $numchapters[words] );
				$tpl->assign("numreviews"   , $numreviews );
			}
		}
	}
	else
	{
		$result2 = mysql_query("SELECT catid, parentcatid, category, leveldown FROM ".$tableprefix."fanfiction_categories ORDER BY displayorder");

		$output .= "<form method=\"post\" enctype=\"multipart/form-data\" action=\"search.php\">
		<table class=\"tblborder\" align=\"center\" width=\"200\"><tr><td colspan=\"3\" align=\"center\">
		<b>"._SIMPLE."</b></td></tr><tr><td>
		<select name=\"searchtype\">
		<option value=\"penname\">"._PENNAME."</option>
		<option value=\"storytitle\">"._STORYTITLE."</option>
		<option value=\"chaptertitle\">"._CHAPTERTITLE."</option>
		<option value=\"summary\">"._SUMMARY."</option>";
		if($store == "mysql")
			$output .= "<option value=\"fulltext\">"._FULLTEXT."</option>";

		$output .= "</select></td><td>
		<INPUT name=\"searchterm\">";
		$output .= "</td><td> <INPUT type=\"submit\" name=\"submit\" value=\""._SUBMIT."\"><INPUT type=\"hidden\" name=\"searchkind\" value=\"simple\">
		</td></tr></table></form>";

		$catquery = mysql_query("SELECT catid FROM ".$tableprefix."fanfiction_categories");
		$output .= "<script language=\"javascript1.2\">";
		$output .= "function setOptions(chosen)";
		$output .= "{";
		$output .= "	var selbox = document.myform.charname;";
		$output .= "	selbox.options.length = 0;";
		$output .= "if (chosen == \"all\"){";
		$output .= " selbox.options[selbox.options.length] = new Option(\""._NOCHARACTERS."\", \"\");";
		$output .= "}";
		while($categories = mysql_fetch_array($catquery))
		{
			$output .= "	if (chosen == \"$categories[catid]\") {";
			$charquery = mysql_query("SELECT charname,charid FROM ".$tableprefix."fanfiction_characters WHERE catid = '$categories[catid]' ORDER BY charname");
			$output .= " selbox.options[selbox.options.length] = new Option(\"All Characters\", \"\");";
			while($characters = mysql_fetch_array($charquery))
			{
				$output .= " selbox.options[selbox.options.length] = new Option(\"$characters[charname]\");";
			}
			$output .= "}";
		}
		$output .= "}";

		$output .= "</script>";
		
		$output .= "<form method=\"POST\" name=\"myform\" enctype=\"multipart/form-data\" action=\"search.php\">
		<table class=\"tblborder\" align=\"center\" width=\"200\"><tr><td colspan=\"2\" align=\"center\">
		<b>"._ADVANCED."</b></td></tr><tr><td>"._CATEGORY.":</td><td>
		<select name=\"catid\" onchange=\"setOptions(document.myform.catid.options[document.myform.catid.selectedIndex].value);\">//gs added onchange
		<option value=\"all\">"._CHOOSECAT."</option>
		<option value=\"all\">"._ALLCATS."</option>";
			while($categorychoose = mysql_fetch_array($result2))
			{
				$space = "";
				if ($categorychoose[parentcatid] != "-1")
				{
					$parent = mysql_query("select leveldown,category from ".$tableprefix."fanfiction_categories WHERE catid='$categorychoose[parentcatid]'");
					$parentresult = mysql_fetch_array($parent);

					for ($count = 0; $count <= $categorychoose[leveldown]; $count++)
					{
					$space .= "&nbsp;&nbsp;&nbsp;";
					}
				}
				else
					$space = "";
				$output .= "<option value=\"".$categorychoose[catid]."\">";
				$output .= "$space-$categorychoose[category]";
				$output .= "</option>";
			}
		$output .= "</select></td></tr>";
		
		$result3 = mysql_query("SELECT charname FROM ".$tableprefix."fanfiction_characters WHERE catid = '$catid'");
		$output .= "<tr><td>"._CHARACTERS.":</td><td>";
		$output .= "<select name=\"charname\">";//gs added div
		$output .= "<option value=\"\">"._SELECTCAT."</option>";//gs

		
		$output .= "</select></td></tr>";


		$output .= "<tr><td>"._GENRE.":</td><td><select name=\"gid\"><option value=\"\">"._ALL."</option>";
		$result4 = mysql_query("SELECT gid,genre FROM ".$tableprefix."fanfiction_genres");
		while($genres = mysql_fetch_array($result4))
		{
			$output .= "<option value=\"$genres[genre]\">$genres[genre]</option>";
		}
		$output .= "</td></tr>";

		$output .= "<tr><td>"._RATING.":</td><td><select name=\"rid\"><option value=\"\">"._ALL."</option>";
		$result5 = mysql_query("SELECT rid,rating FROM ".$tableprefix."fanfiction_ratings");
		while($ratings = mysql_fetch_array($result5))
		{
			$output .= "<option value=\"$ratings[rating]\">$ratings[rating]</option>";
		}
		$output .= "</td></tr>";

		$output .= "<tr><td>"._WARNING.":</td><td><select name=\"wid\"><option value=\"\">"._ALL."</option>";
		$result6 = mysql_query("SELECT wid,warning FROM ".$tableprefix."fanfiction_warnings");
		while($warnings = mysql_fetch_array($result6))
		{
			$output .= "<option value=\"$warnings[warning]\">$warnings[warning]</option>";
		}
		$output .= "</td></tr>";

		$output .= "<tr><td>"._SEARCHTERM.":</td><td><INPUT name=\"searchterm\"></td></tr>";
		$output .= "<tr><td align=\"center\"><INPUT name=\"submit\" value=\""._SUBMIT."\" type=\"submit\"><INPUT type=\"hidden\" name=\"searchkind\" value=\"advanced\"></form>";


		$output .= "</table><br>";
	}
	$tpl->assign( "output", $output );
	$tpl->printToScreen();

}

function recent($offset, $index)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $itemsperpage, $ratings;
	include ("header.php");
	$result = mysql_query("SELECT copyright from ".$tableprefix."fanfiction_settings");
	$helpcop = mysql_fetch_array($result);

	$tpl = new TemplatePower( "skins/$skin/search.tpl" );
	$tpl->assignInclude( "storyblock", "./skins/$skin/storyblock.tpl" );
	$tpl->prepare();
	$tpl->assign( "footer", $helpcop[copyright] );
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

	$output .= "<center><h4>"._RECENTSTORIES."</h4></center>";
	$tpl->assign( "output", $output );

	if (empty($offset) || $offset < 0)
	{
		$offset=0;
	}
	if (empty($index)) $index=0;
	$limit = $itemsperpage;
	$totallimit = 10 * $limit;
	include ("timefunctions.php");
	
	$count = mysql_query("select count(sid) from ".$tableprefix."fanfiction_stories WHERE psid = sid AND validated = '1' LIMIT $totallimit");
	list($numrows)= mysql_fetch_array($count);
	$result2 = mysql_query("SELECT title, psid, sid, rr, catid, numreviews, uid, summary, rid, gid, wid, charid, completed,wordcount,DATE_FORMAT(date, '$datim')as date, DATE_FORMAT(updated, '$datim') as updateddate FROM ".$tableprefix."fanfiction_stories WHERE psid = sid AND validated = '1' ORDER BY updated DESC LIMIT $offset,$limit");
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
		$tpl->assign("updated"   , $stories[updateddate] );
		$tpl->assign("published"   , $stories[date] );
		$tpl->assign("wordcount"   , $numchapters[words] );
		$tpl->assign("numreviews"   , $numreviews );
	}

	$tpl->gotoBlock( "_ROOT" );
	$index++; /* Increment the line index by 1 */

	if ($numrows>$limit) {
	if ($offset>0)
	{
		$pagelinks .= '<a href="search.php?action=recent&offset='.($offset-$limit).'">['._PREVIOUS.']</a> ';
	}
	else
		$pagelinks .=  '['._PREVIOUS.'] ';
	if(ceil($numrows/$limit) < 10)
		$totpages = ceil($numrows/$limit);
	else	
		$totpages = 10;
	$curpage=floor($offset/$limit)+1;
	for ($i=0;$i<$totpages;$i++)
	{
		if ($i+1!=$curpage)
			$pagelinks .=  '<a href="search.php?action=recent&offset='.($i*$limit).'">'.($i+1).'</a> ';
		else $pagelinks .=  ($i+1).' ';
	}
	if ($curpage<$totpages)
	{
		$pagelinks .=  '<a href="search.php?action=recent&offset='.($offset+$limit).'">['._NEXT.']</a>';
	}
	else
		$pagelinks .=  '['._NEXT.']';
	}

	$tpl->assign( "pagelinks", $pagelinks );
	$tpl->printToScreen();
}


switch ($action)
{

	case "recent":
        recent($offset, $index);
    break;

	default:
		main($submit, $searchtype, $searchterm, $searchkind, $catid, $gid, $wid, $rid, $charname);
		break;
}

?>