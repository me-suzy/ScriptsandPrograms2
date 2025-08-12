<?php

/*****************************************
* File      :   $RCSfile: functions.api.images.php,v $
* Project   :   Contenido
* Descr     :   Contenido Image API functions
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   08.08.2003
* Modified  :   $Date: 2003/08/19 10:12:21 $
*
* © four for business AG, www.4fb.de
*
* $Id: functions.api.images.php,v 1.4 2003/08/19 10:12:21 timo.hummel Exp $
******************************************/

/* Info:
 * This file contains Contenido Image API functions.
 *
 * If you are planning to add a function, please make sure that:
 * 1.) The function is in the correct place
 * 2.) The function is documented
 * 3.) The function makes sense and is generically usable
 *
 */


/**
 * capiImgScale: Scales (or crops) an image.
 * If scaling, the aspect ratio is maintained.
 *
 * Returns the path to the scaled temporary image.
 *
 * Note that this function does some very poor caching;
 * it calculates an md5 hash out of the image plus the
 * maximum X and Y sizes, and uses that as the file name.
 * If the file is older than 10 minutes, regenerate it.
 *
 * @param $img string The path to the image (relative to the frontend)
 * @param $maxX int The maximum size in x-direction
 * @param $maxY int The maximum size in y-direction
 * @param $crop boolean If true, the image is cropped and not scaled. NOTE: Not implemented yet
 *
 * @return string Path to the resulting image
 */
function capiImgScale ($img, $maxX, $maxY, $crop = false)
{
	global $cfgClient, $lang, $client;

	$filename = $img;
	$defaultCacheTime = 10;
	
	$filetype = substr($filename, strlen($filename) -4,4);

	$md5 = md5($filename . $maxX . $maxY);
	
	/* Create the target file names for web and server */
	$cfileName = $md5.".png";
	$cacheFile = $cfgClient[$client]["path"]["frontend"]."cache/".$cfileName;
	$webFile = $cfgClient[$client]["path"]["htmlpath"]."cache/".$cfileName;
	
	/* Check if the file exists. If it does, check if the file is valid. */
	if (file_exists($cacheFile))
	{
		if ((filemtime($cacheFile) + (60 * $defaultCacheTime)) < time())
		{
			/* Cache time expired, unlink the file */
			unlink($cacheFile);	
		} else {
			/* Return the web file name */
			return $webFile;
		}
	}
	
	/* Get out which file we have */
	switch ($filetype)
	{
		case ".gif": $imageHandle = @imagecreatefromgif($filename); break;
		case ".png": $imageHandle = @imagecreatefrompng($filename); break;
		case ".jpg": $imageHandle = @imagecreatefromjpeg($filename); break;
		case "jpeg": $imageHandle = @imagecreatefromjpeg($filename); break;
		default: return false;
	}
	
	/* If we can't open the image, return false */
	if (!$imageHandle)
	{
		return false;
	}


	$x = imagesx($imageHandle);
	$y = imagesy($imageHandle);

	/* Calculate the aspect ratio */	
	$aspectXY = $x / $y;
	$aspectYX = $y / $x;
	
	/* Check which axis has the higher aspect ratio */
	if (($maxX / $x) < ($maxY / $y))
	{
		$targetY = $y * ($maxX / $x);
		$targetX = $maxX;
	} else {
		$targetX = $x * ($maxY / $y);
		$targetY = $maxY;
	}

	/* Round the target X/Y sizes */
	$targetX = round($targetX);
	$targetY = round($targetY);

	/* Create the target image with the target size, resize it afterwards. */
	$targetImage = imagecreate($targetX, $targetY);

	imagecopyresized($targetImage, $imageHandle, 0, 0, 0, 0, $targetX, $targetY, $x, $y);
	
	/* Output the file */
	imagepng($targetImage, $cacheFile);
	
	return ($webFile);
}