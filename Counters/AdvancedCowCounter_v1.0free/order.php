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

  //connect to database;
  $datastream = @mysql_connect($sqlip, $sqluser, $sqlpass);
  @mysql_select_db($dbname);




  //get counter id
  $cid = $_POST["id"];

  //get direction
  if (isset($_POST["up"])) {
    $dir = "up";
  } elseif (isset($_POST["down"])) {
    $dir = "down";
  } else {
    //error
    header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/main.php");
    exit();
  };




  //get highest number or lowest number depending on direction;
  if ($dir == "up") {
    $sqlstatement = "SELECT counterid FROM " . $tableprefix . "counterdescription order by userorder limit 1";
  } else {
    $sqlstatement = "SELECT counterid FROM " . $tableprefix . "counterdescription order by userorder DESC limit 1";
  };

  $edgecidresult = @mysql_query($sqlstatement);
  $edgecid = mysql_result($edgecidresult,0,"counterid");

  //check number for edge of list
  if ($cid == $edgecid) {
    //counter can't move that direction
    if ($_POST["page"] == "edit") {
      header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/counters.php");
    } else {
      header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/main.php");
    };
    exit();
  };




  //get order number of one moving
  $getordernum1sql = "SELECT userorder FROM " . $tableprefix . "counterdescription WHERE counterid='" . $cid . "'";
  $getordernum1result = @mysql_query($getordernum1sql);

  //decipher first numbers
    //$cid already has first id number
  $ordernum = mysql_result($getordernum1result,0,"userorder");




  //get counterid an order number of one being bumped
  if ($dir == "up") {
    $getordernum2sql = "SELECT counterid,userorder FROM " . $tableprefix . "counterdescription WHERE userorder<'" . $ordernum . "' ORDER BY userorder DESC LIMIT 1";
  } else {
    $getordernum2sql = "SELECT counterid,userorder FROM " . $tableprefix . "counterdescription WHERE userorder>'" . $ordernum . "' ORDER BY userorder LIMIT 1";
  };
  $getordernum2result = @mysql_query($getordernum2sql);

  //decipher second numbers
  $cid2 = mysql_result($getordernum2result,0,"counterid");
  $ordernum2 = mysql_result($getordernum2result,0,"userorder");




  //build sql statementes for update
  $input1sql = "UPDATE " . $tableprefix . "counterdescription SET userorder='" . $ordernum2 . "' WHERE counterid='" . $cid . "'";
  $input2sql = "UPDATE " . $tableprefix . "counterdescription SET userorder='" . $ordernum . "' WHERE counterid='" . $cid2 . "'";

  //run database updates
  $result1 = @mysql_query($input1sql);
  $result2 = @mysql_query($input2sql);




  if ( !( $result1 && $result2 ) ) {
    //error getting data
    ?>
      There seems to have been an error, the error code returned from you MySQL database was:<BR>
      <?
        echo mysql_error() . "<BR>";
      ?>
      If you do not understand this error, please contact your local system administrator.<BR>
    <?
  } else {
    if ($_POST["page"] == "edit") {
      header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/counters.php");
    } else {
      header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/main.php");
    };
  };

};
?>