<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: edit-item.php
// Version 4.6
// Edit selected item
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
$pagetitle="Edit Selected News Item";

$error = DBConnect ($mhost,$muser,$mpass,$mdb);
if (trim($error)=="")
{
	$tblname = QuoteSmart($prefix."saxon");
	$newsid = QuoteSmart($newsid);
	$result = mysql_query ("SELECT * FROM $tblname WHERE NEWSID='$newsid'");
	if (!$result) die('Invalid query: ' . mysql_error());
	while($row = mysql_fetch_array($result))
	{
		list($year, $month, $day) = explode ("-", $row['DATE']);

		$title= htmlspecialchars($row['TITLE']);
		$news= $row['NEWS'];
		$author=$row['POSTER'];
		$edited=$row['EDITED'];
		/* If form hasnt been submitted */
		if(!isset($_POST['submit']))
		{
			$printmsg="<strong>Created on:</strong> ".DisplayDate($row['DATE'])."<br />\n";
			// Item has already been edited before
			if($edited!="0000-00-00") {
				$editdate = DisplayDate($edited);
				$printmsg .= "<strong>Last edited on:</strong> ".$editdate."<br />\n";
			}
			$scriptname = "update.php?newsid=".$newsid;
			PrintForm($pagetitle,$scriptname,$author,$edited,$newsid,$title,$year,$month,$day,$news,$printmsg,"Update");
		}
	}
}

include("footer.php");

?>