<?php
	require_once('init.php');
	global $font_files;
    // If we didn't get a font ID, we're going to have to error out of this one.
	if (!isset($_GET['font'])) {
		Error('Font ID is missing.');
	} else {
		// Set the font ID to the font ID. Make it an integer though, so trolls
		// can't mess around with ScribbleBoard.
		$fontid = intval($_GET['font']);
	}
    // Create a 200x30 truecolor image for the front preview.
	$img = imagecreatetruecolor(200,30);
	// If possible, enable antialiasing on this image.
	if (ANTIALIAS_OK) {
		imageantialias($img,TRUE);
	}
	// Fill the image with a white background.
	imagefill($img,0,0,imagecolorallocate($img,255,255,255));
	// Print out the standard preview text; some letters and two different
	// types of smiles.
	imagettftext($img,14,0,5,20,imagecolorallocate($img,0,0,0),'./fonts/'. $font_files[$fontid],'AaBbYyZz :O ^_^');
	// Tell the brosser we're going to send it a PNG image.
	header('Content-type: image/png',TRUE);
	// Send the PNG image to the browser.
	imagepng($img);
	// Destroy the image.
	imagedestroy($img);
?>
