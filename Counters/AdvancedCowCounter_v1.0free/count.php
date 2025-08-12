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
//Meezerk's Advanced CowCounter - An Advanced Website Counter.
//Copyright (C) 2004  Daniel Foster  dan_software@meezerk.com
//---------------------------------------------------------------

if (!isset($_GET["counterid"])) {
  //no counterid set
  echo "Error, counterid not set";

} else {
  $counterid = $_GET["counterid"];

  include("config.php");

  //connect to database;
  $datastream = @mysql_connect($sqlip, $sqluser, $sqlpass);
  @mysql_select_db($dbname);

  //test for ip address ignore
  $sqlstatement = "SELECT COUNT(*) FROM " . $tableprefix . "ipignore WHERE '" . $_SERVER['REMOTE_ADDR'] . "' LIKE ipaddress";
  $ignoreresult = @mysql_query($sqlstatement);

  $ignoreit = mysql_result($ignoreresult,0,"COUNT(*)");

  //test for repeat IP in the last 30 minutes
  $sqlstatement = "SELECT TO_DAYS(NOW()) - TO_DAYS(time), EXTRACT(hour from NOW()) - EXTRACT(hour from time), EXTRACT(minute from NOW()) - EXTRACT(minute from time) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND ipaddress='" . $_SERVER['REMOTE_ADDR'] ."' ORDER BY countid DESC LIMIT 1";
  $timeresult = @mysql_query($sqlstatement);

  if (mysql_num_rows($timeresult) > 0) {
    $daydiff = mysql_result($timeresult,0,"TO_DAYS(NOW()) - TO_DAYS(time)");
    $hourdiff = mysql_result($timeresult,0,"EXTRACT(hour from NOW()) - EXTRACT(hour from time)");
    $minutediff = mysql_result($timeresult,0,"EXTRACT(minute from NOW()) - EXTRACT(minute from time)");

    $daydiffmin = $daydiff * 1440;
    $hourdiffmin = $hourdiff * 60;
    $minutediffmin = $minutediff * 1;
      //the line above is just to make minutediff an integer.


    $totalmindiff = $daydiffmin + $hourdiffmin + $minutediffmin;

    if ($totalmindiff < 30) {
      $ignoreit = 1;
    };

    //otherwise assume new visitor
  };

//echo $daydiffmin.",".$hourdiffmin.",".$minutediffmin.",".$totalmindiff;


  $sqlstatement = "SELECT type,destination,startingcount,minimumdigits FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";
  $counterresult = @mysql_query($sqlstatement);

  if (mysql_num_rows($counterresult) == 0) {
    echo "Sorry, that counter id does not exist.";
  } else {
    if (($ignoreit == 0) && (mysql_result($counterresult,0,"type") != "SC")) {
      //insert entry if not an seperator counter (sc)
      $sqlstatement = "INSERT INTO " . $tableprefix . "counting VALUES ('0000','" . $counterid . "',now(),'" . $_SERVER['REMOTE_ADDR'] . "','" . $_SERVER["REMOTE_HOST"] . "','" . $_SERVER["HTTP_USER_AGENT"] . "')";
      $insresult = @mysql_query($sqlstatement);
      //end ignore ip
    };

    //IF COUNTER IS A HIT COUNTER -------------------------------------------------------------    
    if (mysql_result($counterresult,0,"type") == "HC") {
      //get count
      $sqlstatement = "SELECT COUNT(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "'";
      $countresult = @mysql_query($sqlstatement);
      $hitcount = mysql_result($countresult,0,"COUNT(*)");
      $startcount = mysql_result($counterresult,0,"startingcount");
      $minlength = mysql_result($counterresult,0,"minimumdigits");

      $NumHits = $hitcount + $startcount;

      //display picture----------

      //Get size of string (number of digits in number)
      $Number_Length = strlen($NumHits);

      //Check and change number for minimum digits requirement (if set)
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


      //end type = HC
    };


    //IF COUNTER IS A PAGE ACCESS COUNTER -------------------------------------------------------------    
    if (mysql_result($counterresult,0,"type") == "PAC") {
      header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/transparent.gif");
      //end type = PAC
    };


    //IF COUNTER IS A LINK COUNTER -------------------------------------------------------------    
    if (mysql_result($counterresult,0,"type") == "LC") {
      header("Location: " . mysql_result($counterresult,0,"destination"));
      //end type = PAC
    };


    //IF COUNTER IS A SEPERATOR COUNTER (user made a mistake)-------------------------------------------------------------    
    if (mysql_result($counterresult,0,"type") == "SC") {
      echo "Sorry, that counter id does not exist.";
    };


    //end $counterresult is true
  };

  //end $counterid is set
};

?>