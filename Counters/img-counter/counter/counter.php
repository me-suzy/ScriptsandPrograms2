<?php

/********************************************************
	Variables
*********************************************************/

$digit_dir = "./digits";
$file = "counter.txt";
$min_width = 5;
$lifetime = 30;
$domain = "www.offsite.be";

$use_mail = 1;
$trigger = 1000;
$your_email = "admin@offsite.be";


/********************************************************
	Code --> DO NOT EDIT BELOW THIS POINT
*********************************************************/

$fp = fopen($file, "r") or die("Failed to open counter-file");
$size = filesize($file);
$count = fread($fp, $size);
fclose($fp);

if(!Isset($_COOKIE['counter'])){
  $fp = fopen($file, "w");
	$count++;
	fwrite($fp, $count);
	fclose($fp);
  setcookie("counter","dummy",time()+60*60*24*$lifetime,$domain);
  
  if($use_mail AND ($count%$trigger==0)){
    $headers = "From: Counter <noreply@$domain>\n";
    $headers .= "X-Sender: <noreply@$domain>\n";
    $headers .= "X-Mailer: Offsite Counterscript\n";
    $headers .= "Return-Path: <noreply@$domain>\n";
    $subject = "Counter information from $domain";
    $message = "Congratulations!\n\nThe number of visitors on your site has reached $count.";
    mail($email,$subject,$message,$headers);
  }
}

$len = strlen(strval($count));
if($len > $min_width) $width = $len;
else $width = $min_width;

if(!file_exists("$digit_dir/0.png")){
  die("No images in digit-dir");
}

$d0 = ImageCreateFrompng("$digit_dir/0.png");
$dx = ImageSX($d0);
$dy = ImageSY($d0);

$img = ImageCreateTrueColor($width*$dx, $dy);
ImageDestroy($d0);

$xoff = $width*$dx;
while($xoff > 0) {
  $digit = $count % 10;
  $count = $count / 10;
  $temp = ImageCreateFrompng("$digit_dir/$digit.png");
  $xoff = $xoff - $dx;
  ImageCopyResized($img, $temp, $xoff, 0, 0, 0, $dx, $dy, $dx, $dy);
  ImageDestroy($temp);
	}

Header("Content-type: image/png");
Imagepng($img);
ImageDestroy($img);
?>
