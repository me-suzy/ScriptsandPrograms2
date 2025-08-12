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

<H3>Edit Configuration <A HREF="help.php#EditConfig"><IMG BORDER=1 SRC="help.gif"></A></H3>

<FORM ACTION="configeditdo.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">

<?
  if ($_POST["error"] == "true") {
    ?>
      <P><FONT COLOR="#ff0000">It would appear that you did not fill in all 
      of the information required or the information was wrong for this 
      config change to take place.  Please be sure to fill in <B>ALL</B> 
      information and ensure that all information is correct before you
      try again.</FONT></P>
    <?
  };
?>

<P><CENTER><TABLE WIDTH="90%" BORDER="0" CELLSPACING="2" CELLPADDING="2">
  <CAPTION ALIGN="TOP"><B>General Setup (for CowCounter)</B></CAPTION>
  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    Bot Cracker Confuser Enabled:</TD> 
    <TD WIDTH="50%">
    <INPUT TYPE="checkbox" NAME="loginsecurity"<? if ($botconfuser == 'true') { echo ' VALUE="checked" CHECKED="on"'; }; ?>> (NOT YET IMPLEMENTED.)
    </TD>
  </TR>
</TABLE></CENTER></P>

<P><CENTER><TABLE BORDER="0" CELLSPACING="2" CELLPADDING="2" 
WIDTH="90%">
  <CAPTION ALIGN="TOP"><B>MySQL Server Setup</B></CAPTION>
   
  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    IP Address or Hostname of Database Server:<BR>
    (must be MySQL)</TD> 
    <TD WIDTH="50%">
    <INPUT NAME="sqlip" TYPE="text" SIZE="30" <? if(isset($_POST["sqlip"])) { echo 'VALUE="' . $_POST["sqlip"] . '"'; } else { echo 'VALUE="' . $sqlip . '"'; }; ?>>
    <?
      if($_POST["sqliperror"] == "blank") {
        ?>
          <FONT COLOR="#ff0000">Oops, you forgot this.</FONT>
        <?
      };
    ?>
    </TD>
  </TR>
  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    User Account Name:</TD> 
    <TD WIDTH="50%">
    <INPUT NAME="sqluser" TYPE="text" SIZE="25" <? if(isset($_POST["sqluser"])) { echo 'VALUE="' . $_POST["sqluser"] . '"'; } else { echo 'VALUE="' . $sqluser . '"'; }; ?>>
    <?
      if($_POST["sqlusererror"] == "blank") {
        ?>
          <FONT COLOR="#ff0000">Oops, you forgot this.</FONT>
        <?
      };
    ?>
    </TD>
  </TR>
  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    User Account Password:</TD> 
    <TD WIDTH="50%">
    <INPUT NAME="sqlpass" TYPE="password" SIZE="25" <? echo 'VALUE="' . $sqlpass . '"'; ?>>
    <?
      if($_POST["sqlpasserror"] == "blank") {
        ?>
          <FONT COLOR="#ff0000">Oops, you forgot this.</FONT>
        <?
      } elseif($_POST["error"] == "true") {
        ?>
          <FONT COLOR="#ff0000">This needs to be entered again.</FONT>
        <?
      };
    ?>
    </TD>
  </TR>
  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    Database Name:</TD> 
    <TD WIDTH="50%">
    <INPUT NAME="dbname" TYPE="text" SIZE="25" <? if(isset($_POST["dbname"])) { echo 'VALUE="' . $_POST["dbname"] . '"'; } else { echo 'VALUE="' . $dbname . '"'; }; ?>>
    <?
      if($_POST["dbnameerror"] == "blank") {
        ?>
          <FONT COLOR="#ff0000">Oops, you forgot this.</FONT>
        <?
      };
    ?>
    </TD>
  </TR>
  <TR>
    <TD WIDTH="50%" ALIGN="RIGHT">
    Table Name Prefix</TD> 
    <TD WIDTH="50%">
    <INPUT NAME="tableprefix" TYPE="text" SIZE="10" VALUE="<? if(isset($_POST["tableprefix"])) { echo $_POST["tableprefix"]; } else { echo $tableprefix; }; ?>">
    <?
      if($_POST["tableprefixerror"] == "blank") {
        ?>
          <FONT COLOR="#ff0000">Oops, you forgot this.</FONT>
        <?
      };
    ?>
    </TD>
  </TR>
</TABLE></CENTER></P>

<P><CENTER><INPUT NAME="submit" TYPE="submit" VALUE="Submit">
<INPUT NAME="name" TYPE="reset" VALUE="Reset"></CENTER></FORM>

<P><CENTER>To change your password, use the Change Password option to the left.</CENTER></P>
  <?

  include("footer.php");

};
?>