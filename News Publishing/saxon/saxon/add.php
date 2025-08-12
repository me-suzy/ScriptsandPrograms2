<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: add.php
// Version 4.6
// Add a new item
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
$scriptname=$_SERVER['PHP_SELF'];
$pagetitle="Add A News Item";
$task = "add";
$edited="";
$today = date("Y-m-d");

/* If form hasnt been submitted */
if(!isset($_POST['submit']))
{ 
	$name=trim($_SESSION['full_name']);
	$newsid="";
	$title="";
	$news="";
	$printmsg = "";
	list($year, $month, $day) = explode ("-", date("Y-m-d"));
	PrintForm($pagetitle,$scriptname,$name,$edited,$newsid,$title,$year,$month,$day,$news,$printmsg,"Add Item");

}
// If form has been submitted
else
{
	while (list($key, $value) = each($_POST))
	{
		$$key = $value;
	}
	
	if ($title=="" || $news=="") {
		$printmsg = "<p class=\"error\">Please ensure that all boxes are completed!</p>\n";
		PrintForm($pagetitle,$scriptname,$name,$edited,$newsid,$title,$year,$month,$day,$news,$printmsg,"Add Item");
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
	
	$error = DBConnect ($mhost,$muser,$mpass,$mdb);
	if (trim($error)=="")
	{
		// Prepare variables
		$news = QuoteSmart($news);
		$title =  QuoteSmart($title);
		$name =  QuoteSmart($name);
		$postdate = $year."-".$month."-".$day;
		$tblname = QuoteSmart($prefix."saxon");
		
		$query = "INSERT INTO $tblname (DATE,TITLE,NEWS,POSTER) VALUES ('$postdate','$title','$news','$name')";
		
		if (mysql_query($query) ) {
			echo "<p class=\"success\">News item - ".stripslashes($title)." (".DisplayDate(stripslashes($postdate)).") - added.</p>\n";
			if($notify ==1) Notify($postdate,$title,$name,$task);
			if (strtotime($postdate) <= strtotime($today)) {
				$rss_auto = "0";
				$rss_auto = include 'rss-auto.php';
				// check rss-auto ran
				if ($rss_auto == "1") echo "<p class=\"success\">New RSS Feed created!</p>\n";
				else echo "<p class=\"warning\">RSS Feed creation failed. Please contact the site administrator.</p>";
			}
		} 
		else echo "<p class=\"error\">Error adding news item:<br />" .mysql_error(). "</p><p>".$news."\n";
	}
	else echo "<p class=\"error\">$error</p>";
}
include("footer.php");

?>