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

function main($sid, $i)
{
	global $tableprefix, $logo, $adminarea, $skin, $home, $recent, $catslink, $authors, $help, $search, $login, $titles, $logout, $files, $reviewsallowed, $favorites, $storiespath;
	include ("header.php");
	$resulta = mysql_query("SELECT copyright FROM ".$tableprefix."fanfiction_settings");
	$settings = mysql_fetch_array($resulta);
	$result = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
	$stories = mysql_fetch_array($result);
	if($sid == $stories[psid])
	{
		$query = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_stories WHERE psid = '$sid' AND validated = '1' ORDER BY inorder ASC");
		$numchaps = mysql_num_rows($query);
	}
	if(($numchaps == "1") || ($numchaps == "") || ($i == "1"))
	{

		mysql_query("UPDATE ".$tableprefix."fanfiction_stories SET counter = (counter + 1) WHERE sid = '$sid'");

		//make a new TemplatePower object
		$tpl = new TemplatePower( "skins/$skin/viewstory.tpl" );

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


		$result4 = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$stories[uid]'");
		$userinfo = mysql_fetch_array($result4);

	  	if($store == "files")
		{
			$file = "$storiespath/$userinfo[penname]/$stories[sid].txt";
			$log_file = @fopen($file, "r");
			$file_contents = @fread($log_file, filesize($file));
			$story = (nl2br(strip_tags($file_contents, '<b><i><u><center><img><a><hr><p>')));
			@fclose($log_file);
		}
		else if($store == "mysql")
		{
			$story .= (nl2br(strip_tags($stories[storytext], '<b><i><u><center><img><a><hr><p>')));
		}
		$author = "<a href=\"viewuser.php?uid=$stories[uid]\">$userinfo[penname]</a>";
		$result4 = mysql_query("SELECT ratingwarning,warningtext FROM ".$tableprefix."fanfiction_ratings WHERE rating = '$stories[rid]'");
		$rating = mysql_fetch_array($result4);
		if($rating[ratingwarning] == "0")
			$title = "<a href=\"viewstory.php?sid=$stories[psid]\">$stories[title]</a>";
		else
		{
			$warningtext = str_replace("'", "\'", $rating[warningtext]);
			$title = "<a href=\"javascript:if (confirm('$warningtext')) location = 'viewstory.php?sid=$stories[psid]'\">$stories[title]</a>";
		}
		if(($stories[wid] == "0") || ($stories[wid] == ""))
			$warning = ""._NONE."";
		else
			$warning = "$stories[wid]";
			
		$catquery = mysql_query("SELECT category, catid, parentcatid, leveldown FROM ".$tableprefix."fanfiction_categories WHERE catid = '$stories[catid]'");
		$category = mysql_fetch_array($catquery);
		$categorylinks = "<a href=\"categories.php?catid=$category[catid]&parentcatid=$category[catid]\">$category[category]</a>";
		$currentparent = $category[parentcatid];
		for ($i = 0; $i < $category[leveldown]; $i++)
		{
			$parentquery = mysql_query("SELECT category, catid, parentcatid, leveldown FROM ".$tableprefix."fanfiction_categories WHERE catid = '$currentparent'");
			$parent = mysql_fetch_array($parentquery);
			//if($parent[leveldown] != "0")
			//{
				$spacer = " > ";
			//}
			$categorylinks = "<a href=\"categories.php?catid=$parent[catid]&parentcatid=$parent[catid]\">$parent[category]</a>" . $spacer . $categorylinks;
			$currentparent = $parent[parentcatid];
		}
		$chapterquery = mysql_query("SELECT sid,title,chapter,inorder FROM ".$tableprefix."fanfiction_stories WHERE psid = '$stories[psid]' AND validated = '1' ORDER BY inorder");
		$numchapters = mysql_num_rows($chapterquery);

		$jumpmenu .= "<form style=\"margin:0\" enctype=\"multipart/form-data\" method=\"post\" action=\"viewstory.php\">";
		if($stories[inorder] != "0")
		{
			$prevquery = mysql_query("SELECT sid FROM ".$tableprefix."fanfiction_stories WHERE psid = '$stories[psid]' AND inorder = ('$stories[inorder]' - 1)");
			$previous = mysql_fetch_array($prevquery);
			$jumpmenu .= "<a href=\"viewstory.php?sid=$previous[sid]&i=1\"><<</a> ";
		}
		$jumpmenu .= "<select name=\"sid\" onChange=\"if (this.selectedIndex >0) window.location=this.options[this.selectedIndex].value\">";
		$jumpmenu .= "<option>"._CHAPTERS."</option>";
		if($numchapters > 1)
			$jumpmenu .= "<option value=\"viewstory.php?sid=$stories[psid]\">"._INDEX."</option>";
		while($chapters = mysql_fetch_array($chapterquery))
		{
			$jumpmenu .= "<option value=\"viewstory.php?sid=$chapters[sid]&i=1\"";
			if("$chapters[sid]" == $sid)
			{
				$jumpmenu .= " selected ";
			}
			$chapnum = $chapters[inorder] + 1;
			$jumpmenu .= ">$chapnum. $chapters[chapter]</option>";
		}

		$jumpmenu .= "</select>";

		if($stories[inorder] != ($numchapters - 1))
		{
			$nextquery = mysql_query("SELECT sid FROM ".$tableprefix."fanfiction_stories WHERE psid = '$stories[psid]' AND inorder = ('$stories[inorder]' + 1)");
			$next = mysql_fetch_array($nextquery);
			$jumpmenu .= " <a href=\"viewstory.php?sid=$next[sid]&i=1\">>></a> ";
		}

		$jumpmenu .= "</form>";

		if($reviewsallowed == "1")
		{
			$reviewslink = "<a href=\"reviews.php?action=addreview&sid=$stories[sid]\">"._SUBMITREVIEW."</a>";
			$reviews = "<a href=\"reviews.php?sid=$stories[sid]&a=1\">"._REVIEWS."</a>";

			$numreviews = "<a href=\"reviews.php?sid=$stories[sid]&a=1\">$stories[numreviews]</a>";
		}
		if($stories[rr] == "1")
			$roundrobin = ""._ADDROUNDROBIN1." <a href=\"stories.php?action=addchapter&add=add&sid=".$stories[psid]."&submit=newchapter\">"._ADDROUNDROBIN2."</a>?";

		if($_SESSION['adminloggedin'] == "1")
		{
			$adminlinks = "<a href=\"stories.php?action=editstory&sid=$sid\">"._EDITSTORY."</a> | <a href=\"stories.php?action=deletestory&sid=$sid\">"._DELETESTORY."</a>";
		}

		$printicon = "<a href=\"viewstory.php?action=printable&sid=$sid\" target=\"_blank\"><img src=\"images/print.gif\" border=\"0\"></a>";
		
		if(($_SESSION['loggedin'] == "1") && ($favorites == "1"))
			$addtofaves = "<a href=\"viewuser.php?action=favstor&uid=$useruid&sid=$stories[psid]\">"._ADDTOFAVES."</a>";
		
		$summary = stripslashes($stories[summary]);
		$tpl->assign("title"   , $title );
		$tpl->assign("author"   , $author );
		$tpl->assign("summary"   , $summary );
		$tpl->assign("rating"   , $stories[rid] );
		$tpl->assign("genres"   , $stories[gid] );
		$tpl->assign("warnings"   , $warning );
		$tpl->assign("characters"   , $stories[charid] );
		$tpl->assign("category"   , $categorylinks );
		$tpl->assign("story"   , $story );
		$tpl->assign("jumpmenu"   , $jumpmenu );
		$tpl->assign("roundrobin"   , $roundrobin );
		$tpl->assign("reviews"   , $reviews );
		$tpl->assign("reviewslink"   , $reviewslink );
		$tpl->assign("adminlinks"   , $adminlinks );
		$tpl->assign("printicon"   , $printicon );
		$tpl->assign("numreviews"   , $numreviews );
		$tpl->assign("addtofaves"   , $addtofaves );
	}
	else
	{
		//make a new TemplatePower object
		$tpl = new TemplatePower( "skins/$skin/storyindex.tpl" );
		//$tpl->assignInclude( "storyblock", "./skins/$skin/storyblock.tpl" );

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

		$topstory = mysql_fetch_array($query);
		$query = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$topstory[uid]'");
		$authorinfo = mysql_fetch_array($query);
		$penname = "<a href=\"viewuser.php?uid=$topstory[uid]\">$authorinfo[penname]</a>";

		$summary = stripslashes($topstory[summary]);
		$title = stripslashes($topstory[title]);

		$catquery = mysql_query("SELECT category, catid, parentcatid, leveldown FROM ".$tableprefix."fanfiction_categories WHERE catid = '$topstory[catid]'");
		$category = mysql_fetch_array($catquery);
		$categorylink = "<a href=\"categories.php?catid=$category[catid]&parentcatid=$category[catid]\">$category[category]</a>";
		$currentparent = $category[parentcatid];
		for ($i = 0; $i < $category[leveldown]; $i++)
		{
			$parentquery = mysql_query("SELECT category, catid, parentcatid, leveldown FROM ".$tableprefix."fanfiction_categories WHERE catid = '$currentparent'");
			$parent = mysql_fetch_array($parentquery);
			//if($parent[leveldown] == "0")
			//{
				$spacer = " > ";
			//}
			$categorylink = "<a href=\"categories.php?catid=$parent[catid]&parentcatid=$parent[catid]\">$parent[category]</a>" . $spacer . $categorylinks;
			$currentparent = $parent[parentcatid];
		}

		if($topstory[completed] == "1")
			$completephrase = ""._COMPLETEDPHRASE."";
		else
			$completephrase = ""._NOTCOMPLETEDPHRASE."";
		
		if(($_SESSION['loggedin'] == "1") && ($favorites == "1"))
			$addtofaves = "<a href=\"viewuser.php?action=favstor&uid=$useruid&sid=$topstory[psid]\">"._ADDTOFAVES."</a>";	
			
		$tpl->assign("firsttitle"   , $title);
		$tpl->assign("firstauthor"   , $penname );
		$tpl->assign("firstsummary"   , $summary );
		$tpl->assign("firstrating"   , $topstory[rid] );
		$tpl->assign("firstgenres"   , $topstory[gid] );
		$tpl->assign("firstwarnings"   , $topstory[wid] );
		$tpl->assign("firstcharacters"   , $topstory[charid] );
		$tpl->assign("firstcategory"   , $categorylink );
		$tpl->assign( "completephrase", $completephrase );
		$tpl->assign( "addtofaves", $addtofaves );

		include ("timefunctions.php");
		
		$result3 = mysql_query("SELECT *,DATE_FORMAT(date, '$datim')as date, DATE_FORMAT(updated, '$datim') as updated FROM ".$tableprefix."fanfiction_stories WHERE psid = '$sid' AND validated = '1' ORDER BY inorder ");
		while($stories = mysql_fetch_array($result3))
		{

			$tpl->newBlock("storyindexblock");

			$a = "1";
			include("storyblock.php");

			if($rating[ratingwarning] == "0")
				$title = "<a href=\"viewstory.php?sid=$stories[sid]&i=1\">$stories[chapter]</a>";
			else
			{
				$warningtext = str_replace("'", "\'", $rating[warningtext]);
				//$warningtext = ereg_replace("\"", "\'", $rating[warningtext]);
				$title = "<a href=\"javascript:if (confirm('$warningtext')) location = 'viewstory.php?sid=$stories[sid]&i=1'\">$stories[chapter]</a>";
			}

			$summary = stripslashes($stories[summary]);
			$tpl->assign("title"   , $title);
			$tpl->assign("author"   , $author );
			$tpl->assign("summary"   , $summary );
			$tpl->assign("rating"   , $stories[rid] );
			$tpl->assign("genres"   , $stories[gid] );
			$tpl->assign("warnings"   , $warning );
			$tpl->assign("characters"   , $stories[charid] );
			$tpl->assign("category"   , $categorylinks );
			$tpl->assign("reviews"   , $reviews );
			$tpl->assign("published"   , $stories[date] );
			$tpl->assign("wordcount"   , $stories[wordcount] );
			$tpl->assign("numreviews"   , $numreviews );
			$tpl->assign("chapternumber"   , ($stories[inorder] + 1) );
		}

		$tpl->gotoBlock( "_ROOT" );

	}

	$tpl->printToScreen();

}

