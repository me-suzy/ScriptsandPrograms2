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
unset($_SESSION["ip"]);
unset($_SESSION["pass"]);
unset($_SESSION["access"]);

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

if (isset($_GET["session"])) {
  echo "<P><CENTER>Sorry, but it looks like you either tried to access an internal page directly or your session has expired.</CENTER></P>";
} else {
  echo "<P><CENTER>You have been logged out.</CENTER></P>";
};

?>

<P><CENTER><A HREF="index.php">Login Again</A></CENTER></P>
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
