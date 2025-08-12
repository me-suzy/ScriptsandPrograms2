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

 if (isset($_POST["submit"]) && ((isset($_POST["ipid"])) || ($_POST['submit'] == "Create"))) {
  //edit counters is done elsewhere.

  if ($_POST['submit'] == "Delete") {
    //Delete counter
    $ipid = $_POST["ipid"];
    $sqlstatement = "DELETE FROM " . $tableprefix . "ipignore WHERE ipid = '" . $ipid . "'";
    $datastream = @mysql_connect($sqlip, $sqluser, $sqlpass);
    @mysql_select_db($dbname);
    $result = @mysql_query($sqlstatement);

  } elseif ($_POST['submit'] == "Create") {
    //Create counter
    //get variables
    $ipaddress = str_replace( "*", "%", $_POST["ipaddress"]);
    $description = $_POST["description"];
  
    $sqlstatement = "INSERT INTO " . $tableprefix . "ipignore values ('0000','" . 
                       $ipaddress . "','" . $description . "')";

    $datastream = @mysql_connect($sqlip, $sqluser, $sqlpass);
    @mysql_select_db($dbname);

    $result = @mysql_query($sqlstatement);

    //end create if
  };

  $sqlerror = mysql_error();

  if ($result && !$sqlerror) {
    header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/ipignore.php");
    //echo $result;
  } else {
    include('header.php');
    ?>
      <P>It would appear that there was an unexpected MySQL Error.  The returned error is as follows:</P>
    <?
    echo $sqlerror;

    ?>
      <P>
       <FORM ACTION="ipignore.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">
        <INPUT NAME="submit" TYPE="submit" VALUE="Return to Edit Ignoring IPs">
       </FORM>
      </P>
    <?

    include('footer.php');
  };

 //end ipid check
 };

 //if on session check
};

?>