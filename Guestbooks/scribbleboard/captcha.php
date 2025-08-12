<?php
	require_once('init.php');
    // Tell the browser we're sending it a PNG
	header("Content-type: image/png");
    // Create an image.
    $im = imagecreate(121, 31);
    // Initalize a temp. variable to make the PHP parser happy.
    $i = 0;
    // Create whiteness.
    $back = imagecolorallocate($im, 255, 255, 255);
	// Create gr(a|e)yness for the grid we're going to draw later.
	$gcolor = imagecolorallocate($im,204,204,204);
    // Fill the image with the whiteness we just created.
    imagefill($im,0,0,$back);
    // Create a random dark-ish color.
	$color = imagecolorallocate($im, rand(0,150), rand(0,150), rand(0,150));
	// Find the text from our captcha code session.
    $text = $_SESSION['code'];
	// Draw the text.
    imagettftext($im, 14, 0, 10, 20, $color, 'fonts/CPT.TTF', $text);
	// Create a grid.
    do {
		imagerectangle($im,$i,0,$i,30, $gcolor);
		$i+= 10;
	} while ($i<130);
	// Reset the temp. variable.
    $i =0;
    // Create another grid.
    do {
		imagerectangle($im,0,$i,150,$i, $gcolor);
		$i+= 5;
	} while ($i<40);
    // Send the image to the browser. Not the most secure captcha possible,
    // but meh; I'm only human.
	imagepng($im);
	// Delete the image from memory.
	imagedestroy($im);
?>
