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
  if (isset($_POST["submit"])) {
    if ($_POST['submit'] == "Cancel Changes") {
      header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/counters.php");
    } elseif ($_POST['submit'] == "Confirm Changes") {


      $sqlstatement = "UPDATE " . $tableprefix . "counterdescription " . 
                      "SET name = '" . $_POST["name"] . 
                      "', type = '" . $_POST["type"] . 
                      "', destination = '" . $_POST["destination"] . 
                      "', startingcount = '" . $_POST["startingcount"] . 
                      "', minimumdigits = '" . $_POST["minimumdigits"] . 
                      "' WHERE counterid = " . $_POST["counterid"];


      //echo $sqlstatement;
      $datastream = @mysql_connect($sqlip, $sqluser, $sqlpass);
      @mysql_select_db($dbname);
      $result = @mysql_query($sqlstatement);
      //echo $result;
      //echo mysql_error();

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


      //end if submit = confirm
    };


    //end if submit
  };


  //end session info good
};
?>