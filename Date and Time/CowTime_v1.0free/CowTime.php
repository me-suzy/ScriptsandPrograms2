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
//Meezerk's CowTime - A simple image based clock.
//Copyright (C) 2003  Daniel Foster  dan_software@meezerk.com
//---------------------------------------------------------------


$TodayArray = getdate();

//Var for Am/PM indicator on 12 hour clock
$ampm = "am";

if (!isset($_GET["24hour"])) {
  // if 24 hour var is not set
  // 24 to 12 hour clock conversion
  $hour = intval($TodayArray["hours"]);
  if ($hour == 0) {
    $hour = 12;
  } elseif ( $hour > 12 ) {
    $hour = $hour - 12;
    $ampm = "pm";
  } elseif ( $hour == 12 ) {
    $ampm = "pm";
  }; 
  $TodayArray["hours"] = strval($hour);
} else {
  // if 24 hour var is set
  $ampm = "24hour";
};

// make sure minutes have two digits
$minutes = intval($TodayArray["minutes"]);
if ( $minutes < 10 ) {
  $TodayArray["minutes"] = "0" . $TodayArray["minutes"];
};

// make sure seconds have two digits
$seconds = intval($TodayArray["seconds"]);
if ( $seconds < 10 ) {
  $TodayArray["seconds"] = "0" . $TodayArray["seconds"];
};

//include seconds or not
if (isset($_GET["seconds"])) {
  $Today = $TodayArray["hours"] . ":" . $TodayArray["minutes"] . ":" . $TodayArray["seconds"];
} else {
  $Today = $TodayArray["hours"] . ":" . $TodayArray["minutes"];
};

//echo $Today;

//Get size of string
$TimeLength = strlen($Today);

//Calculate Size of (width) of image. (DigitWidth * NumDigits + Sides)
$ImageWidth = (($TimeLength * 14) +10);

if (isset($_GET["seconds"])) {
  //seconds are included (ImageWidth - two timebars) (timebars were added in as digits already)
  $ImageWidth = $ImageWidth -18;
} else {
  //seconds are not included (ImageWidth - one timebar) (timebars were added in as digits already)
  $ImageWidth = $ImageWidth -9;
};

//Add AM/PM size if not in 24 hour mode
if ($ampm != "24hour") {
  $ImageWidth = $ImageWidth +13;
};

//create a blank image to work with
$ImageStream = @imagecreatetruecolor($ImageWidth,30);

//get left side image from files and copy it
$image_LeftSide = @imagecreatefromjpeg("LeftSide.jpg");
@imagecopy($ImageStream, $image_LeftSide, 0, 0, 0, 0, 5, 30);
@imagedestroy($image_LeftSide);


$image_TimeBars = @imagecreatefromjpeg("TimeBars.jpg");

$LeftPosition = 5;
$PositionNumber = 0;

for($PositionNumber; $PositionNumber < $TimeLength; $PositionNumber++) {
  //Get Current Digit
  $Digit = substr($Today, $PositionNumber, 1);
  //check digit and build filename
  if ($Digit == ":") {
    @imagecopy($ImageStream, $image_TimeBars, $LeftPosition, 0, 0, 0, 14, 30);
    $LeftPosition = $LeftPosition +5;
  } else {
    $Digit_Filename = $Digit . ".jpg";
    $image_Number = @imagecreatefromjpeg($Digit_Filename);
    @imagecopy($ImageStream, $image_Number, $LeftPosition, 0, 0, 0, 14, 30);
    @imagedestroy($image_Number);
    $LeftPosition = $LeftPosition +14;
  };
};

//Destroy timebars image.
@imagedestroy($image_TimeBars);

if ($ampm != "24hour") {
  //if not in 24 hour mode
  //get AM/PM image from files and copy it
  if ($ampm == "am") {
    $image_ampm = @imagecreatefromjpeg("AM.jpg");
  } else {
    $image_ampm = @imagecreatefromjpeg("PM.jpg");
  };
  @imagecopy($ImageStream, $image_ampm, $LeftPosition, 0, 0, 0, 13, 30);
  @imagedestroy($image_ampm);
  $LeftPosition = $LeftPosition + 13;
};

//get right side image from files and copy it
$image_RightSide = @imagecreatefromjpeg("RightSide.jpg");
@imagecopy($ImageStream, $image_RightSide, $LeftPosition, 0, 0, 0, 5, 30);
@imagedestroy($image_RightSide);


//send browser headers, display image, and destroy image.
header("Content-Type: image/jpeg");
@imagejpeg($ImageStream);
@imagedestroy($ImageStream);

?>