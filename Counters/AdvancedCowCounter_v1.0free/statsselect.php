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

<H3>Statistics - Select Type of Statistic <A HREF="help.php#Statistics"><IMG BORDER=1 SRC="help.gif"></A></H3>

<FORM ACTION="statsselect.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">

<TABLE BORDER="0" CELLSPACING="1" CELLPADDING="2">
 <CAPTION ALIGN="BOTTOM">
  <INPUT NAME="submit" TYPE="submit" VALUE="To Step 2">
 </CAPTION>
 <TR>
 <TD>
  <SELECT NAME="statselect">
    <OPTION VALUE="TotalHits"<? if (($_POST["statselect"] == "TotalHits") || (!isset($_POST["statselect"]))) { echo " SELECTED"; }; ?>>Total Hits
    <OPTION VALUE="YearlyHits"<? if ($_POST["statselect"] == "YearlyHits") { echo " SELECTED"; }; ?>>Yearly Hits
    <OPTION VALUE="MonthlyHitsOverAYear"<? if ($_POST["statselect"] == "MonthlyHitsOverAYear") { echo " SELECTED"; }; ?>>Monthly Hits Over A Year
    <OPTION VALUE="WeeklyHitsOverAYear"<? if ($_POST["statselect"] == "WeeklyHitsOverAYear") { echo " SELECTED"; }; ?>>Weekly Hits Over A Year
    <OPTION VALUE="DailyHitsOverAMonth"<? if ($_POST["statselect"] == "DailyHitsOverAMonth") { echo " SELECTED"; }; ?>>Daily Hits Over A Month
    <OPTION VALUE="DailyHitsOverAWeek"<? if ($_POST["statselect"] == "DailyHitsOverAWeek") { echo " SELECTED"; }; ?>>Daily Hits Over A Week
    <OPTION VALUE="StatTableDump"<? if ($_POST["statselect"] == "StatTableDump") { echo " SELECTED"; }; ?>>Statistical Daily Counter Table
  </SELECT>
 </TD>
 </TR>
</TABLE>

