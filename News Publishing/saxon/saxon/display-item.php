<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: news.php
// Version 4.6
// Display News for a web page
// Developed by Black Widow
// Copyright (c) 2004 by Black Widow
// Support: www.forum.quirm.net
// Commercial Site: www.blackwidows.co.uk
/************************************************************************/
include 'functions.php';
include 'config.php';
include "$singleItem";
include 'templates/display-header.php';

$error = DBConnect ($mhost,$muser,$mpass,$mdb);
if (trim($error)=="")
{
	$tblname = QuoteSmart($prefix."saxon");
	$result = mysql_query ("SELECT NEWSID,DATE,TITLE,NEWS,POSTER FROM $tblname ORDER BY 'DATE' DESC");
	if (!$result) die('Invalid query: ' . mysql_error());
	$num_rows = mysql_num_rows($result);
	if($num_rows==0)
	{
		echo "<p class=\"error\">No news to display</p>";
		exit;
	}
	while($row = mysql_fetch_array($result))
	{
		if($newsid == $row['NEWSID']) 
		{
			$item = $row['NEWS'];
			$item = PrepText($item, $html);

			// Let's have a nicely formatted posting date for display only
			$displaydate = DisplayDate(($row['DATE']));
			$item_display = displayItem($displaydate, $row['TITLE'], $item, $row['POSTER']);
			echo $item_display;
		}
	}
}
include 'templates/display-footer.php';
?>