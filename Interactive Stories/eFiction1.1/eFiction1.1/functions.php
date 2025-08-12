<?php

function categoryitems($firstid, $value)
{
	global $tableprefix;
	//add one to the current category
	mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET numitems = (numitems + $value) WHERE catid = '$firstid'");
	//find out current category's parent
	$catquery = mysql_query("SELECT catid, parentcatid FROM ".$tableprefix."fanfiction_categories WHERE catid = '$firstid'");
	$thiscat = mysql_fetch_array($catquery);
	
	//while there is a parent category
	while($thiscat[parentcatid] != "-1")
	{
		//add one to the parent category2				
		mysql_query("UPDATE ".$tableprefix."fanfiction_categories SET numitems = (numitems + $value) WHERE catid = '$thiscat[parentcatid]'");
		//and then get the parentcategory so we can check it for a parent
		$catquery2 = mysql_query("SELECT parentcatid, catid FROM ".$tableprefix."fanfiction_categories WHERE catid = '$thiscat[parentcatid]'");
		$thiscat = mysql_fetch_array($catquery2);
	}
}

?>