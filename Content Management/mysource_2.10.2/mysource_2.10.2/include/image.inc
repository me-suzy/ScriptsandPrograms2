<?  ##############################################
   ### MySource ------------------------------###
  ##- Include Files ------ PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: include/image.inc
## Desc: Functions for manipulating images
## $Source: /home/cvsroot/mysource/include/image.inc,v $
## $Revision: 2.10 $
## $Author: csmith $
## $Date: 2003/05/16 05:17:58 $
#######################################################################
global $SQUIZLIB_PATH;
require_once("$SQUIZLIB_PATH/colour/colour.inc");
#---------------------------------------------------------------------#

    ##################################################################
   # Takes a link to a GIF file with an (assumed) greyscale palette #
  # Then replaces the Black<->White with ColourA<->ColourB         #
 # And outputs an a new file                                      #
##################################################################

function image_gradient_palette($input='',$output='',$colour_a='',$colour_b='') {
	if (!$input || !$output || !$colour_a || !$colour_b) return;
	# Open image from file
	$file_type = get_file_type($input);
	switch($file_type) {
		case 'gif':
			if (!$image = ImageCreateFromGif($input)) report_error(__FILE__,__LINE__,"Unable to open GIF for recolourizing: $input");
			break;
		case 'png':
			if (!$image = ImageCreateFromPNG($input)) report_error(__FILE__,__LINE__,"Unable to open PNG for recolourizing: $input");
			break;
		case 'jpg': case 'jpeg':
			if (!$image = ImageCreateFromJPEG($input)) report_error(__FILE__,__LINE__,"Unable to open JPEG for recolourizing: $input");
			break;
		default:
			report_error(__FILE__,__LINE__,"Unable to open unknown image type for recolourizing: $input");
			break;
	}

	# Anylize colours and calculate difference colour
	$ca_array = Colour::html_to_rgb($colour_a);
	$cb_array = Colour::html_to_rgb($colour_b);
	$diff = array(
		r => ($cb_array['r'] - $ca_array['r']),
		g => ($cb_array['g'] - $ca_array['g']),
		b => ($cb_array['b'] - $ca_array['b'])
	);

	# Get the number of colours in a palette
	$palette_size = imageColorsTotal($image);
	# Replace colours in palette
	for ($i = 0; $i < $palette_size; $i++) {
		# because the image is greyscale each value for the R, G & B will be the same
		# therefore to find the percentage white this index is we divide by 256
		$colour = imagecolorsforindex ($image, $i);
		$fraction = (double) ($colour['red'] / 256);

		$r = (int) (255 * ($ca_array['r'] + $diff['r'] * $fraction));
		$g = (int) (255 * ($ca_array['g'] + $diff['g'] * $fraction));
		$b = (int) (255 * ($ca_array['b'] + $diff['b'] * $fraction));
		imagecolorset($image,$i, $r, $g, $b);
	}

	# Output result to file
	switch($file_type) {
		case 'gif':
			if (!imageGIF($image, $output)) report_error(__FILE__,__LINE__,"Unable to output GIF after recolourizing: $output");
			break;
		case 'png':
			if (!imagePNG($image, $output)) report_error(__FILE__,__LINE__,"Unable to output PNG after recolourizing: $output");
			break;
		case 'jpg': case 'jpeg':
			if (!imageJPEG($image, $output)) report_error(__FILE__,__LINE__,"Unable to output JPEG after recolourizing: $output");
			break;
		default:
			report_error(__FILE__,__LINE__,"Unable to open unknown image type for recolourizing: $input");
			break;
	}
	imageDestroy($image);
}


  ########################################################################
 # Takes a link to a GIF file, and output to another GIF file.
# $colour_map is an associative array. It find the "key" colour in the image
# and replaces if with the "value" colour.
# The colours are in the form of HTML colour codes e.g. "5627e8", "goldenrod"
function image_remap_colour($input='',$output='',$colour_map='',$tolerance='') {
	if (!$input || !$output || !$colour_map || !$tolerance) return;
	# Open image from file
	$file_type = get_file_type($input);
	switch($file_type) {
		case 'gif':
			if (!$image = ImageCreateFromGif($input)) report_error(__FILE__,__LINE__,"Unable to open GIF for recolourizing: $input");
			break;
		case 'png':
			if (!$image = ImageCreateFromPNG($input)) report_error(__FILE__,__LINE__,"Unable to open PNG for recolourizing: $input");
			break;
		case 'jpg': case 'jpeg':
			if (!$image = ImageCreateFromJPEG($input)) report_error(__FILE__,__LINE__,"Unable to open JPEG for recolourizing: $input");
			break;
		default:
			report_error(__FILE__,__LINE__,"Unable to open unknown image type for recolourizing: $input");
			break;
	}

	# Get the number of colours in a palette
	$palette_size = imageColorsTotal($image);

	# Replace colours in palette
	for ($i = 0; $i < $palette_size; $i++) {
		$colour = imagecolorsforindex ($image, $i);
		# Convert to HTML colour
		$colour = Colour::rgb_to_html(Colour::rgb($colour['red'],$colour['green'],$colour['blue']));
		$rgb = Colour::html_to_rgb(Colour::remap($colour,$colour_map,$tolerance));
		$r = $rgb['r'] * 255;
		$g = $rgb['g'] * 255;
		$b = $rgb['b'] * 255;
		imagecolorset($image,$i, $r, $g, $b);
	}

	# Output result to file
	switch($file_type) {
		case 'gif':
			if (!imageGIF($image, $output)) report_error(__FILE__,__LINE__,"Unable to output GIF after recolourizing: $output");
			break;
		case 'png':
			if (!imagePNG($image, $output)) report_error(__FILE__,__LINE__,"Unable to output PNG after recolourizing: $output");
			break;
		case 'jpg': case 'jpeg':
			if (!imageJPEG($image, $output)) report_error(__FILE__,__LINE__,"Unable to output JPEG after recolourizing: $output");
			break;
		default:
			report_error(__FILE__,__LINE__,"Unable to open unknown image type for recolourizing: $input");
			break;
	}
	imageDestroy($image);
}


 ##################
