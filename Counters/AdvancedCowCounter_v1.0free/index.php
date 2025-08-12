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

if (!file_exists("config.php")) {
  //check for config file - config file missing.

  header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/setup/index.php");

} elseif (!isset($_POST["pass"])) {
  clearstatcache();

  //send login page

  ?>

<HTML>
<HEAD>
  <TITLE>Welcome to the Advanced CowCounter</TITLE>
</HEAD>
<BODY BGCOLOR="#ffffff" BACKGROUND="CowSpots.gif">

<H1>
 <IMG SRC="CowLogo.gif" WIDTH="90" HEIGHT="81" ALIGN="BOTTOM" BORDER="0">
 Advanced CowCounter
 <HR>
</H1>

  <?


 if (isset($_POST["firstlogin"])) {
  ?>
   <P>Welcome to the administrative login for the Advanced CowCounter, you may bookmark this page to allow you to make changed to your counters and general setup. You can login with your password that you set during the Setup process. </P>
  <?
 };
 if (is_dir("setup")) {
  ?>
   <P><B><FONT COLOR="#ff0000">FOR SECURITY PURPOSES YOU MUST NOW DELETE THE SETUP DIRECTORY.</FONT></B></P>
  <?
 };

?>
<P>This page is for the administrator(s) of the Advanced CowCounter only.<BR>
Cookies must be enabled beyond this point.</P>

<FORM ACTION="index.php" METHOD="POST">
 <CENTER>
  <TABLE WIDTH="90%" BORDER="0" CELLSPACING="2" CELLPADDING="0">
  <TR>
    <TD ALIGN="CENTER">
     Password: <INPUT TYPE="password" SIZE="25" NAME="pass">
    </TD>
  </TR>
  <TR>
    <TD ALIGN="CENTER">
      <INPUT NAME="Submit" TYPE="submit" VALUE="Login">
    </TD>
  </TR>
  </TABLE>
 </CENTER>
</FORM>

<?
  if (isset($_GET["badpass"])) {
    ?>
      <CENTER>
        Sorry but it would appear that your username and password was not accepted, please try again.
      </CENTER>
    <?
  };


?>
<BR>
<HR WIDTH="50%">
<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" WIDTH="99%">
  <TR>
    <TD ALIGN=CENTER>
      Advanced CowCounter is a product of <A HREF="http://www.meezerk.com">Meezerk.com</A> released under the GPL.
    </TD>
  </TR>
</TABLE>

  </BODY>
  </HTML>

<?

  // end login page

} else {
  //check credentials

  include("config.php");

  if ($_POST["pass"] == $adminpass) {
    //credentials good

    session_start();
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['pass'] = $_POST['pass'];
    $_SESSION['access'] = "granted";

    //header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/main.php");

    //------------- Redirect for split second delay for IIS session bug.
    ?>
      <HTML>
      <HEAD>
        <META http-equiv='refresh' content='1;URL=main.php'>
        <TITLE>Meezerk's Advanced CowCounter - Redirecting</TITLE>
      </HEAD>
      <BODY BGCOLOR="#ffffff" BACKGROUND="CowSpots.gif">
        <BR>
        <CENTER>
          <H2>You are now being logged in.</H2>
          <H3>Please Standby...</H3><BR>
          If your browser doesn't go to the next page then please <A HREF="main.php">goto the next page</A>.
        </CENTER>
      </BODY>
      </HTML>
    <?

  } else {
    //credentials bad
    header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/index.php?badpass=true");
  };
};
?>