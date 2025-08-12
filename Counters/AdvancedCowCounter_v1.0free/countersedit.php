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

  if (isset($_POST["id"])) {
    $counterid = $_POST["id"];


    $sqlstatement = "SELECT * FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";
    $datastream = @mysql_connect($sqlip, $sqluser, $sqlpass);
    @mysql_select_db($dbname);
    $result = @mysql_query($sqlstatement);


?>

<H3>
  Edit Counter
</H3>

<FORM ACTION="counterseditdo.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">

<P>
<TABLE BORDER="1" CELLSPACING="2" CELLPADDING="10">
  <CAPTION ALIGN="BOTTOM">
    <INPUT NAME="submit" TYPE="submit" VALUE="Confirm Changes">
    <INPUT NAME="submit" TYPE="submit" VALUE="Cancel Changes">
    <INPUT NAME="name" TYPE="reset" VALUE="Reset Form">
  </CAPTION>
  <TR>
    <TD NOWRAP><B>Counter ID</B> <A HREF="help.php#AddEditCounters_New_CID"><IMG BORDER=1 SRC="help.gif"></A></TD>
    <TD><B><INPUT NAME="counterid" TYPE=hidden VALUE="<? echo mysql_result($result,0,"counterid"); ?>"><? echo mysql_result($result,0,"counterid"); ?></B></TD>
  </TR>
  <TR>
    <TD NOWRAP><B>Counter Start</B> <A HREF="help.php#AddEditCounters_New_Started"><IMG BORDER=1 SRC="help.gif"></A><BR><FONT SIZE="-2"> (YYYY-MM-DD HH:MM:SS)</FONT></TD>
    <TD><B><? echo mysql_result($result,0,"datetimestart"); ?></B></TD>
  </TR>
  <TR>
    <TD NOWRAP><B>Name:</B> <A HREF="help.php#AddEditCounters_New_Name"><IMG BORDER=1 SRC="help.gif"></A></TD>
    <TD><INPUT NAME="name" TYPE="text" SIZE="30" MAXLENGTH="30" VALUE="<? echo mysql_result($result,0,"name"); ?>"></TD>
  </TR>
  <TR>
    <TD NOWRAP><B>Type:</B> <A HREF="help.php#AddEditCounters_New_Type"><IMG BORDER=1 SRC="help.gif"></A></TD>
    <TD>
      <SELECT NAME="type">
        <OPTION VALUE="HC"<? if ( mysql_result($result,$rowcounter,"type") == "HC") { echo " SELECTED"; }; ?>>Hit Counter
        <OPTION VALUE="PAC"<? if ( mysql_result($result,$rowcounter,"type") == "PAC") { echo " SELECTED"; }; ?>>Page Accessed Counter
        <OPTION VALUE="LC"<? if ( mysql_result($result,$rowcounter,"type") == "LC") { echo " SELECTED"; }; ?>>Link Counter
      </SELECT>
    </TD>
  </TR>
  <TR>
    <TD NOWRAP><B>Destination:</B> <A HREF="help.php#AddEditCounters_New_Dest"><IMG BORDER=1 SRC="help.gif"></A></TD>
    <TD>
      <INPUT NAME="destination" TYPE="text" SIZE="50" MAXLENGTH="255" VALUE="<? echo mysql_result($result,0,"destination"); ?>">
    </TD>
  </TR>
  <TR>
    <TD NOWRAP><B>Minimum Display Digits:</B> <A HREF="help.php#AddEditCounters_New_MinDigits"><IMG BORDER=1 SRC="help.gif"></A></TD>
    <TD>
      <INPUT NAME="minimumdigits" TYPE="text" SIZE="15" MAXLENGTH="10" VALUE="<? echo mysql_result($result,0,"minimumdigits"); ?>">
    </TD>
  </TR>
  <TR>
    <TD NOWRAP><B>Starting Count:</B> <A HREF="help.php#AddEditCounters_New_StartCount"><IMG BORDER=1 SRC="help.gif"></A></TD>
    <TD>
      <INPUT NAME="startingcount" TYPE="text" SIZE="15" MAXLENGTH="10" VALUE="<? echo mysql_result($result,0,"startingcount"); ?>">
    </TD>
  </TR>
</TABLE>

</FORM>



<?

    include("footer.php");

    //end of ID check if statement good
  } else {
    header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/logout.php?session=bad");
  };

  //end of session check if statement

};
?>