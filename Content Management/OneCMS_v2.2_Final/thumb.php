<?php
$la = "a";
$z = "b";
@include ("config.php");

   header("Content-type: image/jpeg");
   $im = imagecreatefromjpeg($_GET['p']);
   $orange = imagecolorallocate($im, 220, 210, 60);
   $px = (imagesx($im) - 7.5 * strlen($string)) / 2;

   $old_x=imageSX($im);
   $old_y=imageSY($im);

   $thumb=ImageCreateTrueColor($width,$height);
   imagecopyresized($thumb,$im,0,0,0,0,$width,$height,$old_x,$old_y);

   imagejpeg($thumb,"",100);
   imagedestroy($im);
   imagedestroy($thumb);
?>