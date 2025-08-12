<?php
// This script will show image with logo on it
// Supported are PNGs,GIFs,JPEGs
// ------------------------------------------------------
// call it: http://www.domain.com/showimage.php?img=<name>
// where <name> is name of image...
// example:
// http://www.domain.com/showimage.php?img=001.jpg
// ------------------------------------------------------
// You have to set parameters here:
// ------------------------------------------------------
$image_quality="70";
// quality of JPEG conpression [0-100]
// ------------------------------------------------------
$image_path="./images/";
// path to images
// examples:
// $image_path="./images/";
// $image_path="../../images/";
// ------------------------------------------------------
$logo_path="./logo.png";
// path and name of the LOGO image (PNG,GIF,JPEG)
// examples:
// $logo_path="./logos/img_logo.png";
// $logo_path="../../logos/img_logo.png"
// ------------------------------------------------------
$logo_pos_x="center";
// left, right, center
$logo_pos_y="bottom";
// top, middle, bottom
// ------------------------------------------------------
$error_not_found="File doesn't exists";
// where image is not found, show this error text
$error_not_supported="This image type isn't supported";
// where image is not supported, show this error text
$error_bg_color=array(255,255,255);
// image background color in RGB - (RED,GREEN,BLUE)
$error_text_color=array(255,0,0);
// text color in RGB - (RED,GREEN,BLUE)
// ------------------------------------------------------
//  YOU DON'T HAVE TO EDIT CODE BELOW THIS LINE
// ------------------------------------------------------
// SCRIPT written by Ladislav Soukup, [root@soundboss.cz]
// ------------------------------------------------------
function NewImage($width,$height,$text=""){
	global $error_bg_color,$error_text_color;
	if (function_exists("imagecreatetruecolor")){
		if (!@$img=imagecreatetruecolor($width,$height)){
			$img=imagecreate($width,$height);
		}
	} else {
		$img=imagecreate($width,$height);
	}
	$imgbgcolor=ImageColorAllocate($img,$error_bg_color[0],$error_bg_color[1],$error_bg_color[2]);
	$imgtextcolor=ImageColorAllocate($img,$error_text_color[0],$error_text_color[1],$error_text_color[2]);
	imagefilledrectangle($img,0,0,$width,$height,$imgbgcolor);
	imagestring($img,5,10,10,$text,$imgtextcolor);
	return($img);
}
Header("Content-type: image/jpeg");
$exp=GMDate("D, d M Y H:i:s",time()+999);
Header("Expires: $exp GMT");
$rep_from=array("./","../");
$rep_to=array("","",);
$_GET["img"]=str_replace($rep_from,$rep_to,$_GET["img"]);
$file=$image_path . $_GET["img"];
if (file_exists($file)){
	$info=getimagesize($file);
	$width=$info[0];
	$height=$info[1];
	if ($info[2]==1){
		$img=@imagecreatefromgif($file);
	} else if ($info[2]==2){
		$img=@imagecreatefromjpeg($file);
	} else if ($info[2]==3){
		$img=@imagecreatefrompng($file);
	} else {
		$width=640;
		$height=480;
		$img=NewImage($width,$height,$error_not_supported);
	}
} else {
	$width=640;
	$height=480;
	$img=NewImage($width,$height,$error_not_found);
}
if (file_exists($logo_path)){
	$info=getimagesize($logo_path);
	$logo_width=$info[0];
	$logo_height=$info[1];
	if ($info[2]==1){
		$img_logo=imagecreatefromgif($logo_path);
	} else if ($info[2]==2){
		$img_logo=imagecreatefromjpeg($logo_path);
	} else if ($info[2]==3){
		$img_logo=imagecreatefrompng($logo_path);
	} else {
		$logo_width=120;
		$logo_height=20;
		$img=NewImage($logo_width,$logo_height,$error_not_supported);
	}
	// positioning - X
	if ($logo_pos_x=="left"){
		$dst_x=10;
	} else if ($logo_pos_x=="center"){
		$dst_x=round(($width-$logo_width)/2);
	} else if ($logo_pos_x=="right"){
		$dst_x=$width-10-$logo_width;
	} else {
		$dst_x=round(($width-$logo_width)/2);
	}
	// positioning - Y
	if ($logo_pos_y=="top"){
		$dst_y=5;
	} else if ($logo_pos_y=="middle"){
		$dst_y=round(($height-($logo_height/2))/2);
	} else if ($logo_pos_y=="bottom"){
		$dst_y=$height-5-$logo_height;
	} else {
		$dst_y=round(($height-($logo_height/2))/2);
	}
	imagecopy($img,$img_logo,$dst_x,$dst_y,0,0,$logo_width,$logo_height);
}
ImageJpeg($img,"",$image_quality);
?>