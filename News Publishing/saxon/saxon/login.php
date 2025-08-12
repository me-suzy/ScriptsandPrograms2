<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: login.php
// Version 4.6
// Developed by Black Widow
// Copyright (c) 2004 by Black Widow
// Support: www.forum.quirm.net
// Commercial Site: www.blackwidows.co.uk
/************************************************************************/
ob_start();

// stop errors on multiple session_start()
if(session_id() == "") {
	session_start();
}

header("Cache-control: private"); // IE 6 Fix.
include("functions.php");
include 'config.php';
include("header.php");
$scriptname=$_SERVER['PHP_SELF'];

?>
<div id="content" class="login">
<form method="post" action="<?php echo $scriptname; ?>">
<p><label for="username">Username:</label> <input type="text" name="username" id="username" /></p>
<p><label for="password">Password:</label> <input type="password" name="password" id="password" /></p>
<input type="submit" class="submit button" value="Login" />
</form>
<p class="request"><a href="pwd-request.php">Request new password</a></p>
<?php
$auth=0;

if ($_POST) 
{
	$error = DBConnect ($mhost,$muser,$mpass,$mdb);
	$error = LoginCheck($_POST,$prefix);
	if (trim($error)=="") 
	{
		list($_SESSION["member_id"],$_SESSION['full_name'],$_SESSION['super_user']) = UserLogin($_POST,$prefix);
		header("Location: ".$uri.$path."add.php");
		exit;
	}
	else
	{
		echo "<p class=\"error\">$error</p>";
		include("footer.php");
		exit;
	}
}
include("footer.php");
ob_end_flush();

?>