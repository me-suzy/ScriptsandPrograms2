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
//Meezerk's CowCounter - A simple image based counter.
//Copyright (C) 2003  Daniel Foster  dan_software@meezerk.com
//---------------------------------------------------------------



function filereader($CounterFile) {
  if (isset($CounterFile)) {
    if (!is_file($CounterFile)) {
      $CounterFileStream = fopen($CounterFile, 'wb');
      fwrite($CounterFileStream, "0");
      fclose($CounterFileStream);
    };
    $Counter = intval(file_get_contents($CounterFile));
    $Counter++;
    $CounterFileStream = fopen($CounterFile, "r+b");
    fwrite($CounterFileStream, strval($Counter));
    fclose($CounterFileStream);
    return $Counter;
  };
};


//Get Number out of file.
if(!isset($_GET["file"])) {
  echo "Error, you forgot to specify the file.";
} else {

  include("variables.php");

  if (!in_array($_GET["file"], $Enabled_Counters)) {
    echo "Error, that counter is not a validated counter.  Please use variables.php to validate your counter.";
  } else {
    $CounterFilename = "CounterFile_" . $_GET["file"];
    $NumHits = filereader($CounterFilename);



    //Get size of string (number of digits in number)
    $Number_Length = strlen($NumHits);



    //Check and change number for minimum digits requirement (if set)
    if (isset($_GET["minlength"])) {

      //echo "moo";
      $minlength = $_GET["minlength"];

      if ($minlength > $Number_Length) {

        $lengthdiff = $minlength - $Number_Length;
        $zerostring = "";

        for ($zerocount=0; $zerocount < $lengthdiff; $zerocount++) {
          $zerostring .= "0";
        };

        //set new number
        $NumHits = $zerostring . $NumHits;

        //Re-get size of string (number of digits in number)
        $Number_Length = strlen($NumHits);
      };
    };



    //send browser headers
    header("Content-Type: image/jpeg");



    //calculate size (width) of image.
    $Image_Width = (($Number_Length * 14)+10);



    //create a blank image to work with
    $imagestream = @imagecreatetruecolor($Image_Width,30);



    //Add Sides to image
    $image_LeftSide = @imagecreatefromjpeg("LeftSide.jpg");
    $image_RightSide = @imagecreatefromjpeg("RightSide.jpg");
    @imagecopy($imagestream, $image_LeftSide, 0, 0, 0, 0, 5, 30);
    @imagecopy($imagestream, $image_RightSide, ($Image_Width - 5), 0, 0, 0, 5, 30);
    @imagedestroy($image_RightSide);
    @imagedestroy($image_LeftSide);


    $PositionNumber = 0;

    for($PositionNumber; $PositionNumber < $Number_Length; $PositionNumber++) {
      //Get current digit
      $Digit = substr($NumHits,$PositionNumber,1);

      //Build filename
      $DigitFilename = $Digit . ".jpg";

      //read source image
      $image_Number = @imagecreatefromjpeg($DigitFilename);

      //Build location (horizontally) of digit image
      $Digit_Location = (($PositionNumber*14)+5);


      //copy source images to destination image
      @imagecopy($imagestream, $image_Number, $Digit_Location, 0, 0, 0, 14, 30);

      //destroy source image
      @imagedestroy($image_Number);
    };

    //display image
    @imagejpeg($imagestream);


    //destroy image in memeory as to NOT to crash the server
    @imagedestroy($imagestream);


    //if (!in_array($_GET["file"], $Enabled_Counters))
  };

  //end if(!isset($_GET(file))) {
};
?>