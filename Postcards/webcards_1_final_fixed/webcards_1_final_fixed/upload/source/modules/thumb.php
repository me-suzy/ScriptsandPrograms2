<?php

/*=====================================
// MODULE THAT GENERATES THUMBNAILS  //
=====================================*/

function generate_thumb($source, $filename, $thumb_width, $thumb_height, $output_type)
{

//Get GD version from functions file
$gd_version = gd_version();

$thumb_width = intval($thumb_width);
$thumb_height = intval($thumb_height);


//Get image info and map it to an array we can identify
list($im['width'], $im['height'], $im['type'], $im['attr']) = getimagesize($source);

//Get the ration of width to height
$ratio = round($im['width'] / $im['height'], 3);

if($thumb_width != "0" && $thumb_height != "0") // Force dimensions
{
	$final_thumb_width = $thumb_width;
	$final_thumb_height = $thumb_height;
}
else if($thumb_width != "0" && $thumb_height == "0") // Only a width is set
{
	$final_thumb_width = $thumb_width;
	$final_thumb_height = round($thumb_width / $ratio);
}
else if($thumb_height != "0" && $thumb_width == "0") // Only a height is set
{
	$final_thumb_width = round($thumb_height * $ratio);
	$final_thumb_height = $thumb_height;
}
else if ($thumb_height == "0" && $thumb_width == "0") // Neither is set
{
	$final_thumb_width = $thumb_width;
	$final_thumb_height = $thumb_height;
}

if($im['width'] < $final_thumb_width && $im['height'] < $final_thumb_height)
{
	//The image is smaller than our thumbnail dimensions so we simply need to copy the image
	$final_thumb_width = $im['width'];
	$final_thumb_height = $im['height'];
}

/*//////////////////////////////////
// Create the Image               //
//////////////////////////////////*/

if($im['type'] == 1)
{
	if($gd_version == 2 || !@function_exists(imagecreatefromgif))
	{
		echo "Your version of PHP does not support working with gif images";
	}
	else
	{
		$image = @imagecreatefromgif($source);
	}
}
else if($im['type'] == 2)
{
	$image = @imagecreatefromjpeg($source);
}
else if($im['type'] == 3)
{
	$image = @imagecreatefrompng($source);
}

// Create the blank thumbnail
if($gd_version == 2) //These functions are better but require GD 2
{
	$thumb = @imagecreatetruecolor($final_thumb_width, $final_thumb_height);
	@imagecopyresampled($thumb, $image, 0, 0, 0, 0, $final_thumb_width, $final_thumb_height, $im['width'], $im['height']);
}
else
{
	$thumb = @imagecreate($final_thumb_width, $final_thumb_height);
	@imagecopyresized($thumb, $image, 0, 0, 0, 0, $final_thumb_width, $final_thumb_height, $im['width'], $im['height']);
}

// Save the thumb
if($output_type == "gif")
{
	if($gd_version == 2 || !@function_exists(imagegif))
	{
		echo "Your version of PHP does not support working with gif images";
	}
	else
	{
		@imagegif($thumb, $filename);
	}
}
else if($output_type == "jpg")
{
	@imagejpeg($thumb, $filename);
}
else if($output_type == "png")
{
	@imagepng($thumb, $filename);
}

@chmod($filename, 0777); //Chmod to global writeable
@imagedestroy($thumb); //Destroy the temporary thumbnail
@imagedestroy($image); //Destroy the temporary image


return true;
}
?>
