<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: add-user.php
// Version 4.6
// Add a new user
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
<h2>Add A New User</h2>
<div id="admin-form">
<form method="post" action="<?php echo $self ?>">
<p><label for="LoginId">Login Name:</label> 
<input type="text" name="member_login" id="LoginId" /></p>

<p><label for="FullName">Full Name:</label> 
<input type="text" name="member_name" id="FullName" /></p>

<p><label for="Password1">Password:</label> 
<input type="password" name="member_pwd1" id="Password1" /></p>

<p><label for="Password2">Re-enter Password:</label> 
<input type="password" name="member_pwd2" id="Password2" /></p>

<fieldset class="adduser">
<legend>User Status</legend>
<p><input type="radio" class="radio" name="member_status" id="super" value="Y" />
<label for="super">Administrator</label><br />
<input type="radio" class="radio" name="member_status" id="standard" value="N" checked="checked" />
<label for="standard">Standard User</label></p>
</fieldset>
<p><input type="submit" class="submit button" name="submit" value="Add User" /> 
<input type="reset" class="button" name="reset" value="Reset" /></p>
</form>
</div>
<?php
}
// If form has been submitted
else
{
	$member_login=$_POST['member_login'];
	$member_name=$_POST['member_name'];
	$member_pwd1=$_POST['member_pwd1'];
	$member_pwd2=$_POST['member_pwd2'];
	$member_status=$_POST['member_status'];
	
	if($member_login=="" || $member_name=="" || $member_pwd1=="" || $member_pwd2=="") {
		echo "<p class=\"error\">Please ensure that all boxes are completed</p>\n";
		include("../footer.php");
		exit;
	}
	
	if (!preg_match("/^[a-zA-Z0-9]+$/", $member_login )) {
		echo "<p class=\"error\">You tried to enter a login name of ".stripslashes($member_login).".<br />\n";
		echo "Login names can only contain letters and numbers.</p>\n";
		include("../footer.php");
		exit;
	}
	
	if($member_pwd1<>$member_pwd2) {
		echo "<p class=\"error\">Your passwords don't match! Please try again.</p>\n";
		include("../footer.php");
		exit;
	}
	
	$error = DBConnect ($mhost,$muser,$mpass,$mdb);
	if (trim($error) != "")
	{
		echo "<p class=\"error\">User information not available. Please inform your system administrator.</p>";
		echo $error;
		include("../footer.php");
		exit;
	}
	// does this user exist?
	$error=CheckUsername($member_login,$prefix);
	if (trim($error) == "")
	{
		echo "<p class=\"error\">A user already exists with this login name.</p>\n";
		include("../footer.php");
		exit;
	}
	$new_pwd=md5($member_pwd1);
	$error = DBConnect ($mhost,$muser,$mpass,$mdb);
	if (trim($error) != "")
	{
		echo "<p class=\"error\">User not added</p>";
		echo $error;
		include("../footer.php");
		exit;
	}
	$tblname = QuoteSmart($prefix."saxon_users");
	$member_login = QuoteSmart($member_login);
	$new_pwd = QuoteSmart($new_pwd);
	$member_name = QuoteSmart($member_name);
	$member_status = QuoteSmart($member_status);
	$result = "INSERT INTO $tblname (USER_NAME,USER_PWD,FULL_NAME,SUPER_USER) VALUES ('$member_login','$new_pwd','$member_name','$member_status')";
	if (mysql_query($result))
	{
		echo "<p class=\"success\">User ".$member_login." added</p>";
		include("../footer.php");
		exit;
	}
	echo "<p class=\"error\">Error! User not added!</p>";
}

include("../footer.php");
?>