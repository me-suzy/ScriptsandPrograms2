<?

////////////////////////////////////////////////////////////
//
// spiderCount v1.1 - a simple image-based hits counter
//
////////////////////////////////////////////////////////////
//
// This script increments a hits count every time it is
// called.  It also outputs the count as a JPEG image.
//
// See readme.txt for more information.
//
// Author: Jon Thomas <http://www.fromthedesk.com/code>
// Last Modified: 11/3/2005
//
// You may freely use, modify, and distribute this script.
//
////////////////////////////////////////////////////////////

//
// SET VARIABLES
//

// filename of hits count
$count_filename = "counter.txt";

// minimum number of digits to display
$min_digits = 0; // set to 0 to display hits count as is

// location of digit images
$digits_location = "images/";

// dimensions of digit images in pixels
$digit_width = 25;
$digit_height = 25;


//
// INCREMENT HITS COUNT
//

// open count file for reading only
$count_file = fopen($count_filename, "r");

// get current hits count
$hits_count = fgets($count_file, filesize($count_filename) + 1);

// increment hits count by one
$hits_count++;

// close count file
fclose($count_file);

// open count file for writing only
$count_file = fopen($count_filename, "w");

// write new hits count to count file
fwrite($count_file, $hits_count);

// close count file
fclose($count_file);


//
// OUTPUT HITS COUNT AS A JPEG IMAGE
//

// get number of digits in hits count
$no_digits = strlen($hits_count);

// use a minimum number of digits to display hits count if necessary
if ($no_digits < $min_digits) {
	// get number of zeroes to append to hits count
	$no_zeroes = $min_digits - $no_digits;

	// append zeroes to hits count
	for ($i = 0; $i < $no_zeroes; $i++) {
		$hits_count = "0" . $hits_count;
	}

	// get new number of digits in hits count
	$no_digits = $min_digits;
}

// send headers for JPEG image
header("Content-type: image/jpeg");

// create hits count image
$count_image = imagecreate($digit_width * $no_digits, $digit_height);

// add digit images to hits count image
for ($i = 0; $i < $no_digits; $i++) {
	// get digit in this part of hits count
	$digit = substr($hits_count, $i, 1);

	// get image for this digit
	$digit_image = imagecreatefromjpeg($digits_location . $digit . ".jpg");

	// get x-coordinate for placing this digit in count image
	$x = $digit_width * $i;

	// place digit image within count image
	imagecopymerge($count_image, $digit_image, $x, 0, 0, 0, $digit_width, $digit_height, 100);
}

// output hits count image
imagejpeg($count_image);

?>