<?php
	require_once('init.php');
    // First, we will create our image, with the specified width and height.
	$img = imagecreatetruecolor(BOARD_WIDTH,BOARD_HEIGHT);
    // If possible, enable antialiasing for that image.
	if (ANTIALIAS_OK) {
		imageantialias($img,TRUE);
	}
    // Now we'll fill it with a grayish background. Leave this alone unless
    // you changed it in the style sheet. If you have no idea what I just said
    // just leave it alone. ;)
	imagefill($img,0,0,imagecolorallocate($img,246,246,246));
	// Now we load the background image.
	$tile = imagecreatefromgif('images/'.BACKGORUND_IMAGE);
	// We now tell GD that we want the tile image to be used as the tile image. >_>;;
	imagesettile($img,$tile);
	// Now we fill a rechtangle of the size of the image with that tile image.
	// Voila: a good-looking background. :)
	imageFilledRectangle($img, 0, 0, BOARD_WIDTH, BOARD_HEIGHT, IMG_COLOR_TILED);
	// If the filesize of the message text file is 0, then there is nothing in
	// it... I hope. >_>;
	if (filesize(STORE_PATH. '/messages.txt') != 0) {
		// Open the message textfile for reading. If that doesn't work, error out.
		$fp = fopen(STORE_PATH. '/messages.txt','r') or Error('Unable to open &quot;messages.txt&quot;');
		// If we are allowed to lock the file, lock it with a shared lock. This
		// will allow others to read it, but not to write to it, which is just
		// what we need. :D
		if (!NO_FLOCK) {
	        $cnt = 0;
	        while (!flock($fp,LOCK_SH)) {
	            if ($cnt = 4) {
	                fclose($fp);
	                Error('Unable to get a shared lock for &quot;messages.txt&quot; after 5 tries.');
	            } else {
	                $cnt++;
	                sleep(1);
	            }
	        }
	    }
	    // Read each line. If the line is longer than 5KB, which it shouldn't
	    // be, there will be problems. Then start looping through the file,
	    // reading each row into the variable.
	   	while ($tmp = fgets($fp,5192)) {
	   		// Make an array of all the fields in the row.
	   		$tmp = explode(chr(0),$tmp);
	   		// Draw the text with the parameters.
	   		imagettftext($img,SizeToSize($tmp[2]),$tmp[4],$tmp[5],$tmp[6],HexToColor($tmp[3]),'./fonts/'. FontToFile($tmp[1]),$tmp[0]);
	   	}
	   	// Remove the shared lock, if we set one
		if (!NO_FLOCK) {
		   	flock($fp,LOCK_UN);
		}
		// Close the message text file.
	   	fclose($fp);
	} else {
		// Show a centered error message as a graphic, saying theres no messages.
		ErrorImage('No messages yet. ;_;');
	}
    // Tell the browser we're sending it a PNG image.
	header('Content-type: image/png',TRUE);
	// Send the PNG file to the browser.
	imagepng($img);
	// Remove the PNG file from memory.
    imagedestroy($img);
?>
