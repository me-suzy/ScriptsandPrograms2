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


	$result5 = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$stories[uid]'");
	$userinfo = mysql_fetch_array($result5);
	$author = "<a href=\"viewuser.php?uid=$stories[uid]\">$userinfo[penname]</a>";
	$result4 = mysql_query("SELECT ratingwarning,warningtext FROM ".$tableprefix."fanfiction_ratings WHERE rating = '$stories[rid]'");
	$rating = mysql_fetch_array($result4);
	if($rating[ratingwarning] == "0")
		$title = "<a href=\"viewstory.php?sid=$stories[sid]\">$stories[title]</a>";
	else
	{
		$warningtext = str_replace("'", "\'", $rating[warningtext]);
		$title = "<a href=\"javascript:if (confirm('$warningtext')) location = 'viewstory.php?sid=$stories[sid]'\">$stories[title]</a>";
	}
	if(($stories[wid] == "0") || ($stories[wid] == ""))
		$warning = "none";
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
		//if($parent[leveldown] == "0")
		//{
			$spacer = " > ";
		//}
		$categorylinks = "<a href=\"categories.php?catid=$parent[catid]&parentcatid=$parent[catid]\">$parent[category]</a>" . $spacer . $categorylinks;
		$currentparent = $parent[parentcatid];
	}

	//chapter query

	if($ratings == "2")
	{
		$avgquery = mysql_query("SELECT AVG(rating) as avg_rating FROM ".$tableprefix."fanfiction_reviews WHERE psid = '$stories[sid]' GROUP BY psid");
		$average = mysql_fetch_array($avgquery);
		if($average[avg_rating] >= 0.5)
			$ratingpics = "<img src=\"images/like.gif\">";
		else if(($average[avg_rating] < 0.5) && ($average[avg_rating] > 0))
			$ratingpics = "<img src=\"images/dislike.gif\">";
		else
			$ratingpics = "";
	}
	if($ratings == "1")
	{
		$avgquery = mysql_query("SELECT AVG(rating) as avg_rating FROM ".$tableprefix."fanfiction_reviews WHERE psid = '$stories[sid]' GROUP BY psid");
		$votes = mysql_fetch_array($avgquery);
		if (("$votes[avg_rating]" >= "0") && ("$votes[avg_rating]" <= "2"))
			$ratingpics = "<img src=\"images/starhalf.gif\">";
		else if (("$votes[avg_rating]" > "2") && ("$votes[avg_rating]" <= "3"))
			$ratingpics = "<img src=\"images/star.gif\">";
		else if (("$votes[avg_rating]" > "3") && ("$votes[avg_rating]" <= "4"))
			$ratingpics = "<img src=\"images/star.gif\"><img src=\"images/starhalf.gif\">";
		else if (("$votes[avg_rating]" > "4") && ("$votes[avg_rating]" <= "5"))
			$ratingpics = "<img src=\"images/star.gif\"><img src=\"images/star.gif\">";
		else if (("$votes[avg_rating]" > "5") && ("$votes[avg_rating]" <= "6"))
			$ratingpics = "<img src=\"images/star.gif\"><img src=\"images/star.gif\"><img src=\"images/starhalf.gif\">";
		else if (("$votes[avg_rating]" > "6") && ("$votes[avg_rating]" <= "7"))
			$ratingpics = "<img src=\"images/star.gif\"><img src=\"images/star.gif\"><img src=\"images/star.gif\">";
		else if (("$votes[avg_rating]" > "7") && ("$votes[avg_rating]" <= "8"))
			$ratingpics = "<img src=\"images/star.gif\"><img src=\"images/star.gif\"><img src=\"images/star.gif\"><img src=\"images/starhalf.gif\">";
		else if (("$votes[avg_rating]" > "8") && ("$votes[avg_rating]" <= "9"))
			$ratingpics = "<img src=\"images/star.gif\"><img src=\"images/star.gif\"><img src=\"images/star.gif\"><img src=\"images/star.gif\">";
		else if (("$votes[avg_rating]" > "9") && ("$votes[avg_rating]" < "10"))
			$ratingpics = "<img src=\"images/star.gif\"><img src=\"images/star.gif\"><img src=\"images/star.gif\"><img src=\"images/star.gif\"><img src=\"images/starhalf.gif\">";
		else if ("$votes[avg_rating]" == "10")
			$ratingpics = "<img src=\"images/star.gif\"><img src=\"images/star.gif\"><img src=\"images/star.gif\"><img src=\"images/star.gif\"><img src=\"images/star.gif\">";
		else if (("$votes[avg_rating]" == "0") || ("$votes[avg_rating]" == ""))
			$ratingpics = "";
	}

	if($stories[completed] == "1")
		$completed = ""._YES."";
	else
		$completed = ""._NO."";

	if($stories[rr] == "1")
		$roundrobin = "<img src=\"images/roundrobin.gif\">";
	else
		$roundrobin = "";

	$numchapsquery = mysql_query("SELECT count(sid) as chapters, sum(wordcount) as words FROM ".$tableprefix."fanfiction_stories WHERE psid = '$stories[sid]' AND validated = '1'");
	$numchapters = mysql_fetch_array($numchapsquery);
	if($numchapters[chapters] == "0")
		$numchapters[chapters] = "Sub Chapter";

	if($reviewsallowed == "1")
	{
		$reviews = "<a href=\"reviews.php?sid=$stories[sid]&a=$a\">"._REVIEWS."</a>";
			
		if(($a != "1") && ($numchapters[chapters] != "Sub Chapter"))
		{
			$query = mysql_query("SELECT SUM(numreviews) as total FROM ".$tableprefix."fanfiction_stories WHERE psid = '$stories[sid]'");
			$result = mysql_fetch_array($query);
			$numreviews = "<a href=\"reviews.php?sid=$stories[sid]&a=$a\">$result[total]</a>";
		}
		else
		{
			
			$numreviews = "<a href=\"reviews.php?sid=$stories[sid]&a=$a\">$stories[numreviews]</a>";
		}
	}
	else
		$reviews = "";


?>