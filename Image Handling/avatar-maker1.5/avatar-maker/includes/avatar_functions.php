<?php
//////////////////////
//
// Net Avatar Maker
// version 1.5
// http://php-net.net/
// 1:01 AM 4/28/2005
//
//////////////////////
//
// decode an HTML hex-code into an array of R,G, and B values. accepts these formats: (case insensitive) #ffffff, ffffff, #fff, fff
//
function hex_to_rgb($hex){
// remove '#'
if(substr($hex,0,1) == '#'){
$hex = substr($hex,1);
}
// expand short form ('fff') color
if(strlen($hex) == 3){
$hex = substr($hex,0,1) . substr($hex,0,1) .
substr($hex,1,1) . substr($hex,1,1) .
substr($hex,2,1) . substr($hex,2,1);
}
if(strlen($hex) != 6){
fatal_error('Error: Invalid color "'.$hex.'"');
}
// convert
$rgb['red'] = hexdec(substr($hex,0,2));
$rgb['green'] = hexdec(substr($hex,2,2));
$rgb['blue'] = hexdec(substr($hex,4,2));
return $rgb;
}
//###

function arrow($im, $x1, $y1, $x2, $y2, $alength, $awidth, $color){
/// later on... :)
}

function add_border($im, $width, $pattern, $style){
/// later on... :)
}

?>