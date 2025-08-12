<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: list-users.php
// Version 4.6
// List info on all existing users
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
<h2>List All Users</h2>
<?php
$error = DBConnect ($mhost,$muser,$mpass,$mdb);
if (trim($error)=="")
{
	$tblname = QuoteSmart($prefix."saxon_users");
	$result = mysql_query ("SELECT * FROM $tblname");
	if (!$result) die('Invalid query: ' . mysql_error());
	echo "<dl>\n";
	while($row = mysql_fetch_array($result))
	{
?>	
<dt><?php echo $row['USER_NAME']; ?></dt>
<dd>Full name: <?php echo $row['FULL_NAME']; ?></dd>
<dd>Admin Status: <?php echo $row['SUPER_USER']; ?></dd>
<?php
	}
	echo "</dl>\n";
}
include("../footer.php");
?>