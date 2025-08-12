<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: confirm-delete.php
// Version 4.6
// Delete a selected item
// Developed by Black Widow
// Copyright (c) 2004 by Black Widow
// Support: www.forum.quirm.net
//Commercial Site: www.blackwidows.co.uk
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
$today = date("Y-m-d");

/* If form hasnt been submitted */
if(!isset($_POST['confirm']))
{ 
	$error = DBConnect ($mhost,$muser,$mpass,$mdb);
	if (trim($error)=="")
	{
		$tblname = $prefix."saxon";
		$query = sprintf("SELECT * FROM %s WHERE NEWSID=%s",
			QuoteSmart($tblname),
			QuoteSmart($newsid));
		
		$result = mysql_query ($query);
		if (!$result) die('Invalid query: ' . mysql_error());
		while($row = mysql_fetch_array($result))
		{
			// Formatted posting date for display only
			$postdate = date("l, d F Y",strtotime($row['DATE']));
			$title= $row['TITLE'];
			$news= nl2br($row['NEWS']);
			$name=$row['POSTER'];
			/* If form hasnt been submitted */
			if(!isset( $_POST['submit']))
			{
				?>
<h2>Delete Selected News Item</h2>
<p><strong>Title:</strong> <?php echo $title; ?><br />
<strong>Author:</strong> <?php echo $name; ?><br />
<strong>Dated:</strong> <?php echo $postdate; ?></p>
<div class="border-bottom"><strong>Details:</strong><br /><?php echo $news; ?></div>

<form action="<?php echo $scriptname; ?>" method="post">
<input name="newsid" id="newsid" type="hidden" value="<?php echo $newsid; ?>" />
<input name="title" id="title" type="hidden" value="<?php echo $title; ?>" />
<input name="postdate" id="postdate" type="hidden" value="<?php echo $postdate; ?>" />
<p class="msg">Are you sure you want to delete this item?</p>
<p><input name="confirm" class="button" type="submit" value="Yes" /> 
<input name="confirm" class="button" type="submit" value="No" /></p>
</form>
<?php
			}
		}
	}
}
else
{
	$confirm=$_POST['confirm'];
	if($confirm=="Yes")
	{
		$postdate = $posted = $_POST['postdate'];
		$error = DBConnect ($mhost,$muser,$mpass,$mdb);
		if (trim($error)=="")
		{
			$tblname = $prefix."saxon";
			$query = sprintf("DELETE LOW_PRIORITY FROM %s WHERE NEWSID=%s",
				QuoteSmart($tblname),
				QuoteSmart($newsid));

			if ( @mysql_query($query) ) 
			{
				echo "<p class=\"success\">News item - ".stripslashes($title)." (".DisplayDate(stripslashes($postdate)).") - deleted</p>\n";
				$rss_auto = "0";
				$rss_auto = include 'rss-auto.php';
				// check rss-auto ran
				if ($rss_auto == "1") echo "<p class=\"success\">New RSS Feed created!</p>\n";
				else echo "<p class=\"warning\">RSS Feed creation failed. Please contact the site administrator.</p>";
			} 
			else echo "<p class=\"error\">Could not remove news item - ".stripslashes($title).". Please contact the site administrator.</p>\n";
		}
	}
	else
	{
		echo "<p class=\"msg\">Nothing deleted</p>\n";
		include ("footer.php");
		exit;
	}
}
include("footer.php");

?>