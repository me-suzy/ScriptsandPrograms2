<?php
//include class file
include('class.flashslideshow.php');

/*
create new object with the following variabel
flashSlideShow($width, $height, $interval, $bgcolor)
$width, movie width in pixel
$height, movie height in pixel
$interval, interval in seconds between each images
$bgcolor, movie backgground color in hexadecimal value
*/
$movie = new flashSlideShow(300, 225, 3, '#FFFFFF');


/*
differences from example1.php
instead of add image one by one, we add sequences of images in array
this array may come from readdir function,database query etc
*/
$myfile = array('image1.jpg','image2.jpg');
$movie->addImages($myfile);

/*
differences from example1.php and example2.php
instead of save movie, we can directly output the flash
*/
$movie->output();
?>