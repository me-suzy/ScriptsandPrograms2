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
  Current Counter Stats
</H3>
<?
  if ($rowcount > 0) {
    ?>
<TABLE BORDER="1" CELLSPACING="0" CELLPADDING="2">
  <TR>
    <TH NOWRAP><A HREF="main.php?order=counterid">CID</A><BR><A HREF="help.php#THeadings_CID"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP><A HREF="main.php">Order</A><BR><A HREF="help.php#THeadings_Order"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP><A HREF="main.php?order=name">Name</A><BR><A HREF="help.php#THeadings_Name"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP># of Hits<BR><A HREF="help.php#THeadings_NumHits"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP><A HREF="main.php?order=lastviewed">Last Viewed</A><BR><A HREF="help.php#THeadings_LastViewed"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP><A HREF="main.php?order=type">Type</A><BR><A HREF="help.php#THeadings_Type"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP><A HREF="main.php?order=datetimestart">Started</A><BR><A HREF="help.php#THeadings_Started"><IMG BORDER=1 SRC="help.gif"></A></TH>
    <TH NOWRAP>HTML<BR><A HREF="help.php#THeadings_HTML"><IMG BORDER=1 SRC="help.gif"></A></TH>
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
            <INPUT NAME="up" TYPE="submit" VALUE="/\">
            <INPUT NAME="down" TYPE="submit" VALUE="\/"> 
          </TD>
          </FORM>
          <TD ALIGN="CENTER" WIDTH="90%">
            <? echo mysql_result($result,$rowcounter,"name"); ?>
          </TD>
          <TD ALIGN="CENTER">
            <?
              $sqlstatement = "SELECT COUNT(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "'";
              $countresult = @mysql_query($sqlstatement);
              $hitcount = mysql_result($countresult,0,"COUNT(*)");
              $startcount = mysql_result($result,$rowcounter,"startingcount");
              
              $NumHits = $hitcount + $startcount;
              echo $NumHits;
            ?>
          </TD>
          <TD ALIGN="CENTER" NOWRAP>
            <?
              echo mysql_result($result,$rowcounter,"lastviewed");
            ?>
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
            <? echo mysql_result($result,$rowcounter,"datetimestart"); ?>
          </TD>
          <FORM ACTION="gethtml.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">
          <TD ALIGN="CENTER" NOWRAP>
            <INPUT NAME="id" TYPE="hidden" VALUE="<? echo $counterid; ?>">
            <INPUT NAME="type" TYPE="hidden" VALUE="<? echo mysql_result($result,$rowcounter,"type"); ?>">
            <INPUT NAME="submit" TYPE="submit" VALUE="Get">
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
  $sqlstatement = "SELECT datetimeviewreset,NOW() from " . $tableprefix . "counterdescription limit 1";
  $timeresult = @mysql_query($sqlstatement);
  $resettime = mysql_result($timeresult,0,"datetimeviewreset");
  $currenttime = mysql_result($timeresult,0,"NOW()");
?>

<BR>
<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0">
  <TR>
    <TD>Time of Last Reset: <? echo $resettime; ?>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;Current Time: <? echo $currenttime; ?></TD>
  </TR>
  <TR>
    <TD>
      <A HREF="lastvieweddo.php">Reset 'Last Viewed' to current counter numbers</A> <A HREF="help.php#THeadings_LastViewed"><IMG BORDER=1 SRC="help.gif"></A>
    </TD>
  </TR>
</TABLE>

    <?
    //end of $rowcount > 0
  } else {
    //$rowcount = 0
    echo "No counters exist at this time.";
  };
?>

    <?
    //end of results retrieved successfully.
  };
  @mysql_close($datastream);
  include("footer.php");

};
?>