<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: archive-display.php
// Version 4.6
// Display All News for a web page
// Developed by Black Widow
// Copyright (c) 2004 by Black Widow
// Support: www.forum.quirm.net
// Commercial Site: www.blackwidows.co.uk
/************************************************************************/
include("functions.php");
include 'config.php';

include "$template";

$error = DBConnect ($mhost,$muser,$mpass,$mdb);
if (trim($error)=="")
{
	$tblname = QuoteSmart($prefix."saxon");
	$today = QuoteSmart(date("Y-m-d"));
	$result = mysql_query ("SELECT * FROM $tblname WHERE DATE <= '$today' ORDER BY 'DATE' DESC");
	if (!$result) die('Invalid query: ' . mysql_error());
	$num_rows = mysql_num_rows($result);
	if($num_rows==0)
	{
		echo "<p class=\"error\">No news to display</p>";
		exit;
	}
	// We want to list all items
	while($row = mysql_fetch_array($result))
	{
		$item = $row['NEWS'];
		$item = PrepText($item, $html);

		// Let's have a nicely formatted posting date for display only
		$displaydate = DisplayDate(($row['DATE']));
		$news_display = Template($displaydate, $row['TITLE'], $item, $row['POSTER'], $row['NEWSID']);
		echo $news_display;
	}
}

?>