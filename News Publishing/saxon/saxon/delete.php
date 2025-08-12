<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: delete.php
// Version 4.6
// Select an item to delete
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
<h2>Remove a News Item</h2>
<p>Select an item to delete.</p>
<?php
$error = DBConnect ($mhost,$muser,$mpass,$mdb);
if (trim($error)=="")
{
	$tblname = $prefix."saxon";
	$query = sprintf("SELECT NEWSID,DATE,TITLE FROM %s ORDER BY 'DATE' ASC",
		QuoteSmart($tblname));

	$result = mysql_query ($query);
	if (!$result) die('Invalid query: ' . mysql_error());
	echo "<ul class=\"list-all-items\">\n";
	while($row = mysql_fetch_array($result))
	{

		// Let's have a nicely formatted posting date for display only
		$displaydate = date("d F Y",strtotime($row['DATE']));
		$newsid=$row['NEWSID'];
		$title=$row['TITLE'];
?>
<div>
<li><a href="confirm-delete.php?newsid=<?php echo $newsid ?>"><?php echo $title ?></a> (<small><?php echo $displaydate ?></small>)</li>
</div>
<?php
	}
?>
</ul>
<p class="delete-all"><a href="delete-all.php">Delete all entries</a></p>
<?php
}
include("footer.php");
?>