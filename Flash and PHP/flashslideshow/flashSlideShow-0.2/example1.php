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


//add first image to sequence
$movie->addImage('image1.jpg');

//add second image to sequence
$movie->addImage('image2.jpg');

//save movie,
//now check file name test.swf in current directory
$movie->save('test.swf');
?>