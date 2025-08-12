<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: edit.php
// Version 4.6
// Select an item to edit
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
?>
<h2>Edit a News Item</h2>
<p>Select a news item to amend.</p>
<div id="news-edit">
<ul>
<?php
$error = DBConnect ($mhost,$muser,$mpass,$mdb);
if (trim($error)=="")
{
	$tblname = QuoteSmart($prefix."saxon");
	$result = mysql_query ("SELECT NEWSID,DATE,TITLE FROM $tblname ORDER BY 'DATE' DESC");
	if (!$result) die('Invalid query: ' . mysql_error());
	while($row = mysql_fetch_array($result))
	{
		// Lets have a nicely formatted posting date for display only
		$displaydate = date("d F Y",strtotime($row['DATE']));
		$newsid=$row['NEWSID'];
		$title=$row['TITLE'];
?>
<li><a href="edit-item.php?newsid=<?php echo $newsid ?>"><?php echo $title ?> - <?php echo $displaydate ?></a>
<?php
	}
echo "</ul>\n</div>";
}
include("footer.php");
?>