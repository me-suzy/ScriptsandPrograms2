<?

//---------------------------------------------------------------
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.
//
//Meezerk's Makebar - A Simple Percentage Bar.
//Copyright (C) 2004  Daniel Foster  dan_software@meezerk.com
//---------------------------------------------------------------

if (isset($_GET["dir"])) {
  $dir = $_GET["dir"];
} else {
  $dir = "h";
};

if (isset($_GET["length"])) {
  $length = $_GET["length"];
} else {
  $length = 200;
};

if (isset($_GET["percent"])) {
  $percent = $_GET["percent"];
} else {
  $percent = 0;
};

//calculate length of percent full
$percentlength = round(($percent / 100) * $length);

//send headers
Header("Content-Type: image/jpeg");

if ($dir == "v") {
  //create image
  $image = ImageCreate(6, $length);
} else {
  //dir == h
  //create image
  $image = ImageCreate($length, 6);
};

//Make colours
$grey = ImageColorAllocate($image, 200, 200, 200);
$blue = ImageColorAllocate($image, 0, 0, 255);

//Fill image with grey
ImageFill($image, 0, 0, $grey);

if ($dir == "v") {
  //create blue percent bar
  ImageFilledRectangle($image, 0, $length - $percentlength, 6, $length , $blue);
} else {
  //dir == h
  //create blue percent bar
  ImageFilledRectangle($image, 0, 0, $percentlength, 6, $blue);
};

//send picture to browser
ImageJPEG($image);

//clean up image as to not to crash the server
@imagedestroy($image);

?>