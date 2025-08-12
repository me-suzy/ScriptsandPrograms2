<?php

//Slide gallery variables
$place = "."; //directory of the slide mount images, no need to change
$col = 3; //no. of columns in a page
$maxrow = 2; //no. of rows in a page
$dir="."; //directory for this script, no need to change
$thumb = true ; //setting it to TRUE will generate real thumbnails on-the-fly, supports jpg file only and requires GD library. Setting it to FALSE will resize the original file to fit the thumbnail size, long download time. Turn it off if thumbnails don't show properly.
$croptofit = true ; //TRUE will crop the thumbnail to fit the aspect ratio of the slide mount if they aren't the same. False won't crop the thumbnail but it will be distorted if both aspect ratios are not the same.
$rollover = true ;  //thumbnail rollover effect for IE only

//Upload/Delete Module variables
$LOGIN = "admin";
$PASSWORD = "admin";
$abpath = "/usr/local/apache/vhosts"; //Absolute path to where images are uploaded. No trailing slash
$sizelim = "no"; //Size limit, yes or no
$size = "2500000"; //Size limit if there is one
$number_of_uploads = 5;  //Maximum number of uploads in one time

?>