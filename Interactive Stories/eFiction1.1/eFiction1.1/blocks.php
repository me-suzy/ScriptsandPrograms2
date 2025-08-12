<?php

	$result3 = mysql_query("SELECT title,uid,rid,summary,sid,catid FROM ".$tableprefix."fanfiction_stories WHERE featured = '1'");
	while($featured = mysql_fetch_array($result3))
	{
		$tpl->newBlock("featuredblock");
		$summary = stripslashes(substr($featured[summary], 0, 75) . "...");
		$result4 = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$featured[uid]'");
		$authorresult = mysql_fetch_row($result4);
		$author = "<a href=\"viewuser.php?uid=$featured[uid]\">$authorresult[0]</a>";

		$result4 = mysql_query("SELECT ratingwarning,warningtext FROM ".$tableprefix."fanfiction_ratings WHERE rating = '$featured[rid]'");
		$rating = mysql_fetch_array($result4);
		if($rating[ratingwarning] == "0")
			$title = "<a href=\"viewstory.php?sid=$featured[sid]\">$featured[title]</a>";
		else
		{
			$warningtext = ereg_replace("'", "\'", $rating[warningtext]);
			$title = "<a href=\"javascript:if (confirm('$warningtext')) location = 'viewstory.php?sid=$featured[sid]'\">$featured[title]</a>";
		}
		
		$catquery = mysql_query("SELECT category, catid, parentcatid, leveldown FROM ".$tableprefix."fanfiction_categories WHERE catid = '$featured[catid]'");
		$category = mysql_fetch_array($catquery);
		$categorylinks1 = "<a href=\"categories.php?catid=$category[catid]&parentcatid=$category[catid]\">$category[category]</a>";
		$currentparent = $category[parentcatid];
		for ($i = 0; $i < $category[leveldown]; $i++)
		{
			$parentquery = mysql_query("SELECT category, catid, parentcatid, leveldown FROM ".$tableprefix."fanfiction_categories WHERE catid = '$currentparent'");
			$parent = mysql_fetch_array($parentquery);

				$spacer = " > ";
			$categorylinks1 = "<a href=\"categories.php?catid=$parent[catid]&parentcatid=$parent[catid]\">$parent[category]</a>" . $spacer . $categorylinks1;
		}

		$tpl->assign("featuredtitle"   , $title );
		$tpl->assign("featuredauthor"   , $author );
		$tpl->assign("featuredsummary", $summary );
		$tpl->assign("featuredrating", $featured[rid] );
		$tpl->assign("featuredcategory", $categorylinks1 );
	}

	$tpl->gotoBlock( "_ROOT" );

	$count = 1;
	$result4 = mysql_query("SELECT * FROM ".$tableprefix."fanfiction_categories WHERE parentcatid = '-1' ORDER BY displayorder");
	while($categories = mysql_fetch_array($result4))
	{
		$tpl->newBlock("categoriesblock");
		$catimage = "skins/$skin/images/$categories[image]";
		$title = "<a href=\"categories.php?catid=$categories[catid]&parentcatid=$categories[catid]\">$categories[category]</a>";
		//$query = mysql_query("SELECT count(sid) FROM ".$tableprefix."fanfiction_stories WHERE catid = '$categories[catid]' AND sid = psid AND validated = '1'");
		//$numstories = mysql_fetch_array($query);
		
		$tpl->assign("categorytitle"   , $title );
		$tpl->assign("categorydescription"   , $categories[description] );
		$tpl->assign("categoryimage", $catimage );
		//if($numstories[0] != "0")
		$tpl->assign( "numstories", "(" .$categories[numitems]. ")" );
		if ($count == $columns)
		{
			$categorycolumn = "</tr><tr>";
			$tpl->assign( "categorycolumn", $categorycolumn );
			$count -= $columns;
		}
		$count++;
	}

	$tpl->gotoBlock( "_ROOT" );

	$result5 = mysql_query("SELECT title,uid,rid,summary,sid,catid FROM ".$tableprefix."fanfiction_stories WHERE sid=psid AND validated = '1' ORDER BY updated DESC LIMIT $numupdated");
	while($updated = mysql_fetch_array($result5))
	{
		$tpl->newBlock("recentblock");
		$summary = stripslashes(substr($updated[summary], 0, 75) . "...");
		$result4 = mysql_query("SELECT penname FROM ".$tableprefix."fanfiction_authors WHERE uid = '$updated[uid]'");
		$authorresult = mysql_fetch_row($result4);
		$author = "<a href=\"viewuser.php?uid=$updated[uid]\">$authorresult[0]</a>";

		$result4 = mysql_query("SELECT ratingwarning,warningtext FROM ".$tableprefix."fanfiction_ratings WHERE rating = '$updated[rid]'");
		$rating = mysql_fetch_array($result4);
		if($rating[ratingwarning] == "0")
			$title = "<a href=\"viewstory.php?sid=$updated[sid]\">$updated[title]</a>";
		else
		{
			$warningtext = ereg_replace("'", "\'", $rating[warningtext]);
			$title = "<a href=\"javascript:if (confirm('$warningtext')) location = 'viewstory.php?sid=$updated[sid]'\">$updated[title]</a>";
		}
		
		$catquery = mysql_query("SELECT category, catid, parentcatid, leveldown FROM ".$tableprefix."fanfiction_categories WHERE catid = '$updated[catid]'");
		$category = mysql_fetch_array($catquery);
		$categorylinks2 = "<a href=\"categories.php?catid=$category[catid]&parentcatid=$category[catid]\">$category[category]</a>";
		$currentparent = $category[parentcatid];
		for ($i = 0; $i < $category[leveldown]; $i++)
		{
			$parentquery = mysql_query("SELECT category, catid, parentcatid, leveldown FROM ".$tableprefix."fanfiction_categories WHERE catid = '$currentparent'");
			$parent = mysql_fetch_array($parentquery);

				$spacer = " > ";
			$categorylinks2 = "<a href=\"categories.php?catid=$parent[catid]&parentcatid=$parent[catid]\">$parent[category]</a>" . $spacer . $categorylinks2;
		}

		$summary = stripslashes($summary);
		$tpl->assign("recenttitle"   , $title );
		$tpl->assign("recentauthor"   , $author );
		$tpl->assign("recentsummary", $summary );
		$tpl->assign("recentrating", $updated[rid] );
		$tpl->assign("recentcategory", $categorylinks2 );
	}	
	
?>