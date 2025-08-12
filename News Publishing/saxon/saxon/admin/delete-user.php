<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: delete-user.php
// Version 4.6
// Delete an existing user
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
<h2>Delete An Existing User</h2>
<p>Select a user to delete:</p>
<?php
$error = DBConnect ($mhost,$muser,$mpass,$mdb);
if (trim($error)=="")
{
	$tblname = QuoteSmart($prefix."saxon_users");
	$result = mysql_query ("SELECT USER_ID,USER_NAME from $tblname");
	if (!$result) die('Invalid query: ' . mysql_error());
	echo "<ul class=\"list-all-items\">\n";
	while($row = mysql_fetch_array($result))
	{
		$user_id = $row['USER_ID'];
		$user = $row['USER_NAME'];
		if ($user != "admin") 
		{
?>	
<li><a href="confirm-delete-user.php?user=<?php echo $user ?>"><?php echo $user; ?></a></li>
<?php
		}
	}
	echo "</ul>\n";
}
include("../footer.php");
?>