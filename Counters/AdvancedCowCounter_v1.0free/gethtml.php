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
    <H3>HTML Code</H3>
    <P>This code you can enter anywhere on your site if you wish and even on other sites if you want.  This code will work on any HTML page where the browser accessing that page can still get to this location.  This same HTML coding will work with all other counters OF THIS TYPE (not forgetting to change the counterid), however, this same bit of code may not work with the other types of counters.</P>
    <?
      if ($_POST["type"] == "LC") {
        ?>
          &lt;A HREF=&quot;http://<? echo $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])); ?>/count.php?counterid=<? echo $_POST["id"]; ?>&quot;&gt; <I>Your text here.</I> &lt;/A&gt;<BR>
        <?
      } else {
        ?>
          &lt;IMG SRC=&quot;http://<? echo $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])); ?>/count.php?counterid=<? echo $_POST["id"]; ?>&quot;&gt;<BR>
        <?
      };
    ?>
    <BR>
    <P>If you are one to play with relative file linking instead of just copying and pasting the above code, then you need to call the <I>count.php</I> file (it came included) as
    <?
      if ($_POST["type"] == "LC") {
        ?>
          a link
        <?
      } else {
        ?>
          an image
        <?
      };
    ?>
    with the parameter <I>counterid</I>.  So, in other words, you need to specify the location of count.php from where you are calling it from and then finally call it with the following code.</P>
    count.php?counterid=<? echo $_POST["id"]; ?><BR>
    <BR>
    <BR>
    <FORM ACTION="main.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">
      <INPUT NAME="id" TYPE="hidden" VALUE="<? echo $counterid; ?>">
      <INPUT NAME="submit" TYPE="submit" VALUE="Back">
    </FORM>

  <?

  include("footer.php");
};
?>