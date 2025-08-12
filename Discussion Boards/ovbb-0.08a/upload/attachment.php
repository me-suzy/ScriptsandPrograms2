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

	// Do they have authorization to view this attachment?
	if(!$aPermissions['cviewattachments'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// What attachment do they want?
	$iAttachmentID = (int)$_REQUEST['id'];

	// Get the avatar filename.
	$sqlResult = sqlquery("SELECT filename, filedata FROM attachment WHERE id=$iAttachmentID");
	$aSQLResult = mysql_fetch_row($sqlResult);

	// Tell them the filename.
	header('Content-disposition: inline; filename="'.$aSQLResult[0].'"');

	// Tell them how big the attachment is.
	header('Content-length: '.strlen($aSQLResult[1]));

	// Tell them what kind of file it is.
	switch(strtolower(substr(strrchr($aSQLResult[0], "."), 1)))
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

		// ZIP
		case 'zip':
		{
			header('Content-type: application/zip');
			break;
		}

		// RAR
		case 'rar':
		{
			header('Content-type: application/x-rar-compressed');
			break;
		}

		// GZIP
		case 'gz':
		case 'gzip':
		{
			header('Content-type: application/x-gzip');
			break;
		}

		// 7ZIP
		case '7z':
		{
			header('Content-type: application/x-7z-compressed');
			break;
		}

		// TXT
		case 'txt':
		{
			header('Content-type: text/plain');
			break;
		}

		default:
		{
			header('Content-type: unknown/unknown');
			break;
		}
	}

	// Send the file.
	echo($aSQLResult[1]);

	// Update the attachment's viewcount.
	sqlquery("UPDATE attachment SET viewcount=viewcount+1 WHERE id=$iAttachmentID");
?>