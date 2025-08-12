<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: change-user-pwd.php
// Version 4.6
// Change an existing user password
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
if(!isset($_POST['submit']))
{ 
?>
<h2>Change A User's Password</h2>
<div id="admin-form">
<form method="post" action="<?php echo $self ?>">
<p><label for="username">Username: </label><input type="text" name="user" id="username" /></p>
<p><label for="Password1">New Password: </label><input type="password" name="user_pwd1" id="Password1" /></p>
<p><label for="Password2">Re-enter Password: </label><input type="password" name="user_pwd2" id="Password2" /></p>
<p><input type="submit" class="submit button" name="submit" value="Submit" /> 
<input type="reset" class="button" name="reset" value="Reset" /></p>
</form>
</div>
<?php
}
// If form has been submitted
else
{
	$user=$_POST['user'];
	$user_pwd1=$_POST['user_pwd1'];
	$user_pwd2=$_POST['user_pwd2'];
	if($user=="")
	{
?>
<h2>Change A User's Password</h2>
<div id="pwd-form">
<p class="error">Please enter a username.</p>
<p><a href="<?php echo $self ?>">Previous page</a></p>
</div>
<?php
		include("../footer.php");
		exit;
	}
	if($user_pwd1=="" && $user_pwd2=="")
	{
?>
<h2>Change A User's Password</h2>
<div id="pwd-form">
<p class="error">Please enter a new password.</p>
<p><a href="<?php echo $self ?>">Previous page</a></p>
</div>
<?php
		include("../footer.php");
		exit;
	}
	if($user_pwd1<>$user_pwd2)
	{
?>
<h2>Change A User's Password</h2>
<div id="pwd-form">
<p class="error">Your passwords don't match! Please try again.</p>
<p><a href="<?php echo $self ?>">Previous page</a></p>
</div>
<?php
		include("../footer.php");
		exit;
	}
	$error = DBConnect ($mhost,$muser,$mpass,$mdb);
	if (trim($error) != "")
	{
		echo "<p class=\"error\">User information not available at this time! Please contact the site administrator.</p>";
		echo $error;
		include("../footer.php");
		exit;
	}
	// does this user exist?
	$error=CheckUsername($user,$prefix);
	if (trim($error) != "")
	{
		echo "<p>Search completed for user ".$user."</p>\n";
		echo $error;
		include("../footer.php");
		exit;
	}
	// get all user info
	list($member_user_id,$member_full_name,$member_user_pwd,$member_user_status)=GetUser($user,$prefix);
	$new_pwd=md5($user_pwd1);
	$error = DBConnect ($mhost,$muser,$mpass,$mdb);
	if (trim($error) != "")
	{
		echo "<p class=\"warning\">Password not updated.</p>";
		echo $error;
		echo "Please contact the site administrator.</p>\n";
		include("../footer.php");
		exit;
	}
	$tblname = QuoteSmart($prefix."saxon_users");
	$new_pwd = QuoteSmart($new_pwd);
	$member_user_id = QuoteSmart($member_user_id);
	$update= "UPDATE LOW_PRIORITY IGNORE $tblname SET USER_PWD='$new_pwd' WHERE USER_ID='$member_user_id'";
	if (mysql_query($update))
	{
		echo "<p class=\"success\">Password for ".$user." updated</p>";
		include("../footer.php");
		exit;
	}
	echo "<p class=\"error\">Error! Password not updated</p>";
	echo "Please contact the site administrator.</p>\n";
}

include("../footer.php");
?>