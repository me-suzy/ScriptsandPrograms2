<?php
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  
  original: 
  taCs.php - 'the acronym CAPTCHA sucks' - by pete higgins
	a random scalable CAPTCHA image generator, version 0.1.1
	changelog: 0.1.1 added more useful comments
	
  modified by TinyWegGallery - uses random sizes of images now
  
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3c
  $Date: 2005/11/15 09:02 $
**********************************************/

/*


(paths are hard-coded for testing purposed)

*/

define('C_WIDTH',130);    // output width
define('C_HEIGHT',50);     // output height
define('C_CHARS',4);        // number of chars to use
define('C_SPACING',25);     // letter spacing
define('C_BORDER',true);    // border the image?
define('C_GRID',true);        // use a grid overlay?
if (C_GRID) {
  define('GRID_SPACE_ADJ',rand(3,12)); // spacing increment
  define('GRID_SPACEX',6);           // x spacing for grid
  define('GRID_SPACEY',6);         // y spacing for grid
  }
define('MIN_TRANS',65);            // minimun alpha for chars
define('MAX_TRANS',85);         // max alpha for chars
define('SAVE_FILE',false);         // debugging, save output files?

// read from ./images/bgs/, first layer underneat
$backgrounds = array("bg-1_3.png","bg-1_2.png");

define('CHEAT',false); // for debugging, shows plaintext letters too
// end config type stuff

// use sessions to hide the 'key' from the user
session_start();

// GD 2.0 making some images WIDTH x HEIGHT
//   and set some color allocations
//
$im = imagecreatetruecolor(C_WIDTH,C_HEIGHT);
$bo = imagecolorallocate($im,100,100,100);
$bg = imagecolorallocate($im,255,255,255);
$li = imagecolorallocate($im,50,50,50);

// if bordering, do it, otherwise just fill the bg
if (C_BORDER) {
imagefilledrectangle($im,0,0,C_WIDTH,C_HEIGHT,$bo);
imagefilledrectangle($im,1,1,C_WIDTH-2,C_HEIGHT-2,$bg);
} else {
imagefilledrectangle($im,0,0,C_WIDTH,C_HEIGHT,$bg);
}

// background images
//
foreach ($backgrounds as $bgi) {
    // open each, copy to new image resized
    $tmpImg = imagecreatefrompng('../buttons/tacs/bgs/'.$bgi);
    imagecopyresized($im,$tmpImg,0,0,0,0,C_WIDTH,C_HEIGHT,imagesx($tmpImg),imagesy($tmpImg));     
    // don't forget to free the memory.
    imagedestroy($tmpImg);
}

// the captcha letters
//
$nx = 15; $str='';
// reads the dir ./images/chars/ and finds image files, or dies.
if (!$chars = getcatchars()) { die; }  

// each char
for ($i=0; $i<C_CHARS; $i++) {
    // pick random from list of files
    $l = rand(0,sizeof($chars)-1);  
    // load the char image
    $tmpImg = imagecreatefrompng('../buttons/tacs/chars/'.$chars[$l]);
    // add the char part of the filename (first letter)
    // a1.png && a2.png == variations of a and b3.png = b
     
    $percent = rand(6,8) / 10;
    // Get new dimensions
    $width = imagesx($tmpImg);
    $height = imagesy($tmpImg);
		$new_width = $width  * $percent;
		$new_height = $height * $percent;
		
		// Resample
		$image_p = imagecreatetruecolor($new_width, $new_height);
		imagefilledrectangle($image_p,0,0,$new_width,$new_height,$bg);
		imagecopyresampled($image_p, $tmpImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    $str .= $chars[$l]{0};
    // randomly place this char vertically
    $ny = rand(1,17);
    // then copy to im, goto next x position, and free the memory.
    
    imagecopymerge($im,$image_p, $nx,$ny, 0,0, $new_width, $new_height , rand(MIN_TRANS,MAX_TRANS));
    $nx += C_SPACING;
    imagedestroy($tmpImg);
}

// our 'key' is the string of chars we just made.
$_SESSION['twg_key'] = $str;

if (C_GRID) {  /* grid overlay */

// not yet
$x=GRID_SPACEX;
$y=GRID_SPACEY;

// make a new 'grid' image
$imgr = imagecreatetruecolor(C_WIDTH,C_HEIGHT);
$bo = imagecolorallocate($imgr,0,0,0);
$bg = imagecolorallocate($imgr,255,255,255);
imagefilledrectangle($imgr,0,0,C_WIDTH,C_HEIGHT,$bg);

// place a line until the edge of file
while ($x <= C_WIDTH) {
        imageline($imgr, $x, 0, $x, C_HEIGHT, $bo);
    $x+=GRID_SPACEX+GRID_SPACE_ADJ;
     }
// again
while ($y <= C_HEIGHT) {
    imageline($imgr, 0, $y, C_WIDTH,$y, $bo);
    $y+=GRID_SPACEY+GRID_SPACE_ADJ;
}    

// copy 'grid' image on top of the captcha image
imagecopymerge($im,$imgr, 0,0, 0,0,imagesx($imgr),imagesy($imgr),15);
// free the memory (it's important, really)
imagedestroy($imgr);
}

// did you want to cheat?
if (CHEAT) {
    imagestring($im, 5, 5, 5, strtoupper($str), $bo);
}

/* show the resulting image */
header("Content-type: image/png ");
imagepng($im);

/* free the last little image in memory, the captcha one */
imagedestroy($im);
    

function getcatchars() {

$path = '../buttons/tacs/chars';

if (is_dir($path)) {
if($handle = opendir($path)){
  while(false !== ($file = readdir($handle))){
    // only .png files, and hidden files. needs be case-insensative tho
    if(!preg_match("/^\./", $file) && preg_match("/.*\.png/",$file)){
      if(is_dir($path."/".$file)){
      $dirs[] = $file;
      }else{ $files[] = $file;
      }
    }
  }
closedir($handle);
}

}

if (isset($files) && is_array($files)) {
return $files;
} else {
return 0;
}

}





?>

