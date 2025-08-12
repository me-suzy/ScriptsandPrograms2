<?php
/************************************************************************/
// SAXON: Simple Accessible XHTML Online News system
// Module: pwd-request.php
// Version 4.6
// Allow users to send an email request for a new password to Admin
// Developed by Black Widow
// Copyright (c) 2004 by Black Widow
// Support: www.forum.quirm.net
// Commercial Site: www.blackwidows.co.uk
/************************************************************************/
ob_start();

// stop errors on multiple session_start()
if(session_id() == ""){
  session_start();
}

header("Cache-control: private"); // IE 6 Fix.
include("functions.php");
include 'config.php';
include("header.php");
$scriptname=$_SERVER['PHP_SELF'];

?>
<h2>Request New Password</h2>
<div id="content" class="login">
<p>Send an email request to the SAXON Administrator.</p>
<form method="post" action="<?php echo $scriptname; ?>">
<p><label for="username">Username: </label><input type="text" name="username" id="username" /></p>
<input type="submit" class="submit button" value="Send Email" /> 
<input type="reset" class="button" value="Reset" /> 
</form>
<p class="request"><a href="login.php">Log into SAXON</a></p>

<?php
$auth=0;

if ($_POST) 
{
	$error = DBConnect ($mhost,$muser,$mpass,$mdb);
	$username = $_POST["username"];
	// check username exists
	$error  = CheckUsername($username,$prefix);
	// if yes, grab all details
	if ($error =="")
	{
		list($user_id,$full_name,$user_pwd,$user_status) = GetUser($username,$prefix);
		if ($user_status == "Y") $status = "Administrator";
		else $status = "Standard User";
		$toHeader = "SAXON Admin <".$admin_email.">";
		$fromHeader = "From: ".$username." <".$admin_email.">";
		$subject = "SAXON: New password request";
		$mail_body = "The following SAXON user has requested a new password via ".$uri.$path."login.php";
		$mail_body .= "\n\nSAXON user name: ".$username;
		$mail_body .= "\n\nFull name: ".$full_name;
		$mail_body .= "\n\nStatus ".$status;
		$mail_body .= "\n\nDate: ".date("d-m-Y")."\n";
		// Send the mail
		if(mail($toHeader,$subject,$mail_body,$fromHeader)) echo "<p class=\"success request\">Email sent!</p>";
		else echo "<p class=\"error request\">Email transmission failed. Please contact the site administrator.</p>";
	}
	else
	{
		echo "<div class=\"request\">".$error."</div>";
		include("footer.php");
		exit;
	}
}
include("footer.php");
ob_end_flush();

?>