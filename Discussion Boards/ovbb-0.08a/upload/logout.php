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

	// Are we logged in?
	if($_SESSION['loggedin'])
	{
		// Yes.
		sqlquery("UPDATE member SET loggedin=0 WHERE id={$_SESSION['userid']}");
	}

	// Destroy the session.
	session_unset();
	session_destroy();

	// Delete any cookies.
	setcookie('luserid', '');
	setcookie('lpassword', '');

	// Display message page.
	Msg("<b>You have successfully logged out.</b><br /><br /><font class=\"smaller\">Click <a href=\"index.php\">here</a> to return to the forum index.</font>", 'index.php', 'center');
?>