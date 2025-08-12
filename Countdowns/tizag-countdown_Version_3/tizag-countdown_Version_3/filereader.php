<?php
$fh = fopen('pics/data.dat', 'r') or die("Cannot Open File!");
//Retrieve Information From data.dat, ORDER IS IMPORTANT!
$filename = 	trim(fgets($fh, 1024));
$text = 		stripslashes(fgets($fh, 1024));
$date = 		stripslashes(fgets($fh, 1024));

$mode = 		trim(fgets($fh, 1024));
$dropshadow = 	trim(fgets($fh, 1024));
$border = 		trim(fgets($fh, 1024));
$picture = 		trim(fgets($fh, 1024));

$font_size = 	trim(fgets($fh, 1024));
$xpos = 		trim(fgets($fh, 1024));
$ypos = 		trim(fgets($fh, 1024));
$xposoff = 		trim(fgets($fh, 1024));
$yposoff = 		trim(fgets($fh, 1024));
//Retreive the Colors
$bgcolor = 		trim(fgets($fh, 1024));
$txtcolor = 	trim(fgets($fh, 1024));
$shadowcolor = 	trim(fgets($fh, 1024));
$bordercolor = 	trim(fgets($fh, 1024));
fclose($fh);

?>