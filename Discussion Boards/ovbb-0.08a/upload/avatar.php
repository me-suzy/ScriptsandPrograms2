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

	// Who's avatar do they want?
	$iUserID = (int)$_REQUEST['userid'];

	// Get the avatar information.
	$sqlResult = sqlquery("SELECT * FROM avatar WHERE id=$iUserID");
	$aSQLResult = mysql_fetch_row($sqlResult);

	// Does this user have an avatar?
	if((!$aSQLResult) || (!$_SESSION['showavatars']))
	{
		// No, so send them a blank image.
		$strAvatarData = file_get_contents('images/space.png');
		header('Last-modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Content-disposition: inline; filename="space.png"');
		header('Content-length: '.strlen($strAvatarData));
		header('Content-type: image/png');
		echo($strAvatarData);
		exit;
	}

	// What type is this avatar?
	if($aSQLResult[1])
	{
		// Custom avatar.
		$strFilename = $aSQLResult[1];
		$strAvatarData = $aSQLResult[2];
	}
	else
	{
		// Public avatar.
		require('includes/avatars.inc.php');
		$strFilename = $aAvatars[$aSQLResult[2]]['filename'];
		$strAvatarData = file_get_contents($CFG['paths']['avatars'].$strFilename);
	}

	// So it's not cached...
	header('Last-modified: '.gmdate('D, d M Y H:i:s').' GMT');

	// Tell them the filename.
	header('Content-disposition: inline; filename="'.$strFilename.'"');

	// Tell them how big the attachment is.
	header('Content-length: '.strlen($strAvatarData));

	// Tell them what kind of file it is.
	switch(strtolower(substr(strrchr($strFilename, "."), 1)))
	{
		// BMP
		case 'bmp':
		{
			header('Content-type: image/bmp');
			break;
		}

		// GIF
		case 'gif':
		{
			header('Content-type: image/gif');
			break;
		}


		// JPG
		case 'jpg':
		case 'jpeg':
		{
			header('Content-type: image/jpeg');
			break;
		}

		// PNG
		case 'png':
		{
			header('Content-type: image/png');
			break;
		}
	}

	// Send the file.
	echo($strAvatarData);
?>