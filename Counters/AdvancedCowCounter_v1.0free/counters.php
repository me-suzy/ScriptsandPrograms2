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
  //send page

  include("header.php");

  if (isset($_GET["order"])) {
    $sortorder = $_GET["order"];
    $sqlstatement = "SELECT * FROM " . $tableprefix . "counterdescription WHERE type!='SC' ORDER BY " . $sortorder;
  } else {
    $sqlstatement = "SELECT * FROM " . $tableprefix . "counterdescription ORDER BY userorder";
  }

  $datastream = @mysql_connect($sqlip, $sqluser, $sqlpass);
  @mysql_select_db($dbname);
  $result = @mysql_query($sqlstatement);

  if (!$result) {
    //error getting data
    ?>
      There seems to have been an error, the error code returned from you MySQL database was:<BR>
      <?
        echo mysql_error() . "<BR>";
      ?>
      If you do not understand this error, please contact your local system administrator.<BR>
    <?
  } else {
    // results retrieved successfully.

    $rowcount = mysql_num_rows($result);


    ?>



<H3>
  Change Existing Counters <A HREF="help.php#AddEditCounters"><IMG BORDER=1 SRC="help.gif"></A>
</H3>
<?
  if ($rowcount > 0) {
    ?>
<TABLE BORDER="1" CELLSPACING="0" CELLPADDING="2">
  <TR>
    <TH NOWRAP><A HREF="counters.php?order=counterid">CID</A><BR><A HREF="help.php#THeadings_CID"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP><A HREF="counters.php">Order</A><BR><A HREF="help.php#THeadings_Order"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP><A HREF="counters.php?order=name">Name</A><BR><A HREF="help.php#THeadings_Name"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP><A HREF="counters.php?order=type">Type</A><BR><A HREF="help.php#THeadings_Type"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP><A HREF="counters.php?order=minimumdigits">Min. Digits</A><BR><A HREF="help.php#THeadings_MinDigits"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP><A HREF="counters.php?order=datetimestart">Started</A><BR><A HREF="help.php#THeadings_Started"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP>Reset<BR><A HREF="help.php#THeadings_Reset"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP>Edit<BR><A HREF="help.php#THeadings_Edit"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP>Delete<BR><A HREF="help.php#THeadings_Delete"><IMG BORDER=1 SRC="help.gif"></A></TH>
  </TR>
  <?
    for ( $rowcounter=0; $rowcounter < $rowcount; $rowcounter++ )
     {
      $counterid = mysql_result($result,$rowcounter,"counterid");

      if (mysql_result($result,$rowcounter,"type") == "SC") {
        ?>
        <TR>
          <TD></TD>
          <FORM ACTION="order.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">
          <TD ALIGN=CENTER NOWRAP>
            <INPUT NAME="id" TYPE="hidden" VALUE="<? echo $counterid; ?>">
            <INPUT NAME="page" TYPE="hidden" VALUE="edit">
            <INPUT NAME="up" TYPE="submit" VALUE="/\">
            <INPUT NAME="down" TYPE="submit" VALUE="\/">
          </TD>
          </FORM>
          <TD></TD>
          <TD></TD>
          <TD></TD>
          <TD></TD>
          <TD></TD>
          <TD></TD>
          <FORM ACTION="countersdo.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">
          <TD ALIGN="CENTER" NOWRAP>
            <INPUT NAME="id" TYPE="hidden" VALUE="<? echo $counterid; ?>">
            <INPUT NAME="submit" TYPE="submit" VALUE="Delete">
          </TD>
          </FORM>
        </TR>
        <?
      } else {
        ?>
        <TR>
          <TD ALIGN="CENTER" NOWRAP>
            <?
              echo $counterid;
            ?>
          </TD>
          <FORM ACTION="order.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">
          <TD ALIGN=CENTER NOWRAP>
            <INPUT NAME="id" TYPE="hidden" VALUE="<? echo $counterid; ?>">
            <INPUT NAME="page" TYPE="hidden" VALUE="edit">
            <INPUT NAME="up" TYPE="submit" VALUE="/\">
            <INPUT NAME="down" TYPE="submit" VALUE="\/"> 
          </TD>
          </FORM>
          <TD ALIGN="CENTER" WIDTH="90%">
            <? echo mysql_result($result,$rowcounter,"name"); ?>
          </TD>
          <TD ALIGN="CENTER" NOWRAP>
            <?
              if ( mysql_result($result,$rowcounter,"type") == "HC") { 
                echo "HC";
              } elseif ( mysql_result($result,$rowcounter,"type") == "PAC") {
                echo "PAC";
              } elseif ( mysql_result($result,$rowcounter,"type") == "LC") { 
                echo "LC";
              };
            ?>
          </TD>
          <TD ALIGN="CENTER" NOWRAP>
            <? echo mysql_result($result,$rowcounter,"minimumdigits"); ?>
          </TD>
          <TD ALIGN="CENTER" NOWRAP>
            <? echo mysql_result($result,$rowcounter,"datetimestart"); ?>
          </TD>
          <FORM ACTION="countersdo.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">
          <TD ALIGN="CENTER" NOWRAP>
            <INPUT NAME="id" TYPE="hidden" VALUE="<? echo $counterid; ?>">
            <INPUT NAME="submit" TYPE="submit" VALUE="Reset">
          </TD>
          </FORM>
          <FORM ACTION="countersedit.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">
          <TD ALIGN="CENTER" NOWRAP>
            <INPUT NAME="id" TYPE="hidden" VALUE="<? echo $counterid; ?>">
            <INPUT NAME="submit" TYPE="submit" VALUE="Edit">
          </TD>
          </FORM>
          <FORM ACTION="countersdo.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">
          <TD ALIGN="CENTER" NOWRAP>
            <INPUT NAME="id" TYPE="hidden" VALUE="<? echo $counterid; ?>">
            <INPUT NAME="submit" TYPE="submit" VALUE="Delete">
          </TD>
          </FORM>
        </TR>
        <?


        //end if type = sc
      };
      //end for loop
     };
  ?>

</TABLE>
    <?
    //end of $rowcount > 0
  } else {
    //$rowcount = 0
    echo "No counters exist at this time.";
  };
?>

<BR>
<HR>

<? //--------------------------------- ?>



<FORM ACTION="countersdo.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">

<H3>
  Create a New Counter <A HREF="help.php#AddEditCounters_New"><IMG BORDER=1 SRC="help.gif"></A>
</H3>

<TABLE BORDER="1" CELLSPACING="0" CELLPADDING="2">
  <CAPTION ALIGN="BOTTOM">
    <INPUT NAME="submit" TYPE="submit" VALUE="Create">
  </CAPTION>
  <TR>
    <TH NOWRAP>Name <A HREF="help.php#AddEditCounters_New_Name"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP>Type <A HREF="help.php#AddEditCounters_New_Type"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP>Destination <A HREF="help.php#AddEditCounters_New_Dest"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP>Min. Digits <A HREF="help.php#AddEditCounters_New_MinDigits"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP>Starting Count <A HREF="help.php#AddEditCounters_New_StartCount"><IMG BORDER=1 SRC="help.gif"></A></TH>
  </TR>
  <TR>
    <TD HEIGHT="23" ALIGN="CENTER">
      <INPUT NAME="newname" TYPE="text" SIZE="25" MAXLENGTH="30">
    </TD>
    <TD HEIGHT="23" ALIGN="CENTER">
      <SELECT NAME="newtype">
        <OPTION VALUE="HC" SELECTED>Hit Counter (HC)
        <OPTION VALUE="PAC">Page Accessed Counter (PAC)
        <OPTION VALUE="LC">Link Counter (LC)
      </SELECT>
    </TD>
    <TD HEIGHT="23" ALIGN="CENTER">
      <INPUT NAME="newdestination" TYPE="text" SIZE="25" MAXLENGTH="255" VALUE="http://">
    </TD>
    <TD HEIGHT="23" ALIGN="CENTER">
      <INPUT NAME="newmindigits" TYPE="text" SIZE="15" MAXLENGTH="10" VALUE="0">
    </TD>
    <TD HEIGHT="23" ALIGN="CENTER">
      <INPUT NAME="newstart" TYPE="text" SIZE="15" MAXLENGTH="10" VALUE="0">
    </TD>
  </TR>
</TABLE>

</FORM>

<BR>
<HR>

<? //--------------------------------- ?>



<FORM ACTION="countersdo.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">

<H3>
  Create a Counter Seperator <A HREF="help.php#AddEditCounters_Sep"><IMG BORDER=1 SRC="help.gif"></A>
</H3>

<TABLE BORDER="1" CELLSPACING="0" CELLPADDING="2">
  <TR>
    <TD HEIGHT="23" ALIGN="CENTER">
      <INPUT NAME="newname" TYPE="hidden" VALUE="CowCounterSeperatorCounter">
      <INPUT NAME="newtype" TYPE="hidden" VALUE="SC">
      <INPUT NAME="newdestination" TYPE="hidden" VALUE="http://">
      <INPUT NAME="newmindigits" TYPE="hidden" VALUE="0">
      <INPUT NAME="newstart" TYPE="hidden" VALUE="0">
      <INPUT NAME="submit" TYPE="submit" VALUE="Create">
    </TD>
    <TD>
      Note: Seperators are only viewed when sorting the list by 'Order'.
    </TD>
  </TR>
</TABLE>

</FORM>


    <?

    //end query result succeeded.
  };

  include("footer.php");


  //end page send
};
?>