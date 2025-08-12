<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: preview.php
// Version 4.6
// Preview news display
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
include "$template";

$today = date("Y-m-d");
?>
<h2>Preview All News Items On Server</h2>

<?php
$error = DBConnect ($mhost,$muser,$mpass,$mdb);
if (trim($error)=="")
{
	$tblname = QuoteSmart($prefix."saxon");
	$result = mysql_query ("SELECT * FROM $tblname ORDER BY 'DATE' DESC");
	if (!$result) die('Invalid query: ' . mysql_error());
	$num_rows = mysql_num_rows($result);
	if($num_rows==0)
	{
		echo "<p class=\"error\">No news to display</p>";
		include("footer.php");
		exit;
	}
	while($row = mysql_fetch_array($result))
	{
		//remove any slashes inserted by magic_quotes_gpc and convert line breaks to <br />
		$row['NEWS'] = stripslashes ($row['NEWS']);
		//Convert all applicable characters to HTML entities with the exception of single quotes;
		if ($html==0) $row['NEWS'] = htmlentities($row['NEWS']);
		$row['NEWS'] = nl2br($row['NEWS']);
		// Let's have a nicely formatted posting date for display only
		$displaydate = date("l, d F Y",strtotime($row['DATE']));
		$news_display = Template($displaydate, $row['TITLE'], $row['NEWS'], $row['POSTER']);
		echo $news_display;
	}
}
include("footer.php");

?>