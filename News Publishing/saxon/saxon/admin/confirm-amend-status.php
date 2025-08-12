<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: confirm-amend-status.php
// Version 4.6
// Confirm status change of an existing user
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
$scriptname=$_SERVER['PHP_SELF'];

/* If form hasnt been submitted */
if(!isset($_POST['confirm'])) {
	if ($user == "admin") {
		echo "<p class=\"error\">You cannot alter the status of the main Admin User!</p>\n";
		include ("../footer.php");
		exit;
	}
	$error = DBConnect ($mhost,$muser,$mpass,$mdb);
	if (trim($error)=="")
	{
		$user_check = CheckUsername($user,$prefix);
		if (trim($user_check) != "") 
		{
			echo "<p>Search completed for user ".$user."</p>\n";
			echo $user_check;
			include("../footer.php");
			exit;
		}
		else 
		{
			list($users_id,$users_full_name,$users_pwd,$users_status)=GetUser($user,$prefix);
			if ($users_status=="Y") $status = "Administrator";
			if ($users_status=="N") $status = "Standard User";
?>
<h2>Amend User Status</h2>
<div id="admin-form">
<form action="<?php echo $scriptname; ?>" method="post">
<input name="user" id="user" type="hidden" value="<?php echo $user; ?>" />
<input name="users_id" id="users_id" type="hidden" value="<?php echo $users_id; ?>" />
<p><strong>User Login: </strong> <?php echo $user; ?></p>
<p><strong>Full Name:</strong> <?php echo $users_full_name; ?></p>
<p><strong>Current Status:</strong> <?php echo $status; ?></p>
<form method="post" action="<?php echo $scriptname; ?>">

<fieldset class="amenduser">
<legend>New User Status</legend>
<p><input type="radio" class="radio" name="users_status" id="super" value="super" <?php if ($users_status=="Y") echo "checked=\"checked\"";?>
<label for="super">Administrator</label><br />
<input type="radio" class="radio" name="users_status" id="standard" value="standard" <?php if ($users_status=="N") echo "checked=\"checked\"";?>
<label for="standard">Standard User</label></p>
</fieldset>
<p><input type="submit" class="button" name="confirm" value="Update" /> 
<input type="submit" class="button" name="confirm" value="Cancel" /></p>
</form>
</div>
<?php
		}
	}
}
else {
	$confirm=$_POST['confirm'];
	if($confirm=="Update") { 
		$users_status=$_POST['users_status'];
		if ($users_status=="super") $super="Y";
		if ($users_status=="standard") $super="N";
		$error = DBConnect ($mhost,$muser,$mpass,$mdb);
		if (trim($error)=="") {
			$tblname = QuoteSmart($prefix."saxon_users");
			$users_id = QuoteSmart($users_id);
			$super = QuoteSmart($super);
			$result = "UPDATE LOW_PRIORITY IGNORE $tblname SET SUPER_USER='$super' WHERE USER_ID='$users_id'";
			if ( mysql_query($result) ) {
				echo "<p class=\"success\">Status for $user amended</p>\n";
			} 
			else {
				echo "<p class=\"error\">Could not amend user status:<br />" .mysql_error(). "</p>\n";
			}
		}
	}
	else {
		echo "<p class=\"msg\">No changes made</p>\n";
		include ("../footer.php");
		exit;
	}
}
include("../footer.php");

?>