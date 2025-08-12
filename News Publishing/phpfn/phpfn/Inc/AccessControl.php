<?php

/*	+--------------------------------------------------------------
	| PHPFreeNews - News Headlines on your website                |
	| Developed by Jim Willsher.                                  |
	| http://www.phpfreenews.co.uk                                |
	+-------------------------------------------------------------+
*/

require_once('Functions.php');

// Start the session, if not already started
if (!session_id())
	session_start();

// Obtain the credentials, either from the buffer or from the session
$LoginUsername = isset($_POST['LoginUsername']) ? addslashes($_POST['LoginUsername']) : (isset($_SESSION['LoginUsername']) ? $_SESSION['LoginUsername'] : NULL);
$LoginPassword = isset($_POST['LoginPassword']) ? addslashes($_POST['LoginPassword']) : (isset($_SESSION['LoginPassword']) ? $_SESSION['LoginPassword'] : NULL);
$LoggedInFullName = "";

// Display Authentication Form?
if ($LoginUsername == NULL)
{
	include("LoginPage.html");
	exit;
}

// Update the session
$_SESSION['LoginUsername'] = $LoginUsername;
$_SESSION['LoginPassword'] = $LoginPassword;

//$sql = "SELECT * FROM news_users WHERE Username = '" . str_replace("\\'", "''", $LoginUsername) . "'";
$sql = "SELECT * FROM news_users WHERE Username = '". $LoginUsername . "'";

$ResultSet = mysql_query($sql);
if (!$ResultSet)
	 echo('A database error occurred while checking your login details.' . mysql_error());

$Row = mysql_fetch_array($ResultSet, MYSQL_ASSOC);

// Login is valid?
if ( (mysql_num_rows($ResultSet) == 1) && (md5($LoginPassword) == $Row['Password']) )
{
	$LoggedInUserId = $Row['ID'];
	$LoggedInAccessLevel = $Row['AccessLevel'];
	$LoggedInFullName = $Row['FullName'];
	$LoggedInEditAnyPost = $Row['EditAnyPost'];
	$LoggedInCanApprovePosts = $Row['CanApprovePosts'];
	$LoggedInCanChangeLock = $Row['CanChangeLock'];
	$LoggedInMustChangePassword = $Row['MustChangePassword'];
	$ErrorText = '';

	// Is the user now logged-in (as opposed to just re-verifying their login details)?
	if (! isset($_SESSION['LoggedIn']))
	{
		$_SESSION['LoggedIn'] = 'Y';
		WriteAuditEvent(AUDIT_TYPE_LOGIN, 'X', $LoggedInUserId, "User has logged in");
	}
}
else
{
	session_unregister('LoginUsername');
	session_unregister('LoginPassword');
	session_unregister('LoggedIn');
	include('AccessDenied.html');
	exit;
}
?>
