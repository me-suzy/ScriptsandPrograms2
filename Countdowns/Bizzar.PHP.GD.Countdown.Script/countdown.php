<?
// Bizzar PHP GD Countdown Script
// Written by: Brad Derstine (Brad@BizzarScripts.net)

$month = 7; // Month of the countdown
$day = 1; // Day of the countdown
$year = 2004; // Year of the countdown

// mktime is the marked time, and time() is the current time.
$target = mktime(0,0,0,$month,$day,$year);
$diff = $target - time();

$days = ($diff - ($diff % 86400)) / 86400;
$diff = $diff - ($days * 86400);
$hours = ($diff - ($diff % 3600)) / 3600;
$diff = $diff - ($hours * 3600);
$minutes = ($diff - ($diff % 60)) / 60;
$diff = $diff - ($minutes * 60);
$seconds = ($diff - ($diff % 1)) / 1;

header ("Content-type: image/png");
$imgname = "hl2countdown.png";
$im = @imagecreatefrompng ($imgname);

//Here are some common color codes in case you want to change the colors.
//$white = imagecolorallocate ($im, 255, 255, 255);
//$blue = imagecolorallocate ($im, 0, 0, 255);
//$black = imagecolorallocate ($im,0,0,0);
//$gray = imagecolorallocate ($im,153,153,153);
//$red = imagecolorallocate ($im,255,0,0);
//$orange = imagecolorallocate ($im, 255, 127, 36);

$background_color = imagecolorallocate ($im, 0, 0, 0);
$orange = imagecolorallocate ($im, 255, 127, 36);
$yellow = imagecolorallocate ($im, 247, 246, 201);
imagestring ($im, 2, 60, 5,  " Countdown to Half Life 2 Release:     Available from www.hl2empire.com", $yellow);
imagestring ($im, 3, 65, 18,  "[ $days day(s) ] [ $hours hour(s) ] [ $minutes minute(s) ] [ $seconds second(s) ]", $orange);
imagepng ($im);
imagedestroy ($im);
?>
