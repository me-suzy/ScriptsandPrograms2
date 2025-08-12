<?php
/*Generates a Countdown Image in PNG format from JPG, GIF, PNG pictures as
* as well as creating solid graphic backgrounds if desired by the person
*/

include("functions.php");

// Read the data.dat file for countdown info
include("filereader.php");
$date_array = explode('-', $date);
$date = mktime(0,0,0, $date_array[0], $date_array[1], $date_array[2]);

if($picture){
	// Get Image Info Array
	$image_array = getimagesize ($filename);
	
	// Switch on IMAGETYPE to choose the correct functions. ¿! Need a default case!?
	switch($image_array[2]){
		case IMAGETYPE_JPEG:
			header("Content-type: ".image_type_to_mime_type($image_array[2]));
			$im = ImageCreateFromJPEG($filename) or die ("Cannot Initialize new GD image stream");
		break;
		case IMAGETYPE_GIF:
			header("Content-type: ".image_type_to_mime_type($image_array[2]));
			$im = ImageCreateFromGIF($filename) or die ("Cannot Initialize new GD image stream");
		break;
		case IMAGETYPE_PNG:
			header("Content-type: ".image_type_to_mime_type($image_array[2]));
			$im = ImageCreateFromPNG($filename) or die ("Cannot Initialize new GD image stream");
		break;
	}
} else {
	$im = ImageCreate (400, 300) or die ("Cannot Initialize new GD image stream");
	$i_bgcolor = colorCreator($im, $bgcolor);
	imagefill ($im, 0, 0, $i_bgcolor);

}


$i_txtcolor = colorCreator($im, $txtcolor);
$i_shadowcolor = colorCreator($im, $shadowcolor);
$i_bordercolor = colorCreator($im, $bordercolor);

if(mktime() < $date){
		$difference = $date - mktime();
} else{
	$difference = 0;
}
$days = (int)($difference/86400);
$temp_date = $difference % 86400;
$hours = (int)($temp_date/3600);
$temp_date = $temp_date % 3600;
$minutes = (int)($temp_date/60);
$seconds = $temp_date % 60;

$date = "$days Days, $hours Hours, $minutes Minutes, $seconds Seconds";

// Write the strings to the image. 
if($dropshadow){
	ImageTTFText($im, $font_size, 0, ($xpos + 2), ($ypos + 2), $i_shadowcolor, "fonts/Times New Roman Bold.ttf", $text);
	ImageTTFText($im, $font_size, 0, ($xpos + $xposoff + 2), ($ypos + $yposoff + 2), $i_shadowcolor, "fonts/Times New Roman Bold.ttf", $date);
}
ImageTTFText($im, $font_size, 0, $xpos, $ypos, $i_txtcolor, "fonts/Times New Roman Bold.ttf", $text);
ImageTTFText($im, $font_size, 0, ($xpos + $xposoff), ($ypos + $yposoff), $i_txtcolor, "fonts/Times New Roman Bold.ttf", $date);

imagepng($im);

imagedestroy($im);



?>


<?php # stay inside the bounds
/*$newwidth = min ($maxwidth, $width);
$newheight = min ($maxheight, $height);
$prop = $width / $height;
if ($prop > 1) # landscape
  $newheight = $height * $newwidth / $width;
elseif ($prop < 1) # portrait
  $newwidth = $width * $newheight / $height;
*/

//int imagefontwidth ( int font)
?> 