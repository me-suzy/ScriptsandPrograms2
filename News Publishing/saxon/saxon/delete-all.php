<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: delete-all.php
// Version 4.6
// Delete all entries
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
/* If form hasnt been submitted */
if(!isset($_POST['confirm']))
{ 
	$self=$_SERVER['PHP_SELF'];
?>
<h2>Delete All News Items</h2>
<div id="post-form" class="center">
<form action="<?php echo $self ?>" method="post">
<p class="warning">Are you sure you want to delete all news items?</p>
<p class="warning">This data cannot be recovered.</p>
<p><input name="confirm" class="button" type="submit" value="Yes" /> 
<input name="confirm" class="button" type="submit" value="No" /></p>
</form>
</div>
<?php
}
else
{
	$confirm=$_POST['confirm'];
	if($confirm=="Yes")
	{
		$error = DBConnect ($mhost,$muser,$mpass,$mdb);
		if (trim($error)=="")
		{
			$tblname = $prefix."saxon";
			$query = sprintf("DELETE LOW_PRIORITY FROM %s", QuoteSmart($tblname));
			if ( @mysql_query($query) ) {
				echo "<p class=\"success\">All News Items deleted</p>\n";
				$rss_auto = "0";
				$rss_auto = include 'rss-auto.php';
				// check rss-auto ran
				if ($rss_auto == "1") echo "<p class=\"success\">New RSS Feed created!</p>\n";
				else echo "<p class=\"warning\">RSS Feed creation failed</p>";
			}
			else echo "<p class=\"error\">Could not delete news items:<br />" .mysql_error(). "</p>\n";
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