# Resizes an image 
function image_resize($input='', $output='', $w='', $h='') {
	if (!$input || !$output || !$w || !$h) return;

	$scaler  = 'pnmscale';

	# Check we have the right decoder
	switch(get_file_type($input)) {
		case 'gif':
			$decoder = 'giftopnm';
			break;
		case 'png':
			$decoder = 'pngtopnm';
			break;
		case 'jpg': case 'jpeg':
			$decoder = 'djpeg';
			break;
		default:
			report_error(__FILE__,__LINE__,"Unable to open unknown image type for resizing: $input");
			return;
	}

	# Check we have the right encoder
	switch(get_file_type($output)) {
		case 'gif':
			$encoder = 'pnmtogif';
			break;
		case 'png':
			$encoder = 'pnmtopng';
			break;
		case 'jpg': case 'jpeg':
			$encoder = 'cjpeg';
			break;
		default:
			report_error(__FILE__,__LINE__,"Unable to save unknown image type for resizing: $output");
			return;
	}

	$needed_programs[] = $encoder;
	$needed_programs[] = $decoder;

	foreach($needed_programs as $p) {
		$code = 0;
		system("$p /? 2> /dev/null",$code);
		if($code != 1) {
			report_error(__FILE__,__LINE__,"Unable to resize image, required external program could not be found: $p");
			$die = true;
		}
	}
	if($die) return;

	$options = '';

	if($w) $options .= " -w $w";
	if($h) $options .= " -h $h";

	$err = `(cat $input | $decoder | $scaler $options | $encoder > $output) 2>&1; echo $?`;
	if($err > 0) {
		report_error(__FILE__,__LINE__,"There seem to have been problems resizing the image<br><br>$input<br><Br>to<br><br>$output:<br><br><pre>".htmlspecialchars($err)."</pre>");
		unlink($output);
		return;
	}
	return 1;
}

 ########################################################
# returns the image resource for the passed image file
function image_from_file($input='') {
	if (!$input) return;
	switch(strtolower(get_file_type($input))) {
		case 'gif':
			if ($image = ImageCreateFromGif($input)) return $image;
			else report_error(__FILE__,__LINE__, "Unable to open GIF : $input");
			break;
		case 'png':
			if ($image = ImageCreateFromPNG($input)) return $image;
			else report_error(__FILE__,__LINE__, "Unable to open PNG : $input");
			break;
		case 'jpg': case 'jpeg':
			if ($image = ImageCreateFromJPEG($input)) return $image;
			else report_error(__FILE__,__LINE__, "Unable to open JPEG : $input");
			break;
		default:
			report_error(__FILE__,__LINE__,"Unable to open unknown image type : $input");
			break;
	}
	return false;

}#end image_from_file()

 ########################################################
# Write the image into the output file
function image_to_file($image='', $output='', $jpeg_quality=80) {
	if (!$image || !$output) return;
	# Output result to file
	switch(strtolower(get_file_type($output))) {
		case 'gif':
			if (imageGIF($image, $output)) return true;
			else report_error(__FILE__,__LINE__,"Unable to output GIF : $output");
			break;
		case 'png':
			if (imagePNG($image, $output)) return true;
			else report_error(__FILE__,__LINE__,"Unable to output PNG : $output");
			break;
		case 'jpg': case 'jpeg':
			if (imageJPEG($image, $output, $jpeg_quality)) return true;
			else report_error(__FILE__,__LINE__,"Unable to output JPEG : $output");
			break;
		default:
			report_error(__FILE__,__LINE__,"Unable to write unknown image type : $input");
			break;
	}
	return false;
}


 ########################################################
# Resizes an image using bicubic interpolation inside PHP
# this doesn't appear to work without GD 2.0 or later
function new_image_resize($input='', $output='', $new_w='', $new_h='') {
	if (!$input || !$output || !$new_w || !$new_h) return;
	
	list($old_w, $old_h) = getimagesize($input);

	if($new_w <= 0) $new_w = $old_w;
	if($new_h <= 0) $new_h = $old_h;

	$old_image = image_from_file($input);
	$new_image = ImageCreateTrueColor($new_w, $new_h);

	if(!imagecopyresampled($new_image,$old_image, 0, 0, 0, 0, $new_w, $new_h, $old_w, $old_h)) return false;
	
	image_to_file($new_image, $output);
	
	return true;

}


?>
