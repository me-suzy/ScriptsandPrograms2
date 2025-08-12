<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: confirm delete-user.php
// Version 4.6
// Confirm deletion of an existing user
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

/* If form hasnt been submitted */
if(!isset($_POST['confirm'])) {
	if ($user == "admin") {
		echo "<p class=\"error\">You cannot delete the main Admin User!</p>\n";
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
			list($user_id,$user_full_name,$user_user_pwd,$user_user_status)=GetUser($user,$prefix);
			if ($user_user_status=="Y") $user_status = "Super User";
			if ($user_user_status=="N") $user_status = "Standard User";
?>
<h2>Delete Selected User</h2>
<div id="admin-form">
<form action="<?php echo $self ?>" method="post">
<input name="user" id="user" type="hidden" value="<?php echo $user ?>" />
<input name="user_id" id="user_id" type="hidden" value="<?php echo $user_id ?>" />
<p><strong>User Login: </strong> <?php echo $user ?></p>
<p><strong>Full Name:</strong> <?php echo $user_full_name ?></p>
<p><strong>Status:</strong> <?php echo $user_status ?></p>
<p class="warning">Are you sure you want to delete this user?</p>
<form method="post" action="<?php echo $self ?>">
<p><input type="submit" class="button" name="confirm" value="Yes" /> 
<input type="submit" class="button" name="confirm" value="No" /></p>
</form>
</div>
<?php
		}
	}
}
else {
	$confirm=$_POST['confirm'];
	if($confirm=="Yes") { 
		$error = DBConnect ($mhost,$muser,$mpass,$mdb);
		if (trim($error)=="") {
			$tblname = QuoteSmart($prefix."saxon_users");
			$user_id = QuoteSmart($user_id);
			$result = "DELETE LOW_PRIORITY FROM $tblname WHERE USER_ID='$user_id'";
			if ( @mysql_query($result) ) {
				echo "<p class=\"success\">User '$user' deleted</p>\n";
			} 
			else {
				echo "<p class=\"error\">Could not delete user:<br />" .mysql_error(). "</p>\n";
			}
		}
	}
	else {
		echo "<p class=\"msg\">No users deleted</p>\n";
		include ("../footer.php");
		exit;
	}
}
include("../footer.php");

?>