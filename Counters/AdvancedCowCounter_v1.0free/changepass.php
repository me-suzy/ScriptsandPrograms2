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

  include("header.php");
  ?>
    <CENTER>
      <P><B>Change Password</B></P>
      <FORM ACTION="changepassdo.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">
        <TABLE WIDTH="2%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
          <TR>
            <TD NOWRAP WIDTH="1%">Old Password: </TD>
            <TD><INPUT NAME="oldpassword" TYPE="password" SIZE="25"></TD>
          </TR>
          <TR>
            <TD NOWRAP WIDTH="1%">New Password: </TD>
            <TD><INPUT NAME="newpassword" TYPE="password" SIZE="25"></TD>
          </TR>
          <TR>
            <TD NOWRAP WIDTH="1%">Retype New Password: </TD>
            <TD><INPUT NAME="newpassword2" TYPE="password" SIZE="25"></TD>
          </TR>
        </TABLE>
        <INPUT NAME="submit" TYPE="submit" VALUE="Change Password">
      </FORM>
    </CENTER>
  <?

  if (isset($_GET["error"])) {
    //error setting password

    if ($_GET["adminpasserror"] == "oldpasserror") {
      ?>
        <CENTER>Sorry, but it looks like your original password was wrong.</CENTER>
      <?
    } elseif (($_GET["adminpasserror"] == "nonewpass") || ($_GET["adminpasserror"] == "nopassretype")) {
      ?>
        <CENTER>Sorry, but it looks like you didn't enter a new password in one of the boxes.</CENTER>
      <?
    } elseif ($_GET["adminpasserror"] == "nomatch") {
      ?>
        <CENTER>Sorry, but it looks like you retyped your new password incorrectly. They didn't match.</CENTER>
      <?
    };

  };

  include("footer.php");

};
?>