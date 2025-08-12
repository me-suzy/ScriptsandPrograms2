<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: amend-user-status.php
// Version 4.6
// Amend status of user Standard/Super
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
include("../functions.php");
include("admin-header.php");
Authenticate();
UserStatus();
include("../config.php");
include("admin-menu.php");
$self=$_SERVER['PHP_SELF'];
?>
<h2>Amend User Status</h2>
<p>Select a user to amend:</p>
<?php
$error = DBConnect ($mhost,$muser,$mpass,$mdb);
if (trim($error)=="")
{
	$tblname = QuoteSmart($prefix."saxon_users");
	$result = mysql_query ("SELECT * FROM $tblname");
	if (!$result) die('Invalid query: ' . mysql_error());
	echo "<ul class=\"list-all-items\">\n";
	while($row = mysql_fetch_array($result))
	{
		$users_id = $row['USER_ID'];
		$users_login = $row['USER_NAME'];
		if ($users_login != "admin") {
		echo "<li><a href=\"confirm-amend-status.php?user=".$users_login."\">".$users_login."</a></li>\n";
		}
	}
	echo "</ul>\n";
}
include("../footer.php");
?>