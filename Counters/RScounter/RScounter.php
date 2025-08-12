<?php
/************************************
	DEVELOPED BY REDSHIFT SOFTWARE
	HTTP://SOFTWARE.REDSHIFT.JE
************************************/	


$file = "count.txt";			// Filename of counter
$img_ext = "gif";				// Image file extension
$img_path = "images/digits/";	// Image path
$min = 0;						// Minimum digits to display. 0 = Default = Only Required Digits

// DO NOT EDIT BELOW THIS POINT! <-------------------------------------------------------

// Find the current hit count and increment it
$fcount = fopen($file, "r");
$count = fread($fcount, filesize($file));
fclose($fcount);
$count++;

// Save new hits value
$fcount = fopen($file, "w");
fputs($fcount, $count);
fclose($fcount);

// Number of digits in the hit count
$digits = strlen($count);

// Add leading zeros if required
if ($min && $digits < $min) {
	$difference = $min - $digits;
	for ($i = 0; $i < $difference; $i++) {
		$count = "0" . $count;
	}
	$digits = $min;
}

// Output each digit image for the counter
for ($i = 0; $i < $digits; $i++) {
	$digit = substr($count, $i, 1);
	echo "<img src=$img_path.$digit.$img_ext>";
}

?>