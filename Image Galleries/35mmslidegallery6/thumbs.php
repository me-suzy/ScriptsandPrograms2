<?php
require('config.php');
$image = $_GET['image'] ; 
$newwidth = $_GET['newwidth'];
$newheight = $_GET['newheight'];
$height = $_GET['height'];
$width = $_GET['width'];
$correctheight = round($width/2*3);
$correctwidth = round($height/3*2);
$correctheightb = round($width/3*2);
$correctwidthb = round($height/2*3);
$ratio = ($height/$width);
$src = imagecreatefromjpeg("$image");
$im = imagecreatetruecolor($newwidth,$newheight);  

if ($croptofit)
{
if ($height > $width)
{ 
if ($ratio > 1.5)
{
$newy = round(($height-$correctheight)/2);
imagecopyresampled($im,$src,0,0,0,$newy,$newwidth,$newheight,$width,$correctheight); 
}
else
{
$newx = round(($width-$correctwidth)/2);
imagecopyresampled($im,$src,0,0,$newx,0,$newwidth,$newheight,$correctwidth,$height); 
}
}
else
if ($ratio < 0.67)
{
$newx = round(($width-$correctwidthb)/2);
imagecopyresampled($im,$src,0,0,$newx,0,$newwidth,$newheight,$correctwidthb,$height); 
}
else
{
$newy = round(($height-$correctheightb)/2);
imagecopyresampled($im,$src,0,0,0,$newy,$newwidth,$newheight,$width,$correctheightb); 
}
}
else
{
imagecopyresampled($im,$src,0,0,0,0,$newwidth,$newheight,$width,$height); 
}

imagejpeg($im, '',85); 
imagedestroy($im); 





?>