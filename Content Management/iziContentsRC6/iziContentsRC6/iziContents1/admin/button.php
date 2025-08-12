<?php

/***************************************************************************

 button.php
 -----------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/


include_once ("rootdatapath.php");

$savefile	= urldecode($_GET["save"]);
$template	= $_GET["template"];
$text		= urldecode($_GET["text"]);
$textColour = $_GET["textcolour"];
$align		= $_GET["align"];
$valign		= $_GET["valign"];
$tFont		= $_GET["font"];
$tFontSize  = $_GET["fontsize"];


//  Determine what image types are available to us. .gif is the preferred format,
//		with .jpg and .png as second and third choices respectively
if (ImageTypes() & IMG_PNG) $type = 'png';
if (ImageTypes() & IMG_JPG) $type = 'jpg';
if (ImageTypes() & IMG_GIF) $type = 'gif';
$savefile .= '.'.$type;

//  See if we have a template file in the appropriate style subdirectory;
//		if not, we use a default
$style = False;
if ($GLOBALS["gsAdminStyle"] != '') {
	$template_image = $GLOBALS["rootdp"].$GLOBALS["style_home"].$GLOBALS["gsAdminStyle"].'/'.$template.".".$type;
	if (file_exists($template_image)) $style = True;
}
if (!$style) {
	$template_image = $GLOBALS["rootdp"].$GLOBALS["style_home"].$template.".".$type;
	if (file_exists($template_image)) $style = True;
}

if ($style) {
	$sourceImageSize = getImageSize($template_image);
	$sourceImageWidth  = $sourceImageSize[0];
	$sourceImageHeight = $sourceImageSize[1];
	//  Create our basic button from the template
	switch ($type) {
		case 'gif' : $im = @ImageCreateFromGif($template_image);
					 break;
		case 'jpg' : $im = @ImageCreateFromJpeg($template_image);
					 break;
		case 'png' : $im = @ImageCreateFromPng($template_image);
					 break;
	} // end switch
}


//  If we couldn't create a button from the template, we generate a
//			simple image as a straight block of colour
if ((!$style) || (!$im)) {
	$sourceImageWidth  = 150;
	$sourceImageHeight = 30;
	$im  = ImageCreate($sourceImageWidth,$sourceImageHeight);
	$bgc = ImageColorAllocate($im, 255, 0, 0);
	ImageFilledRectangle($im,0,0,$sourceImageWidth,$sourceImageHeight,$bgc);
}


//  Add our text to the template button image
//  set up the text colour
$textColour = str_replace('#','',$textColour);
$tTextColour = imageColorAllocate($im, hexdec(substr($textColour,0,2)), hexdec(substr($textColour,2,2)), hexdec(substr($textColour,4,2)));

// We need an absolute directory reference in Unix format for truetype fonts
$savedir = getcwd();
chdir($GLOBALS["rootdp"].$GLOBALS["font_home"]);
$fontdir = getcwd();
chdir($savedir);
$fontdir = str_replace('\\','/', $fontdir);
if (substr($fontdir,1,1) == ':') { $fontdir = substr($fontdir,2,strlen($fontdir) - 2); }
$tTextFont = $fontdir.'/'.$tFont.'.ttf';


$truetype = False;
if (file_exists($tTextFont)) {
	$truetype = True;
	// this determines how big the text box will be
	$textBox  = imageTTFBBox($tFontSize, 0, $tTextFont, $text);
	$textBoxWidth  = abs($textBox[2] - $textBox[0]);
	$textBoxHeight = abs($textBox[5] - $textBox[3]);
	// Truetype doesn't work correctly with GD2 on windows, so we trap for it here.
	// If imageTTFBBox doesn't return a valid value, we use the GD internal fonts instead.
	if ($textBoxWidth < 1) { $truetype = False; }
	if ($truetype) {
		// this positions the text on the image
		switch ($align) {
			case 'center' : $textXPos = ($sourceImageWidth - $textBoxWidth) / 2;
							break;
			case 'right'  : $textXPos = ($sourceImageWidth - $textBoxWidth) - 5;
							break;
			default		  : $textXPos = 5;
							break;
		} // end switch
		switch ($valign) {
			case 'bottom' : $textYPos = $sourceImageHeight - 5;
							break;
			case 'top'	  : $textYPos = $textBoxHeight + 5;
							break;
			default		  : $textYPos = (($sourceImageHeight - $textBoxHeight) / 2) + $textBoxHeight;
							break;
		} // end switch
		// place the text on the image
		imageTTFText($im,$tFontSize,0,$textXPos,$textYPos,$tTextColour,$tTextFont,$text);
	}
}
if (!$truetype) {
	$tFontSize = floor($tFontSize / 3);
	if ($tFontSize < 1) { $tFontSize = 1; }
	if ($tFontSize > 5) { $tFontSize = 5; }
	// this determines how large the text be
	$textBoxWidth  = abs(ImageFontWidth($tFontSize)) * strlen($text);
	$textBoxHeight = abs(ImageFontHeight($tFontSize));
	// this positions the text on the image
	switch ($align) {
		case 'center' : $textXPos = ($sourceImageWidth - $textBoxWidth) / 2;
						break;
		case 'right'  : $textXPos = ($sourceImageWidth - $textBoxWidth) - 5;
						break;
		default			: $textXPos = 5;
						break;
	} // end switch
	switch ($valign) {
		case 'bottom' : $textYPos = ($sourceImageHeight - $textBoxHeight) - 5;
						break;
		case 'top'	  : $textYPos = 5;
						break;
		default		  : $textYPos = ($sourceImageHeight - $textBoxHeight) / 2;
						break;
	} // end switch
	ImageString($im,$tFontSize,$textXPos,$textYPos,$text,$tTextColour);
}

// Output the completed button
switch ($type) {
  case 'gif' :	ImageGif($im,$savefile);
				Header("Content-type: image/gif");
				ImageGif($im);
				break;
  case 'jpg' :	ImageJpeg($im,$savefile);
				Header("Content-type: image/jpeg");
				ImageJpeg($im);
				break;
  case 'png' :	ImagePng($im,$savefile);
				Header("Content-type: image/png");
				ImagePng($im);
				break;
} // end switch

ImageDestroy($im);

?>