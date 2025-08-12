<?php
//***************************************************************************//
//                                                                           //
//  Copyright (c) 2004-2005 Jonathon J. Freeman                              //
//  All rights reserved.                                                     //
//                                                                           //
//  This program is free software. You may use, modify, and/or redistribute  //
//  it under the terms of the OvBB License Agreement v2 as published by the  //
//  OvBB Project at www.ovbb.org.                                            //
//                                                                           //
//***************************************************************************//

	// Initialize OvBB.
	require('includes/init.inc.php');

	// Are they already logged in?
	if($_SESSION['loggedin'])
	{
		LoggedIn();
	}

	// Are they coming for the first time?
	if(!$_REQUEST['username'])
	{
		// Yes, so give them the login page.
		require('includes/login.inc.php');
		exit;
	}

	// Grab the values (if any) the user posted.
	$strPostedUsername = mysql_real_escape_string($_REQUEST['username']);
	$strPostedPassword = $_REQUEST['password'];

	// Get the member information of the member whose username was specified.
	$sqlResult = sqlquery("SELECT * FROM member WHERE username='$strPostedUsername'");

	// Was the username of a real member?
	if($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
	{
		// Yes, so do the passwords match?
		if($aSQLResult['password'] == md5($strPostedPassword))
		{
			// Yes. We're logged in.
			$_SESSION['loggedin'] = TRUE;

			// Store the member information into the session.
			$_SESSION['userid'] = $aSQLResult['id'];
			$_SESSION['username'] = $aSQLResult['username'];
			$_SESSION['password'] = $aSQLResult['password'];
			$_SESSION['autologin'] = $aSQLResult['autologin'];
			$_SESSION['showsigs'] = $aSQLResult['showsigs'];
			$_SESSION['showavatars'] = $aSQLResult['showavatars'];
			$_SESSION['threadview'] = $aSQLResult['threadview'];
			$_SESSION['postsperpage'] = $aSQLResult['postsperpage'] ? $aSQLResult['postsperpage'] : $CFG['default']['postsperpage'];
			$_SESSION['threadsperpage'] = $aSQLResult['threadsperpage'] ? $aSQLResult['threadsperpage'] : $CFG['default']['threadsperpage'];
			$_SESSION['weekstart'] = $aSQLResult['weekstart'];
			$_SESSION['timeoffset'] = $aSQLResult['timeoffset'];
			$_SESSION['dst'] = (bool)$aSQLResult['dst'];
			$_SESSION['dstoffset'] = (int)$aSQLResult['dstoffset'];
			$_SESSION['lastactive'] = $aSQLResult['lastactive'];
			$_SESSION['usergroup'] = $aSQLResult['usergroup'];

			// Delete any guest entries from the session table.
			sqlquery('DELETE FROM session WHERE id=\''.session_id().'\'');

			// Do they wanna be remembered?
			if($aSQLResult['autologin'])
			{
				setcookie('luserid', $_SESSION['userid'], $CFG['globaltime']+2592000, pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME).'/');
				setcookie('lpassword', $aSQLResult['password'], $CFG['globaltime']+2592000, pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME).'/');
			}

			// Show them the success page.
			LoggedIn();
		}
		else
		{
			// No. Give them an error page.
			ErrorPage(TRUE);
		}
	}
	else
	{
		// No. Give them an error page.
		ErrorPage(FALSE);
	}
?>
<?php
function ErrorPage($bValidUsername)
{
	global $CFG;

	// What's the error?
	if($bValidUsername)
	{
		// They specified a valid username but the wrong password.
		$strError = 'Wrong password specified.';
	}
	else
	{
		// They specified an invalid ussername, let alone password.
		$strError = 'Invalid username specified.';
	}

	// Display message page.
	Msg("$strError Please go back and try again. Click <a href=\"forgotdetails.php\">here</a> if you've forgotten your member details.");
}

function LoggedIn()
{
	global $CFG;

	$strRedirect = urldecode($_REQUEST['redirect']);
	if(!$strRedirect)
	{
		$strRedirectURL = $strRedirect = 'index.php';
	}
	else
	{
		$strRedirectURL = str_replace('&', '&amp;', $strRedirect);
	}

	$strUsername = htmlspecialchars($_SESSION['username']);
	Msg("<b>Thank you for logging in, {$strUsername}.</b><br /><br /><font class=\"smaller\">You should be redirected momentarily. Click <a href=\"{$strRedirectURL}\">here</a><br />if you do not want to wait any longer or if you are not redirected.</font>", $strRedirect, 'center');
}
?>