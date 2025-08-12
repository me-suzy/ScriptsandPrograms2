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

//session check

session_start();
include("config.php");

if (!(($_SESSION['ip'] == $_SERVER['REMOTE_ADDR']) && ($_SESSION['pass'] == $adminpass) && ($_SESSION['access'] == "granted"))) {
  //session info bad
  header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/logout.php?session=bad");

} else {
  //session info good

  $datastream = @mysql_connect($sqlip, $sqluser, $sqlpass);
  @mysql_select_db($dbname);

  $sqlstatement = "SELECT counterid,startingcount FROM " . $tableprefix . "counterdescription ORDER BY counterid";
  $counterlistresult = @mysql_query($sqlstatement);

  if (!$counterlistresult) {
    //error getting data
    ?>
      There seems to have been an error, the error code returned from you MySQL database was:<BR>
      <?
        echo mysql_error() . "<BR>";
      ?>
      If you do not understand this error, please contact your local system administrator.<BR>
    <?
  } else {
    // results retrieved successfully.

    $rowcount = mysql_num_rows($counterlistresult);
    for ( $rowcounter=0; $rowcounter < $rowcount; $rowcounter++ )
      {
        //get counterid
        $counterid = mysql_result($counterlistresult,$rowcounter,"counterid");

        //get current tick count
        $sqlstatement = "SELECT COUNT(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "'";
        $countresult = @mysql_query($sqlstatement);
        $hitcount = mysql_result($countresult,0,"COUNT(*)");
        $startcount = mysql_result($counterlistresult,$rowcounter,"startingcount");
        $NumHits = $hitcount + $startcount;

        //update last viewed tick count
        $sqlstatement = "UPDATE " . $tableprefix . "counterdescription SET datetimeviewreset=NOW(),lastviewed='" . $NumHits . "' WHERE counterid='" . $counterid . "'";
        $updateresult = @mysql_query($sqlstatement);

       //end for loop
      };

   //end if mysql worked
  };

@mysql_close($datastream);
header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/main.php");

};
?>