</FORM>


  <?

  if (isset($_POST["submit"]) && isset($_POST["statselect"])) {
    $stattype = $_POST["statselect"];

    ?>
      <HR>
      <H3>Statistics - Select Parameters</H3>
      <FORM ACTION="statsselectdo.php" ENCTYPE="x-www-form-urlencoded" METHOD="POST">
      <INPUT TYPE="hidden" NAME="statselect" VALUE="<? echo $stattype; ?>">
    <?

    //connect to database;
    $datastream = @mysql_connect($sqlip, $sqluser, $sqlpass);
    @mysql_select_db($dbname);


//------------------------------------------------------------------------------------------------------
    if ($stattype == "TotalHits") {
      ?>
        <P>You have selected the statistic: <B>Total Hits</B>.  Please select deisred counters.</P>
        <TABLE BORDER="0" CELLSPACING="1" CELLPADDING="2">
          <CAPTION ALIGN="BOTTOM">
            <INPUT NAME="submit" TYPE="submit" VALUE="Build Statistics">
          </CAPTION>
          <TR><TD>----------------------------------------------------------------------------</TD></TR>
      <?
      $sqlstatement = "SELECT counterid,name,type FROM " . $tableprefix . "counterdescription ORDER BY userorder";
      $result = @mysql_query($sqlstatement);
      $rowcount = mysql_num_rows($result);

      for ($rowcounter=0; $rowcounter < $rowcount; $rowcounter++ ) {
        $counterid = mysql_result($result, $rowcounter, "counterid");
        $countername = mysql_result($result, $rowcounter, "name");
        $countertype = mysql_result($result, $rowcounter, "type");

        if ($countertype == "SC") {
          echo "<TR><TD>----------------------------------------------------------------------------</TD></TR>";
        } else {
          ?>
            <TR><TD><INPUT TYPE="checkbox" NAME="counters[]" VALUE="<? echo $counterid; ?>" CHECKED>
          <?
          echo $countername . "</TD></TR>";
        };
      //end for loop
      };

      ?>
        <TR><TD>----------------------------------------------------------------------------</TD></TR>
        </TABLE>
      <?




//------------------------------------------------------------------------------------------------------      
    } elseif ($stattype == "YearlyHits") {
      ?>
        <P>You have selected the statistic: <B>Yearly Hits</B>.  Please select deisred counters.</P>
        <TABLE BORDER="0" CELLSPACING="1" CELLPADDING="2">
          <CAPTION ALIGN="BOTTOM">
            <INPUT NAME="submit" TYPE="submit" VALUE="Build Statistics">
          </CAPTION>
          <TR><TD>----------------------------------------------------------------------------</TD></TR>
      <?
      $sqlstatement = "SELECT counterid,name,type FROM " . $tableprefix . "counterdescription ORDER BY userorder";
      $result = @mysql_query($sqlstatement);
      $rowcount = mysql_num_rows($result);

      for ($rowcounter=0; $rowcounter < $rowcount; $rowcounter++ ) {
        $counterid = mysql_result($result, $rowcounter, "counterid");
        $countername = mysql_result($result, $rowcounter, "name");
        $countertype = mysql_result($result, $rowcounter, "type");

        if ($countertype == "SC") {
          echo "<TR><TD>----------------------------------------------------------------------------</TD></TR>";
        } else {
          ?>
            <TR><TD><INPUT TYPE="checkbox" NAME="counters[]" VALUE="<? echo $counterid; ?>" CHECKED>
          <?
          echo $countername . "</TD></TR>";
        };
      //end for loop
      };

      ?>
        <TR><TD>----------------------------------------------------------------------------</TD></TR>
        </TABLE>
      <?




//------------------------------------------------------------------------------------------------------
    } elseif ($stattype == "MonthlyHitsOverAYear") {
      ?>
        <P>You have selected the statistic: <B>Monthly Hits Over a Year</B>.  Please select deisred counters and years to include.</P>
        <TABLE BORDER="0" CELLSPACING="1" CELLPADDING="2">
          <CAPTION ALIGN="BOTTOM">
            <INPUT NAME="submit" TYPE="submit" VALUE="Build Statistics">
          </CAPTION>
          <TR><TD>----------------------------------------------------------------------------</TD></TR>
      <?

      //select years
      $sqlstatement = "SELECT year(time) FROM " . $tableprefix . "counting GROUP BY year(time) ORDER BY year(time)";
      $yearresult = @mysql_query($sqlstatement);
      $yearcount = mysql_num_rows($yearresult);

      ?>
        <TR><TD>
          Select Year(s):
          <SELECT NAME="yearselect">
          <OPTION SELECTED>All Years (Average)
        <?

        for ( $rowcounter=0; $rowcounter < $yearcount; $rowcounter++ ) {
          $year = mysql_result($yearresult, $rowcounter, "year(time)");
          ?><OPTION><?
          echo $year;
        };

        ?>
          </SELECT>
        </TD></TR>
        <TR><TD>----------------------------------------------------------------------------</TD></TR>
      <?


      // select counters

      $sqlstatement = "SELECT counterid,name,type FROM " . $tableprefix . "counterdescription ORDER BY userorder";
      $result = @mysql_query($sqlstatement);
      $rowcount = mysql_num_rows($result);

      for ($rowcounter=0; $rowcounter < $rowcount; $rowcounter++ ) {
        $counterid = mysql_result($result, $rowcounter, "counterid");
        $countername = mysql_result($result, $rowcounter, "name");
        $countertype = mysql_result($result, $rowcounter, "type");

        if ($countertype == "SC") {
          echo "<TR><TD>----------------------------------------------------------------------------</TD></TR>";
        } else {
          ?>
            <TR><TD><INPUT TYPE="checkbox" NAME="counters[]" VALUE="<? echo $counterid; ?>" CHECKED>
          <?
          echo $countername . "</TD></TR>";
        };
      //end for loop
      };

      ?>
        <TR><TD>----------------------------------------------------------------------------</TD></TR>
        </TABLE>
      <?




//------------------------------------------------------------------------------------------------------
    } elseif ($stattype == "WeeklyHitsOverAYear") {
      ?>
        <P>You have selected the statistic: <B>Weekly Hits Over a Year</B>.  Please select deisred counters and years to include.</P>
        <TABLE BORDER="0" CELLSPACING="1" CELLPADDING="2">
          <CAPTION ALIGN="BOTTOM">
            <INPUT NAME="submit" TYPE="submit" VALUE="Build Statistics">
          </CAPTION>
          <TR><TD>----------------------------------------------------------------------------</TD></TR>
      <?

      //select years
      $sqlstatement = "SELECT year(time) FROM " . $tableprefix . "counting GROUP BY year(time) ORDER BY year(time)";
      $yearresult = @mysql_query($sqlstatement);
      $yearcount = mysql_num_rows($yearresult);

      ?>
        <TR><TD>
          Select Year(s):
          <SELECT NAME="yearselect">
          <OPTION SELECTED>All Years (Average)
        <?

        for ( $rowcounter=0; $rowcounter < $yearcount; $rowcounter++ ) {
          $year = mysql_result($yearresult, $rowcounter, "year(time)");
          ?><OPTION><?
          echo $year;
        };

        ?>
          </SELECT>
        </TD></TR>
        <TR><TD>----------------------------------------------------------------------------</TD></TR>
      <?


      // select counters

      $sqlstatement = "SELECT counterid,name,type FROM " . $tableprefix . "counterdescription ORDER BY userorder";
      $result = @mysql_query($sqlstatement);
      $rowcount = mysql_num_rows($result);

      for ($rowcounter=0; $rowcounter < $rowcount; $rowcounter++ ) {
        $counterid = mysql_result($result, $rowcounter, "counterid");
        $countername = mysql_result($result, $rowcounter, "name");
        $countertype = mysql_result($result, $rowcounter, "type");

        if ($countertype == "SC") {
          echo "<TR><TD>----------------------------------------------------------------------------</TD></TR>";
        } else {
          ?>
            <TR><TD><INPUT TYPE="checkbox" NAME="counters[]" VALUE="<? echo $counterid; ?>" CHECKED>
          <?
          echo $countername . "</TD></TR>";
        };
      //end for loop
      };

      ?>
        <TR><TD>----------------------------------------------------------------------------</TD></TR>
        </TABLE>
      <?




//------------------------------------------------------------------------------------------------------
    } elseif ($stattype == "DailyHitsOverAMonth") {
      ?>
        <P>You have selected the statistic: <B>Daily Hits Over a Month</B>.  Please select deisred counters, years, and months to include.</P>
        <TABLE BORDER="0" CELLSPACING="1" CELLPADDING="2">
          <CAPTION ALIGN="BOTTOM">
            <INPUT NAME="submit" TYPE="submit" VALUE="Build Statistics">
          </CAPTION>
          <TR><TD>----------------------------------------------------------------------------</TD></TR>
      <?

      //select years
      $sqlstatement = "SELECT year(time) FROM " . $tableprefix . "counting GROUP BY year(time) ORDER BY year(time)";
      $yearresult = @mysql_query($sqlstatement);
      $yearcount = mysql_num_rows($yearresult);

      //select months
      $sqlstatement = "SELECT monthname(time) FROM " . $tableprefix . "counting GROUP BY monthname(time) ORDER BY month(time)";
      $monthresult = @mysql_query($sqlstatement);
      $monthcount = mysql_num_rows($monthresult);

      //show years and months
      ?>
        <TR><TD>
          Select Year(s):
          <SELECT NAME="yearselect">
          <OPTION SELECTED>All Years (Average)
        <?

        for ( $rowcounter=0; $rowcounter < $yearcount; $rowcounter++ ) {
          $year = mysql_result($yearresult, $rowcounter, "year(time)");
          ?><OPTION><?
          echo $year;
        };

        ?>
          </SELECT>
        </TD></TR>
        <TR><TD>
          Select Month(s):
          <SELECT NAME="monthselect">
          <OPTION SELECTED>All Months (Average)
        <?

        for ( $rowcounter=0; $rowcounter < $monthcount; $rowcounter++ ) {
          $month = mysql_result($monthresult, $rowcounter, "monthname(time)");
          ?><OPTION><?
          echo $month;
        };
      ?>
        </TD></TR>
        <TR><TD>----------------------------------------------------------------------------</TD></TR>
      <?


      // select counters

      $sqlstatement = "SELECT counterid,name,type FROM " . $tableprefix . "counterdescription ORDER BY userorder";
      $result = @mysql_query($sqlstatement);
      $rowcount = mysql_num_rows($result);

      for ($rowcounter=0; $rowcounter < $rowcount; $rowcounter++ ) {
        $counterid = mysql_result($result, $rowcounter, "counterid");
        $countername = mysql_result($result, $rowcounter, "name");
        $countertype = mysql_result($result, $rowcounter, "type");

        if ($countertype == "SC") {
          echo "<TR><TD>----------------------------------------------------------------------------</TD></TR>";
        } else {
          ?>
            <TR><TD><INPUT TYPE="checkbox" NAME="counters[]" VALUE="<? echo $counterid; ?>" CHECKED>
          <?
          echo $countername . "</TD></TR>";
        };
      //end for loop
      };

      ?>
        <TR><TD>----------------------------------------------------------------------------</TD></TR>
        </TABLE>
      <?




//------------------------------------------------------------------------------------------------------
    } elseif ($stattype == "DailyHitsOverAWeek") {
      ?>
        <P>You have selected the statistic: <B>Daily Hits Over a Week</B>.  Please select deisred counters, years, and weeks to include.</P>
        <TABLE BORDER="0" CELLSPACING="1" CELLPADDING="2">
          <CAPTION ALIGN="BOTTOM">
            <INPUT NAME="submit" TYPE="submit" VALUE="Build Statistics">
          </CAPTION>
          <TR><TD>----------------------------------------------------------------------------</TD></TR>
      <?

      //select years
      $sqlstatement = "SELECT year(time) FROM " . $tableprefix . "counting GROUP BY year(time) ORDER BY year(time)";
      $yearresult = @mysql_query($sqlstatement);
      $yearcount = mysql_num_rows($yearresult);

      //select weeks
      $sqlstatement = "SELECT week(time,0) FROM " . $tableprefix . "counting GROUP BY week(time,0) ORDER BY week(time,0)";
      $weekresult = @mysql_query($sqlstatement);
      $weekcount = mysql_num_rows($weekresult);

      //show years and weeks
      ?>
        <TR><TD>
          Select Year(s):
          <SELECT NAME="yearselect">
          <OPTION SELECTED>All Years (Average)
        <?

        for ( $rowcounter=0; $rowcounter < $yearcount; $rowcounter++ ) {
          $year = mysql_result($yearresult, $rowcounter, "year(time)");
          ?><OPTION><?
          echo $year;
        };

        ?>
          </SELECT>
        </TD></TR>
        <TR><TD>
          Select Week(s):
          <SELECT NAME="weekselect">
          <OPTION SELECTED>All Weeks (Average)
        <?

        for ( $rowcounter=0; $rowcounter < $weekcount; $rowcounter++ ) {
          $week = mysql_result($weekresult, $rowcounter, "week(time,0)");
          ?><OPTION><?
          echo $week;
        };
      ?>
        </TD></TR>
        <TR><TD>----------------------------------------------------------------------------</TD></TR>
      <?


      // select counters

      $sqlstatement = "SELECT counterid,name,type FROM " . $tableprefix . "counterdescription ORDER BY userorder";
      $result = @mysql_query($sqlstatement);
      $rowcount = mysql_num_rows($result);

      for ($rowcounter=0; $rowcounter < $rowcount; $rowcounter++ ) {
        $counterid = mysql_result($result, $rowcounter, "counterid");
        $countername = mysql_result($result, $rowcounter, "name");
        $countertype = mysql_result($result, $rowcounter, "type");

        if ($countertype == "SC") {
          echo "<TR><TD>----------------------------------------------------------------------------</TD></TR>";
        } else {
          ?>
            <TR><TD><INPUT TYPE="checkbox" NAME="counters[]" VALUE="<? echo $counterid; ?>" CHECKED>
          <?
          echo $countername . "</TD></TR>";
        };
      //end for loop
      };

      ?>
        <TR><TD>----------------------------------------------------------------------------</TD></TR>
        </TABLE>
      <?




//------------------------------------------------------------------------------------------------------
    } elseif ($stattype == "StatTableDump") {
      ?>
        <P>You have selected the statistic: <B>Statistical Daily Counter Table</B>.  Please select deisred counters to include.</P>
        <TABLE BORDER="0" CELLSPACING="1" CELLPADDING="2">
          <CAPTION ALIGN="BOTTOM">
            <INPUT NAME="submit" TYPE="submit" VALUE="Build Statistics">
          </CAPTION>
          <TR><TD>----------------------------------------------------------------------------</TD></TR>
      <?

      //select years
      $sqlstatement = "SELECT year(time) FROM " . $tableprefix . "counting GROUP BY year(time) ORDER BY year(time)";
      $yearresult = @mysql_query($sqlstatement);
      $yearcount = mysql_num_rows($yearresult);

      ?>
        <TR><TD>
          Select Year(s):
          <SELECT NAME="yearselect">
        <?

        for ( $rowcounter=0; $rowcounter < $yearcount; $rowcounter++ ) {
          $year = mysql_result($yearresult, $rowcounter, "year(time)");
          ?><OPTION><?
          echo $year;
        };

        ?>
          </SELECT>
        </TD></TR>
        <TR><TD>----------------------------------------------------------------------------</TD></TR>
      <?


      // select counters

      $sqlstatement = "SELECT counterid,name,type FROM " . $tableprefix . "counterdescription ORDER BY userorder";
      $result = @mysql_query($sqlstatement);
      $rowcount = mysql_num_rows($result);

      for ($rowcounter=0; $rowcounter < $rowcount; $rowcounter++ ) {
        $counterid = mysql_result($result, $rowcounter, "counterid");
        $countername = mysql_result($result, $rowcounter, "name");
        $countertype = mysql_result($result, $rowcounter, "type");

        if ($countertype == "SC") {
          echo "<TR><TD>----------------------------------------------------------------------------</TD></TR>";
        } else {
          ?>
            <TR><TD><INPUT TYPE="checkbox" NAME="counters[]" VALUE="<? echo $counterid; ?>" CHECKED>
          <?
          echo $countername . "</TD></TR>";
        };
      //end for loop
      };

      ?>
        <TR><TD>----------------------------------------------------------------------------</TD></TR>
        </TABLE>
      <?




//------------------------------------------------------------------------------------------------------
    } else {
      //error: statistic doesn't exist.
      ?>
        You have selected a statistic that does not exist. This is a program error. Oops!!
      <?


//------------------------------------------------------------------------------------------------------
    };

    @mysql_close($datastream);

    //end if statement for second step
  };


  include("footer.php");
};
?>