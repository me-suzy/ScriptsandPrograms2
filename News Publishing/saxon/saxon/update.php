<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: update.php
// Version 4.6
// Update news item in database
// Developed by Black Widow
// Copyright (c) 2004 by Black Widow
// Support: www.forum.quirm.net
// Commercial Site: www.blackwidows.co.uk
/************************************************************************/
// stop errors on multiple session_start()
if(session_id() == ""){
session_start();
}
header("Cache-control: private"); // IE 6 Fix.
include("functions.php");
include("header.php");
Authenticate();
include 'config.php';
include("menu.php");

$scriptname = $_SERVER['PHP_SELF'];
$pagetitle="Edit Selected News Item";
$task = "update";
// Who edited this item?
$edited_by = trim($_SESSION['full_name']);
$today = date("Y-m-d");

while (list($key, $value) = each($_POST))
{
	$$key = $value;
}

if ($title=="" || $news=="") {
	$printmsg = "<p class=\"error\">Please ensure that all boxes are completed!</p>\n";
	PrintForm($pagetitle,$scriptname,$name,$edited,$newsid,$title,$year,$month,$day,$news,$printmsg,"Update");
	include("footer.php");
	exit;
}

if (!checkdate($month,$day,$year))
{
	$printmsg = "<p class=\"error\">Invalid date!</p>\n";
	PrintForm($pagetitle,$scriptname,$name,$edited,$newsid,$title,$year,$month,$day,$news,$printmsg,"Add Item");
	include("footer.php");
	exit;
}		

if ($html=="0") {
	$title = strip_tags($title);
	$news = strip_tags($news);
}

//Convert all applicable characters to HTML entities with the exception of single quotes;
if ($html=="0") $news = htmlentities($news);
// Remove any whitespace from the end of the $news string
$news = rtrim($news);
$postdate = $year."-".$month."-".$day;
$error = DBConnect ($mhost,$muser,$mpass,$mdb);
if (trim($error)=="")
{
	$edited = date("Y-m-d");
	$tblname = QuoteSmart($prefix."saxon");
	$title =QuoteSmart($title);
	$news = QuoteSmart($news);
	
	$update= "UPDATE LOW_PRIORITY IGNORE $tblname SET DATE='$postdate',TITLE='$title',NEWS='$news',EDITED='$edited' WHERE NEWSID='$newsid'";

	if (mysql_query($update)) 
	{
		if (!$update) die('Invalid query: ' . mysql_error());
		$editdate = date("l, d F Y",strtotime($edited));
		echo "<p class=\"success\">News item - ".stripslashes($title)." (".DisplayDate(stripslashes($postdate)).") - amended</p>\n";
		if($notify ==1) Notify($postdate,$title,$edited_by,$task);
		if (strtotime($postdate) <= strtotime($today)) {
			echo "true";
			$rss_auto = "0";
			$rss_auto = include 'rss-auto.php';
			// check rss-auto ran
			if ($rss_auto == "1") echo "<p class=\"success\">New RSS Feed created!</p>\n";
			else echo "<p class=\"warning\">RSS Feed creation failed. Please contact the site administrator.</p>";
		}
	} 
	else {
		echo "<p class=\"error\">Error amending news item:<br />" .mysql_error(). "</p><p>".$news."\n";
	}
}
	else{echo "<p class=\"error\">$error</p>";}
include("footer.php");

?>