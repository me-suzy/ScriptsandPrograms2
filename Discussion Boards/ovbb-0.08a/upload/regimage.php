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

	// Configuration information.
	require('includes/config.inc.php');

	// Initialize session.
	if(isset($_REQUEST['s']) && ($_REQUEST['s'] == ''))
	{
		unset($_REQUEST['s']);
	}
	ini_set('arg_separator.output', '&amp;');
	ini_set('session.cookie_path', pathinfo($_SERVER['PHP_SELF'], PATHINFO_DIRNAME).'/');
	session_name('s');
	session_start();

	if(!isset($_SESSION['randstr']))
	{
		$strRandom = '';

		// We use only those characters and numbers
		// which can't be confused and are not wide.
		$strChars = '2346789ABCDEFGHJKLNPRTXYZ';

		// Generate a random string.
		for($i = 0; $i < 7; $i++)
		{
			$strRandom = $strRandom . $strChars[mt_rand(0, 24)];
		}
		$_SESSION['randstr'] = $strRandom;
	}

	// Set our foreground color to whatever the inverse of the background color is.
	list($fgRed, $fgGreen, $fgBlue) = sscanf(strtolower($CFG['style']['forum']['txtcolor']), '#%02x%02x%02x');

	// Set our background color to whatever the main table's cell A is.
	list($bgRed, $bgGreen, $bgBlue) = sscanf(strtolower($CFG['style']['table']['cella']), '#%02x%02x%02x');

	// Get the font.
	$font = realpath('entangled.ttf');

	// Create a 210x55 image.
	list(,, $iWidth,,,,,) = imagettfbbox(40, 0, $font, "«{$_SESSION['randstr']}»");
	$image = imagecreate(210, 45);

	$black = imagecolorallocate($image, $fgRed, $fgGreen, $fgBlue);

	// Fill it with the background color.
	imagefill($image, 0, 0, imagecolorallocate($image, $bgRed, $bgGreen, $bgBlue));

	// Write the string to the image.
	imagettftext($image, 40, 0, ((208-$iWidth)/2), 40, $black, $font, "«{$_SESSION['randstr']}»");

	// Send the image data.
	header('Content-type: image/png');
	header('Last-modified: '.gmdate('D, d M Y H:i:s').' GMT');
	imagepng($image);

	// Free the resource up.
	imagedestroy($image);
?>