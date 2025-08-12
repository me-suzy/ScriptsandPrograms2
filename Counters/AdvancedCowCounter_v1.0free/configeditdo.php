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

  //destroy all old vars except password
  unset($botconfuser);
  unset($sqlip);
  unset($sqlpass);
  unset($dbname);
  unset($tableprefix);


function errorlink() {
 extract($GLOBALS);

 $link = "error=true";
  
 if (isset($adminpasserror)) { $link = $link . "&adminpasserror=" . $adminpasserror; };
 
 if (isset($sqlip)) { $link = $link . "&sqlip=" . $sqlip; };
 if (isset($sqliperror)) { $link = $link . "&sqliperror=" . $sqliperror; };
 
 if (isset($sqluser)) { $link = $link . "&sqluser=" . $sqluser; };
 if (isset($sqlusererror)) { $link = $link . "&sqlusererror=" . $sqlusererror; };
 
 if (isset($sqlpasserror)) { $link = $link . "&sqlpasserror=" . $sqlpasserror; };
 
 if (isset($dbname)) { $link = $link . "&dbname=" . $dbname; };
 if (isset($dbnameerror)) { $link = $link . "&dbnameerror=" . $dbnameerror; };
 
 if (isset($tableprefix)) { $link = $link . "&tableprefix=" . $tableprefix; };
 if (isset($tableprefixerror)) { $link = $link . "&tableprefixerror=" . $tableprefixerror; };
 
 $link = $link . "&botconfuser=" . $botconfuser;
 return $link;
};
//end function errorout




//Variable Pickup and validation --------------------------------------------

//Bot Confuser Enabled
if ($_POST["loginsecurity"] == "on" || $_POST["loginsecurity"] == "checked") {
 $botconfuser = "true";
} else {
 $botconfuser = "false";
};

//MySQL IP
if (!empty($_POST["sqlip"])) {
 $sqlip = $_POST["sqlip"];
} else {
 $sqliperror = "blank";
};

//MySQL Username
if (!empty($_POST["sqluser"])) {
 $sqluser = $_POST["sqluser"];
} else {
 $sqlusererror = "blank";
};

//MySQL Password
if (!empty($_POST["sqlpass"])) {
 $sqlpass = $_POST["sqlpass"];
} else {
 $sqlpasserror = "blank";
};

//MySQL DB name
if (!empty($_POST["dbname"])) {
 $dbname = $_POST["dbname"];
} else {
 $dbnameerror = "blank";
};

//MySQL Table Prefix
if (!empty($_POST["tableprefix"])) {
 $tableprefix = $_POST["tableprefix"];
} else {
 $tableprefixerror = "blank";
};

//Variable Gathering complete


if (isset($adminpasserror) || isset($webiperror) || isset($webporterror) || isset($scriptlocationerror) || isset($sqliperror) || isset($sqlusererror) || isset($sqlpasserror) || isset($dbnameerror) || isset($tableprefixerror)) {
  header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/configedit.php?" . errorlink() );
} 

//write config.php file

$configfilename = dirname($_SERVER["SCRIPT_FILENAME"]) . "/config.php";
$configfile = @fopen($configfilename, 'w');
if (!$configfile) {
  //error opening file
  $errorstring = "Unable to open config.php file for writing.<BR>";
  $error = "true";
} else {
  //file is open
  $errorstring = "File config.php opened for writing.<BR>";
  @fwrite($configfile, "<?\r\n");
  @fwrite($configfile, "\$adminpass = '" . $adminpass . "';\r\n");
  @fwrite($configfile, "\$botconfuser = '" . $botconfuser . "';\r\n");
  @fwrite($configfile, "\$sqlip = '" . $sqlip . "';\r\n");
  @fwrite($configfile, "\$sqluser = '" . $sqluser . "';\r\n");
  @fwrite($configfile, "\$sqlpass = '" . $sqlpass . "';\r\n");
  @fwrite($configfile, "\$dbname = '" . $dbname . "';\r\n");
  @fwrite($configfile, "\$tableprefix = '" . $tableprefix . "';\r\n");
  @fwrite($configfile, "?>");
  @fclose($configfile);
  $errorstring = $errorstring . "Finished writing to config.php file, file closed.<BR>";
};

//end write config.php file

include("header.php");
?>

  <CENTER>
    <TABLE WIDTH="80%">
      <TR>
        <TD>
          Attempting file write... Standby...<BR>
          <?
            echo $errorstring;
            echo "<P><BR></P>";
          ?>
        </TD>
      </TR>
      <?
        if (isset($error)) {
      ?>
      <TR>
        <TD>
          Since it looked like you had problems during the requested changes, use the error replies above to resolve the problem or contact your system administrator.
        </TD>
      </TR>
      <?
        } else {
          // no error writing file
          ?>
            <TR>
              <TD>
                Since it looks like there weren't any problems, changes are complete.
              </TD>
            </TR>
          <?
        };
      ?>
    </TABLE>
  </CENTER>

<?
include ("footer.php");


  //end session check
};
?>