function printable($sid)
{
	global $tableprefix, $sitename, $url, $store, $databasepath, $storiespath;
	//include("header.php");
	include("config.php");
	include("languser.php");
	include ($databasepath."/dbconfig.php");
	$query = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_stories WHERE sid = '$sid'");
	$result = mysql_fetch_array($query);
	$authorquery = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$result[uid]'");
	$author = mysql_fetch_array($authorquery);
	echo "<font face=\"Arial\"><center><b>$result[title]: $result[chapter]</b></center><br>";
	echo "<center>"._BY." <a href=\"$url/viewuser.php?uid=$result[uid]\">$author[penname]</a></center><br>";
	if($store == "files")
	{
		$file = "$storiespath/$author[penname]/$sid.txt";
		$log_file = fopen($file, "r");
		$file_contents = fread($log_file, filesize($file));
		$story .= (nl2br(strip_tags($file_contents, '<b><i><u><center><img><a><hr><p>')));
		fclose($log_file);
	}
	else if($store == "mysql")
	{
		$story .= (nl2br(strip_tags($result[storytext], '<b><i><u><center><img><a><hr><p>')));
	}

	echo "$story";
	echo "<br><br>";
	echo "<center>"._ARCHIVEDAT.": <a href=\"$url\">$sitename</a></center><br>";
	echo "<center><a href=\"$url/viewstory.php?sid=$sid\">$url/viewstory.php?sid=$sid</a></center></font>";
}

switch ($action)
{

	case "printable":
		printable($sid);
	break;

	default:
		main($sid, $i);
		break;
}

?>