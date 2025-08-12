<?php
	require_once('init.php');
    // If we don't have a query string, we'll error out because we need one with
    // the coordinates given to us.
	if ($_SERVER['QUERY_STRING'] == '') {
		Error('The position coordinates are missing.');
	} else {
		// Explode that string into an array with the two coordinates.
		$coordinates = explode(',',$_SERVER['QUERY_STRING']);
		// If there's not two coordinates in the array, Error out.
		if (count($coordinates) != 2) {
			Error('The position coordinates are missing');
		}
	}
    // Load the image of the crosshair.
	$spot = imagecreatefrompng('images/spot.png');
	// Downlod a copy of the current ScribbleBoard picture and get its size.
	$tmp = getimagesize(URL_TO_BOARD.'/board.php') or Error('Can\'t read image properties.');
	// Download a copy of the current ScribbleBoard picture and make a PNG image
	// resource out of it.
    $img = imagecreatefrompng(URL_TO_BOARD.'/board.php');
	// Get the magic divisor number.
	$md = (330/$tmp[0]);
	// Divide the X and Y values by our magic divisor number.
	$xval = intval($coordinates[0] * $md);
	$yval = intval($coordinates[1] * $md);
    // Calculate thumbnail width and height.
    $thumb_w = 330;
    $thumb_h = $tmp[1]*(330/$tmp[0]);
    // Create a true-color image with the thumbnail width and height.
    $dst = ImageCreateTrueColor($thumb_w,$thumb_h) or Error('Can\'t create new image.');
	// If we can antialias it, we will! >:O
	if (ANTIALIAS_OK) {
		imageantialias($dst,TRUE);
	}
    // Copy a resampled version of the current ScribbleBoard picture onto it,
    // with resized dimensions.
    imagecopyresampled($dst,$img,0,0,0,0,$thumb_w,$thumb_h,$tmp[0],$tmp[1]) or Error('Can\'t resample image.');
    // Copy the crosshair onto that image.
	imagecopy($dst,$spot,$xval,$yval,0,0,22,22);
    // Tell the browser we're sending it a PNG image.
    header('Content-type: image/png',TRUE);
    // Send the browser the PNG image.
    imagepng($dst);
    // Destory the loaded image, the returned thumbnail, and the crosshair.
	imagedestroy($img);
	imagedestroy($dst);
	imagedestroy($spot);
?>
