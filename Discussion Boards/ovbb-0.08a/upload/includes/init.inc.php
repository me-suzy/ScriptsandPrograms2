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

	// Set the global time.
	$CFG['globaltime'] = time();

	// Global functions
	require('functions.inc.php');

	// Set up our custom error handling.
	error_reporting(E_ERROR | E_WARNING | E_PARSE);
	set_error_handler('HandleError');

	// Disable those damned magic quotes, if need be.
	if(get_magic_quotes_gpc())
	{
		$_REQUEST = dmq($_REQUEST);
	}

	// Start timer.
	$tStartTime = getmicrotime();

	// Set the OvBB version.
	$CFG['version'] = '0.08a';

	// What is this page?
	$strCurrentPage = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/')+1, strlen($_SERVER['PHP_SELF']));
	$strCurrentPage = mysql_real_escape_string("$strCurrentPage?{$_SERVER['QUERY_STRING']}");

	// Grab the configuration settings.
	require('config.inc.php');

	// Initialize query count for generating statistics at footer.
	$iQueries = 0;
	$aQueries = array();

	// Initialize error handling.
	$aGlobalErrors = array();

	// Database settings
	require('db.inc.php');

	// Encode the page if the option is set.
	if($CFG['general']['gzip']['aggression_lvl'] == 1)
	{
		ob_start('ob_gzhandler');
	}

	// Initialize session.
	if(isset($_REQUEST['s']) && ($_REQUEST['s'] == ''))
	{
		unset($_REQUEST['s']);
	}
	ini_set('arg_separator.output', '&amp;');
	ini_set('session.cookie_path', pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME).'/');
	session_name('s');
	session_start();

	// Don't screw with our URLs, you crazy PHP you.
	output_reset_rewrite_vars();

	// Add the session ID to all local URLs.
	if(SID)
	{
		output_add_rewrite_var('s', stripslashes(session_id()));
	}

	// Get the user groups.
	require('usergroups.inc.php');

	// Initialize member settings, if user is logged in.
	$iIP = ip2long($_SERVER['REMOTE_ADDR']);
	$strLastLocation = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/')+1, strlen($_SERVER['PHP_SELF']));
	$strLastRequest = mysql_real_escape_string(serialize($_REQUEST));
	if($_SESSION['loggedin'])
	{
		$dateLastActive = $_SESSION['lastactive'];
		$CFG['time']['display_offset'] = $_SESSION['timeoffset'];
		$CFG['time']['dst'] = $_SESSION['dst'];
		$CFG['time']['dst_offset'] = $_SESSION['dstoffset'];

		// Load the permissions for this user.
		$aPermissions = $aGroup[$_SESSION['usergroup']];

		// Also update the user's lastactive, lastlocation, and ipaddress values in their profile.
		if(substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/')+1, strlen($_SERVER['PHP_SELF'])) != 'avatar.php')
		{
			sqlquery("UPDATE member SET lastactive={$CFG['globaltime']}, loggedin=1, lastlocation='$strLastLocation', lastrequest='$strLastRequest', ipaddress=$iIP WHERE id={$_SESSION['userid']}");
		}
	}
	else
	{
		// Does the user have our cookie?
		if(isset($_COOKIE['luserid']) && isset($_COOKIE['lpassword']))
		{
			// Yes, save the data.
			$iUserID = (int)$_COOKIE['luserid'];
			$strPassword = $_COOKIE['lpassword'];

			// Get the member information of the member whose user ID was specified.
			$sqlResult = sqlquery("SELECT * FROM member WHERE id=$iUserID");

			// Was the username of a real member?
			if($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
			{
				// Yes, so do the passwords match?
				if($aSQLResult['password'] == $strPassword)
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

					// Initialize startup values.
					$dateLastActive = $_SESSION['lastactive'];
					$CFG['time']['display_offset'] = $_SESSION['timeoffset'];
					$CFG['time']['dst'] = $_SESSION['dst'];
					$CFG['time']['dst_offset'] = $_SESSION['dstoffset'];

					// Load the permissions for this user.
					$aPermissions = $aGroup[$_SESSION['usergroup']];

					// Also update the user's lastactive, lastlocation, and ipaddress values in their profile.
					if($strLastLocation != 'avatar.php')
					{
						sqlquery("UPDATE member SET lastactive={$CFG['globaltime']}, loggedin=1, lastlocation='$strLastLocation', lastrequest='$strLastRequest', ipaddress=$iIP WHERE id={$_SESSION['userid']}");
					}
				}
			}
		}

		// Are they still not logged in?
		if(!$_SESSION['loggedin'])
		{
			// No. Load the permissions for this guest.
			$aPermissions = $aGroup[0];

			$_SESSION['showsigs'] = TRUE;
			$_SESSION['showavatars'] = TRUE;

			// User isn't logged in, so we'll add them to the session table.
			if(substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '/')+1, strlen($_SERVER['PHP_SELF'])) != 'avatar.php')
			{
				sqlquery("REPLACE INTO session(id, lastactive, lastlocation, lastrequest, ipaddress) VALUES('".session_id()."', {$CFG['globaltime']}, '$strLastLocation', '$strLastRequest', $iIP)");
			}

			// This will delete old (20+ minutes) session entries about every 100 times one is added.
			if(mt_rand(1, 100) == 50)
			{
				sqlquery('DELETE FROM session WHERE lastactive <= '.(int)($CFG['globaltime'] - 1200));
			}
		}
	}
?>
<?php
function dmq($given)
{
	return is_array($given) ? array_map('dmq', $given) : stripslashes($given);
}
?>