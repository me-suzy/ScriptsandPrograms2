<?php
	// Start a session.
	session_start();
	function Error($error) {
		// General error handler. It's not very pretty, I know.
		require_once('template/error.htm');
		die();
	}
	function StripExt($file) {
        $tmp = explode('.', $file);
        return str_replace('.'. $tmp[count($tmp) -1], '', $file);
    }
	function ErrorImage($error) {
		global $font_files;
	    // First, we will create our image, with the specified width and height.
	    $img = imagecreatetruecolor(BOARD_WIDTH,BOARD_HEIGHT);
	    // Now we'll fill it with a grayish background. Leave this alone unless
	    // you changed it in the style sheet. If you have no idea what I just
	    // said just leave it alone. ;)
	    imagefill($img,0,0,imagecolorallocate($img,246,246,246));
	    // Now we load the background image.
	    $tile = imagecreatefromgif('images/'.BACKGORUND_IMAGE);
	    // We now tell GD that we want the tile image to be used as the tile
	    // image. >_>;;
	    imagesettile($img,$tile);
	    // Now we fill a rechtangle of the size of the image with that tile
	    // image. Voila: a good-looking background. :)
	    imageFilledRectangle($img, 0, 0, BOARD_WIDTH, BOARD_HEIGHT, IMG_COLOR_TILED);
		// Next we will get the coordinates of a box around the error message.
		// This gives us four points which we can use to calculate the width and
		// height of the string.
		$coords = imagettfbbox(14,0,'fonts/'. $font_files[0],$error);
		// Now we calculate the width and height of the string.
		$w = (($coords[0] * -1) + $coords[2]);
        $h = (($coords[1] * -1) + $coords[3]);
		// Finally, we calculate what coordinates would center it on the image.
		$x = intval(((BOARD_WIDTH - $w) / 2));
        $y = intval(((BOARD_HEIGHT - $h) / 2));
		// Draw it with the first available font. I can't rely on a fixed font
		// name here, because fonts can be changed now.
		imagettftext($img,14,0,$x,$y,imagecolorallocate($img,0,0,0),'fonts/'. $font_files[0],$error);
        // Tell the browser we're sending it a PNG image.
		header('Content-type: image/png',TRUE);
		// Send the image to the browser.
		imagepng($img);
        // Destroy the image.
		imagedestroy($img);
		// End execution.
		die();
	}
	function FontToFile($id) {
		global $font_files;
		// Return the filename of the font ID.
		return $font_files[$id];
	}
	function SizeToSize($id) {
		// Return the right font size. I chose not to include the font size
		// in the HTML template because that could be tampered with and you
		// would most likely end up with something like font size 700 entries
		// from trolls who just want to be annoying.
		switch ($id) {
			default:
				return FALSE;
				break;
			case 1:
				return 12;
				break;
			case 2:
				return 14;
				break;
			case 3:
				return 20;
				break;
		}
	}
	function HexToColor($color) {
		global $img;
		// Remove the # character from the given color.
		$color = substr($color,1);
		// Extract the RED, GREEN and BLUE hex values from the string, convert
		// them to integers, and allocate them in the image.
		$r = hexdec(substr($color,0,2));
        $g = hexdec(substr($color,2,2));
        $b = hexdec(substr($color,4,2));
		return imagecolorallocate($img,$r,$g,$b);
	}
	function MessageIsTooSimilar($message) {
        // If this is disabled, return FALSE.
        if (!USE_BLOCK) {
            return FALSE;
        }
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
        // Declare an array for future use.
	    $arr = array();
	    // Read each line. If the line is longer than 5KB, which it shouldn't
	    // be, there will be problems. Then start looping through the file,
	    // reading each row into the variable.
	   	while ($tmp = fgets($fp,5192)) {
	   		// Make an array of all the fields in the row.
	   		$tmp = explode(chr(0),$tmp);
	   		// Read the message of each line into that array we declared.
	   		$arr[] = $tmp[0];
	   	}
	   	// Remove the shared lock, if we set one
		if (!NO_FLOCK) {
		   	flock($fp,LOCK_UN);
		}
		// Close the message text file.
	   	fclose($fp);
        // Declare a float that will hold the sum of all percentages.
		$overallp = (float) 0;
		// Okay, we got all the messages in the array now. Now, we will check
		// the last X of them.
		for ($i = 0; $i < BLOCK_MSGNR; $i++) {
            // If we are at the end of the array (well, the beginning), exit the
            // loop to prevent trouble.
            if (count($arr) - ($i+1) < 0) {
                break;
            }
            // Do the check.
            similar_text($arr[count($arr) - ($i+1)], $message, $p);
            // Add the percentage.
            $overallp += $p;
		}
		// Now divide the overall percentage by how many times we looped and
        // round it to an ingeger. Not dividing by X is important since there
        // might be less than X messages.
		$overallp = intval(round(($overallp / BLOCK_MSGNR),0));
		// Depending if Y% is reached, return TRUE or FALSE.
		return ($overallp >= BLOCK_PERCENT);
    }
?>
