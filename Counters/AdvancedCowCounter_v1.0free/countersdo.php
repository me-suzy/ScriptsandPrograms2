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


 if (isset($_POST["submit"]) && ((isset($_POST["id"])) || ($_POST['submit'] == "Create"))) {
  //edit counters is done elsewhere.

  $datastream = @mysql_connect($sqlip, $sqluser, $sqlpass);
  @mysql_select_db($dbname);

  if ($_POST['submit'] == "Delete") {
    //Delete counter
    //get counter id
    $counterid = $_POST["id"];

    $sqlstatement = "DELETE FROM " . $tableprefix . "counting WHERE counterid = '" . $counterid . "'";
    $result = @mysql_query($sqlstatement);

    $sqlstatement = "DELETE FROM " . $tableprefix . "counterdescription WHERE counterid = '" . $counterid . "'";
    $datastream = @mysql_connect($sqlip, $sqluser, $sqlpass);
    @mysql_select_db($dbname);
    $result = @mysql_query($sqlstatement);

  } elseif ($_POST['submit'] == "Reset") {
    //reset counter
    //get counter id
    $counterid = $_POST["id"];

    $sqlstatement = "DELETE FROM " . $tableprefix . "counting WHERE counterid = '" . $counterid . "'";
    $result = @mysql_query($sqlstatement);

  } elseif ($_POST['submit'] == "Create") {
    //Create counter
    //get variables
    $name = $_POST["newname"];
    $type = $_POST["newtype"];
    $dest = $_POST["newdestination"];
    $mindigits = $_POST["newmindigits"];
    $start = $_POST["newstart"];

    //get next order number
    $sqlstatement = "SELECT counterid FROM " . $tableprefix . "counterdescription order by counterid desc limit 1";
    $orderresult = @mysql_query($sqlstatement);
    if (mysql_num_rows($orderresult) != 0) {
      $ordernum = mysql_result($orderresult,0,"counterid") + 1;
    } else {
      $ordernum = 1;
    };

    //create actual counter
    $sqlstatement = "INSERT INTO " . $tableprefix . "counterdescription values ('0000','" . $ordernum . "','" .
                       $name . "','0','" . $type . "','" . $dest . "','" . $start . "',now(), now(), '" . $mindigits . "')";

    $result = @mysql_query($sqlstatement);

    //end create if
  };


  $sqlerror = mysql_error();

  if ($result && !$sqlerror) {
    header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/counters.php");
    //echo $result;
  } else {
    include('header.php');
    ?>
      <P>It would appear that there was an unexpected MySQL Error.  The returned error is as follows:</P>
    <?
    echo $sqlerror;

    ?>
      <P>
       <FORM ACTION="counters.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">
        <INPUT NAME="submit" TYPE="submit" VALUE="Return to Add/Edit Counters">
       </FORM>
      </P>
    <?

    include('footer.php');
  };

 //end counterid check
 };

 //if on session check
};

?>