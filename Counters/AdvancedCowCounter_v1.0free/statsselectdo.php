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

  if (isset($_POST["counters"])) {

    //connect to database;
    $datastream = @mysql_connect($sqlip, $sqluser, $sqlpass);
    @mysql_select_db($dbname);

    $countersselected = $_POST["counters"];
    $stattype = $_POST["statselect"];


//------------------------------------------------------------------------------------------------------
    if ($stattype == "TotalHits") {
      $totalhits = 0;

      foreach($countersselected as $counterid) {
        $sqlccounting = "SELECT count(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "'";
        $sqlresultccounting = @mysql_query($sqlccounting);
        $ccount = mysql_result($sqlresultccounting,"0","count(*)");
        $totalhits = $totalhits + $ccount;
      };

      ?>
        <H3>Statistics - Total Hits <A HREF="help.php#Stats_TotalHits"><IMG BORDER=1 SRC="help.gif"></A></H3>
        <TABLE BORDER="1" CELLSPACING="0" CELLPADDING="5" WIDTH="90%">
          <CAPTION ALIGN="BOTTOM">
            Total Hits: <? echo $totalhits; ?>
          </CAPTION>
          <TR>
            <TH NOWRAP>CID</TH>
            <TH NOWRAP>Name</TH>
            <TH NOWRAP>Type</TH>
            <TH NOWRAP>Hits</TH>
            <TH NOWRAP>Percent</TH>
          </TR>
      <?

      foreach($countersselected as $counterid) {

        $sqlcdescription = "SELECT name,type FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";
        $sqlccounting = "SELECT count(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "'";
        $sqlresultcdescription = @mysql_query($sqlcdescription);
        $sqlresultccounting = @mysql_query($sqlccounting);

        $cname = mysql_result($sqlresultcdescription,"0","name");
        $ctype = mysql_result($sqlresultcdescription,"0","type");
        $ccount = mysql_result($sqlresultccounting,"0","count(*)");
        if ($totalhits == 0) {
          $cpercent = 0;
        } else {
          $cpercent = round(($ccount / $totalhits)*100);
        };

        ?>
          <TR>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $counterid; ?>
            </TD>
            <TD WIDTH="90%" ALIGN=CENTER>
              <? echo $cname; ?>
            </TD>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $ctype; ?>
            </TD>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $ccount; ?>
            </TD>
            <TD NOWRAP>
              <IMG SRC="makebar.php?length=200&percent=<? echo $cpercent; ?>"> <? echo $cpercent . "%"; ?>
            </TD>
          </TR>
        <?
      };

      ?></TABLE><?



//------------------------------------------------------------------------------------------------------
    } elseif ($stattype == "YearlyHits") {

      ?>
        <H3>Statistics - Yearly Hits <A HREF="help.php#Stats_YearlyHits"><IMG BORDER=1 SRC="help.gif"></A></H3>
        <TABLE BORDER="1" CELLSPACING="0" CELLPADDING="5" WIDTH="90%">
          <TR>
            <TH NOWRAP>CID</TH>
            <TH NOWRAP>Name</TH>
            <TH NOWRAP>Type</TH>
            <TH NOWRAP>Total Counter Hits</TH>
          </TR>
      <?

      foreach($countersselected as $counterid) {

        $sqlccounting = "SELECT count(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "'";
        $sqlresultccounting = @mysql_query($sqlccounting);
        $totalcounterhits = mysql_result($sqlresultccounting,"0","count(*)");

        $sqlcdescription = "SELECT name,type FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";
        $sqlccounting = "SELECT year(time),count(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' GROUP BY year(time) ORDER BY year(time)";
        $sqlresultcdescription = @mysql_query($sqlcdescription);
        $sqlresultccounting = @mysql_query($sqlccounting);

        $cname = mysql_result($sqlresultcdescription,"0","name");
        $ctype = mysql_result($sqlresultcdescription,"0","type");

        $numyears = mysql_num_rows($sqlresultccounting);

        ?>
          <TR>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $counterid; ?>
            </TD>
            <TD WIDTH="90%">
              <? echo $cname; ?>
            </TD>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $ctype; ?>
            </TD>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $totalcounterhits; ?>
            </TD>
         </TR>
         <TR>
           <TD></TD>
           <TD NOWRAP ALIGN=RIGHT>
             <TABLE BORDER="1" CELLSPACING="0" CELLPADDING="1" WIDTH="90%">
        <?

        for ($yearcounter=0; $yearcounter < $numyears; $yearcounter++) {

          $cyear = mysql_result($sqlresultccounting,$yearcounter,"year(time)");
          $yearcount = mysql_result($sqlresultccounting,$yearcounter,"count(*)");
          $yearpercent = round(($yearcount / $totalcounterhits)*100);

          ?>
                <TR>
                  <TD>
                    <? echo $cyear; ?>: 
                  </TD>
                  <TD>
                    <IMG SRC="makebar.php?length=200&percent=<? echo $yearpercent; ?>"> - <? echo $yearcount; ?> Hits - <? echo $yearpercent . "%"; ?>
                  </TD>
                </TR>
          <?

          //end for yearcounter
        };

        ?>
              </TABLE>
            </TD>
          </TR>
        <?

        //end foreach
      };

      ?></TABLE><?



//------------------------------------------------------------------------------------------------------
    } elseif ($stattype == "MonthlyHitsOverAYear") {
      $yearselect = $_POST["yearselect"];

      ?>
        <H3>Statistics - Monthly Hits Over a Year <A HREF="help.php#Stats_MonthlyHitsOverAYear"><IMG BORDER=1 SRC="help.gif"></A></H3>
        <TABLE BORDER="1" CELLSPACING="0" CELLPADDING="5" WIDTH="99%">
          <TR>
            <TH NOWRAP>CID</TH>
            <TH NOWRAP>Name</TH>
            <TH NOWRAP>Type</TH>
            <? if ($yearselect == "All Years (Average)") { ?><TH NOWRAP>Avg. Years</TH><? }; ?>
            <TH NOWRAP>Yearly Counter Hits</TH>
          </TR>
      <?

      foreach($countersselected as $counterid) {

        //total hits for percent calculations
        if ($yearselect == "All Years (Average)") {
          //get number of years counter is in operation for average
          $sqlyear = "SELECT (TO_DAYS(now()) - TO_DAYS(datetimestart))/365 + 1 FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";
          $sqlresultyear = @mysql_query($sqlyear);
          $yearcount = floor(mysql_result($sqlresultyear,"0","(TO_DAYS(now()) - TO_DAYS(datetimestart))/365 + 1"));
          $sqlccounting = "SELECT (count(*)/" . $yearcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "'";
        } else {
          $sqlccounting = "SELECT count(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND year(time)='" . $yearselect . "'";
        };
        $sqlresultccounting = @mysql_query($sqlccounting);

        if ($yearselect == "All Years (Average)") {
          $totalcounterhits = mysql_result($sqlresultccounting,"0","(count(*)/" . $yearcount . ")");
        } else {
          $totalcounterhits = mysql_result($sqlresultccounting,"0","count(*)");
        };


        $sqlcdescription = "SELECT name,type FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";

        if ($yearselect == "All Years (Average)") {
          $sqlccounting = "SELECT monthname(time),(count(*)/" . $yearcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' GROUP BY month(time) ORDER BY month(time)";
        } else {
          $sqlccounting = "SELECT monthname(time),count(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND year(time)='" . $yearselect . "' GROUP BY month(time) ORDER BY month(time)";
        };

        $sqlresultcdescription = @mysql_query($sqlcdescription);
        $sqlresultccounting = @mysql_query($sqlccounting);

        $cname = mysql_result($sqlresultcdescription,"0","name");
        $ctype = mysql_result($sqlresultcdescription,"0","type");

        $nummonths = mysql_num_rows($sqlresultccounting);

        ?>
          <TR>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $counterid; ?>
            </TD>
            <TD WIDTH="90%">
              <? echo $cname; ?>
            </TD>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $ctype; ?>
            </TD>
            <? if ($yearselect == "All Years (Average)") { ?>
              <TD NOWRAP ALIGN=CENTER>
                <? echo $yearcount; ?>
              </TD>
            <? }; ?>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $totalcounterhits; ?>
            </TD>
         </TR>
         <TR>
           <TD></TD>
           <TD NOWRAP ALIGN=RIGHT>
             <TABLE BORDER="1" CELLSPACING="0" CELLPADDING="1" WIDTH="90%">

        <?

        for ($monthcounter=0; $monthcounter < $nummonths; $monthcounter++) {

          $cmonth = mysql_result($sqlresultccounting,$monthcounter,"monthname(time)");
          if ($yearselect == "All Years (Average)") {
            $monthcount = mysql_result($sqlresultccounting,$monthcounter,"(count(*)/" . $yearcount . ")" );
          } else {
            $monthcount = mysql_result($sqlresultccounting,$monthcounter,"count(*)");
          };
          $monthpercent = round(($monthcount / $totalcounterhits)*100);

          ?>
               <TR>
                 <TD NOWRAP>
                   <? echo $cmonth; ?>: 
                 </TD>
                 <TD NOWRAP>
                   <IMG SRC="makebar.php?length=200&percent=<? echo $monthpercent; ?>"> - <? echo $monthcount; ?> Hits - <? echo $monthpercent . "%"; ?>
                 </TD>
               </TR>
          <?

          //end for monthcounter
        };

        ?>
              </TABLE>
            </TD>
          </TR>
        <?

        //end foreach
      };

      ?></TABLE><?



//------------------------------------------------------------------------------------------------------
    } elseif ($stattype == "WeeklyHitsOverAYear") {
      $yearselect = $_POST["yearselect"];

      $sql = "SELECT week(now())";
      $result = @mysql_query($sql);
      $weeknumb = mysql_result($result,"0","week(now())");

      ?>
        <H3>Statistics - Weekly Hits Over a Year - Currently Week #<? echo $weeknumb; ?> <A HREF="help.php#Stats_WeeklyHitsOverAYear"><IMG BORDER=1 SRC="help.gif"></A></H3>
        <TABLE BORDER="1" CELLSPACING="0" CELLPADDING="5" WIDTH="90%">
          <TR>
            <TH NOWRAP>CID</TH>
            <TH NOWRAP>Name</TH>
            <TH NOWRAP>Type</TH>
            <? if ($yearselect == "All Years (Average)") { ?><TH NOWRAP>Avg. Years</TH><? }; ?>
            <TH NOWRAP>Yearly Counter Hits</TH>
          </TR>
      <?

      foreach($countersselected as $counterid) {

        //get number of years counter is in operation for average

        //total hits for percent calculations
        if ($yearselect == "All Years (Average)") {
          $sqlyear = "SELECT (TO_DAYS(now()) - TO_DAYS(datetimestart))/365 + 1 FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";
          $sqlresultyear = @mysql_query($sqlyear);
          $yearcount = floor(mysql_result($sqlresultyear,"0","(TO_DAYS(now()) - TO_DAYS(datetimestart))/365 + 1"));
          $sqlccounting = "SELECT (count(*)/" . $yearcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "'";
        } else {
          $sqlccounting = "SELECT count(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND year(time)='" . $yearselect . "'";
        };
        $sqlresultccounting = @mysql_query($sqlccounting);

        if ($yearselect == "All Years (Average)") {
          $totalcounterhits = mysql_result($sqlresultccounting,"0","(count(*)/" . $yearcount . ")");
        } else {
          $totalcounterhits = mysql_result($sqlresultccounting,"0","count(*)");
        };


        $sqlcdescription = "SELECT name,type FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";

        if ($yearselect == "All Years (Average)") {
          $sqlccounting = "SELECT week(time,0),(count(*)/" . $yearcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' GROUP BY week(time,0) ORDER BY week(time,0)";
        } else {
          $sqlccounting = "SELECT week(time,0),count(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND year(time)='" . $yearselect . "' GROUP BY week(time,0) ORDER BY week(time,0)";
        };

        $sqlresultcdescription = @mysql_query($sqlcdescription);
        $sqlresultccounting = @mysql_query($sqlccounting);

        $cname = mysql_result($sqlresultcdescription,"0","name");
        $ctype = mysql_result($sqlresultcdescription,"0","type");

        $numweeks = mysql_num_rows($sqlresultccounting);

        ?>
          <TR>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $counterid; ?>
            </TD>
            <TD WIDTH="90%">
              <? echo $cname; ?>
            </TD>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $ctype; ?>
            </TD>
            <? if ($yearselect == "All Years (Average)") { ?>
              <TD NOWRAP ALIGN=CENTER>
                <? echo $yearcount; ?>
              </TD>
            <? }; ?>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $totalcounterhits; ?>
            </TD>
         </TR>
         <TR>
           <TD></TD>
           <TD NOWRAP ALIGN=RIGHT>
             <TABLE BORDER="1" CELLSPACING="0" CELLPADDING="1" WIDTH="90%">

        <?

        for ($weekcounter=0; $weekcounter < $numweeks; $weekcounter++) {

          $cweek = mysql_result($sqlresultccounting,$weekcounter,"week(time,0)");
          if ($yearselect == "All Years (Average)") {
            $weekcount = mysql_result($sqlresultccounting,$weekcounter,"(count(*)/" . $yearcount . ")" );
          } else {
            $weekcount = mysql_result($sqlresultccounting,$weekcounter,"count(*)");
          };
          $weekpercent = round(($weekcount / $totalcounterhits)*100);

          ?>
               <TR>
                 <TD NOWRAP>
                   <? echo $cweek; ?>: 
                 </TD>
                 <TD NOWRAP>
                   <IMG SRC="makebar.php?length=200&percent=<? echo $weekpercent; ?>"> - <? echo $weekcount; ?> Hits - <? echo $weekpercent . "%"; ?>
                 </TD>
               </TR>
          <?

          //end for weekcounter
        };

        ?>
              </TABLE>
            </TD>
          </TR>
        <?

        //end foreach
      };

      ?></TABLE><?


//------------------------------------------------------------------------------------------------------
    } elseif ($stattype == "DailyHitsOverAMonth") {
      $yearselect = $_POST["yearselect"];
      $monthselect = $_POST["monthselect"];

      ?>
        <H3>Statistics - Daily Hits Over a Month <A HREF="help.php#Stats_DailyHitsOverAMonth"><IMG BORDER=1 SRC="help.gif"></A></H3>

        <TABLE BORDER="1" CELLSPACING="0" CELLPADDING="5" WIDTH="90%">
          <TR>
            <TH NOWRAP>CID</TH>
            <TH NOWRAP>Name</TH>
            <TH NOWRAP>Type</TH>
            <? if ($yearselect == "All Years (Average)" || $monthselect == "All Months (Average)") { ?><TH NOWRAP>Avg. Months</TH><? }; ?>
            <TH NOWRAP>Monthly Counter Hits</TH>
          </TR>
      <?

      foreach($countersselected as $counterid) {

        //get number of months counter is being averaged on if it is being averaged

        if ($monthselect == "All Months (Average)") {
          //if (year) and (month) are being averaged then get total number of months
          $sqlmonth = "SELECT PERIOD_DIFF(DATE_FORMAT(NOW(), '%Y%m'),DATE_FORMAT(datetimestart, '%Y%m')) + 1 FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";
          $sqlresultmonth = @mysql_query($sqlmonth);
          $monthcount = mysql_result($sqlresultmonth,"0","PERIOD_DIFF(DATE_FORMAT(NOW(), '%Y%m'),DATE_FORMAT(datetimestart, '%Y%m')) + 1");

        } elseif ($yearselect == "All Years (Average)") {
          //if just (year) is being averaged then get number of years and assume one month per year
          $sqlmonth = "SELECT MONTH(datetimestart),MONTH(NOW()),PERIOD_DIFF(DATE_FORMAT(NOW(), '%Y%m'),DATE_FORMAT(datetimestart, '%Y%m')) / 12 FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";
          $sqlresultmonth = @mysql_query($sqlmonth);
          $monthcount = floor(mysql_result($sqlresultmonth,"0","PERIOD_DIFF(DATE_FORMAT(NOW(), '%Y%m'),DATE_FORMAT(datetimestart, '%Y%m')) / 12"));
          $monthstart = mysql_result($sqlresultmonth,"0","MONTH(datetimestart)");
          $monthnow = mysql_result($sqlresultmonth,"0","MONTH(NOW())");

          if ($monthselect = 'January') {
            $monthselectnum = '1';
          } elseif ($monthselect = 'February') {
            $monthselectnum = '2';
          } elseif ($monthselect = 'March') {
            $monthselectnum = '3';
          } elseif ($monthselect = 'April') {
            $monthselectnum = '4';
          } elseif ($monthselect = 'May') {
            $monthselectnum = '5';
          } elseif ($monthselect = 'June') {
            $monthselectnum = '6';
          } elseif ($monthselect = 'July') {
            $monthselectnum = '7';
          } elseif ($monthselect = 'August') {
            $monthselectnum = '8';
          } elseif ($monthselect = 'September') {
            $monthselectnum = '9';
          } elseif ($monthselect = 'October') {
            $monthselectnum = '10';
          } elseif ($monthselect = 'November') {
            $monthselectnum = '11';
          } elseif ($monthselect = 'December') {
            $monthselectnum = '12';
          };

          //adjust for month offset as follows
          //if start <= selected <= now then add 1 to monthcount above
          //if selected < start < now then keep monthcount above
          //if start < now < selected then keep monthcount above
          //if now < selected < start then keep monthcount above
          //if selected < now < start then add 1 to monthcount above
          //if now < start < selected then add 1 to monthcount above

          if (($monthstart <= $monthselectnum) && ($monthselectnum <= $monthnow)) {
            $monthcount = $monthcount +1;
          } elseif (($monthselectnum < $monthnow) && ($monthnow <= $monthstart)) {
            $monthcount = $monthcount +1;
          } elseif (($monthnow <= $monthstart) && ($monthstart < $monthselectnum)) {
            $monthcount = $monthcount +1;
          };

        };

        //end getting month count for averages


        //get total hits for percent calculations

        if (($monthselect == "All Months (Average)") && ($yearselect == "All Years (Average)")) {
          //average on both year and month
          $sqlccounting = "SELECT (count(*)/" . $monthcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "'";

        } elseif ($monthselect == "All Months (Average)") {
          //average on month only, year is given
          $sqlccounting = "SELECT (count(*)/" . $monthcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND YEAR(time)='" . $yearselect . "'";

        } elseif ($yearselect == "All Years (Average)") {
          //average on year only, month is given
          $sqlccounting = "SELECT (count(*)/" . $monthcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND MONTHNAME(time)='" . $monthselect . "'";

        } else {
          //no averages, both month and year are given
          $sqlccounting = "SELECT count(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND YEAR(time)='" . $yearselect . "' AND MONTHNAME(time)='" . $monthselect . "'";
        };
        $sqlresultccounting = @mysql_query($sqlccounting);


        //extract total hits for counter

        if (($monthselect == "All Months (Average)") || ($yearselect == "All Years (Average)")) {
          $totalcounterhits = mysql_result($sqlresultccounting,"0","(count(*)/" . $monthcount . ")");

        } else {
          $totalcounterhits = mysql_result($sqlresultccounting,"0","count(*)");
        };


        //get counter description information
        $sqlcdescription = "SELECT name,type FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";


        //get daily hits

        if (($monthselect == "All Months (Average)") && ($yearselect == "All Years (Average)")) {
          $sqlccounting = "SELECT DAYOFMONTH(time),(count(*)/" . $monthcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' GROUP BY DAYOFMONTH(time) ORDER BY DAYOFMONTH(time)";

        } elseif ($monthselect == "All Months (Average)") {
          $sqlccounting = "SELECT DAYOFMONTH(time),(count(*)/" . $monthcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND YEAR(time)='" . $yearselect . "' GROUP BY DAYOFMONTH(time) ORDER BY DAYOFMONTH(time)";

        } elseif ($yearselect == "All Years (Average)") {
          $sqlccounting = "SELECT DAYOFMONTH(time),(count(*)/" . $monthcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND MONTHNAME(time)='" . $monthselect . "' GROUP BY DAYOFMONTH(time) ORDER BY DAYOFMONTH(time)";

        } else {
          $sqlccounting = "SELECT DAYOFMONTH(time),count(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND YEAR(time)='" . $yearselect . "' AND MONTHNAME(time)='" . $monthselect . "' GROUP BY DAYOFMONTH(time) ORDER BY DAYOFMONTH(time)";
        };

        $sqlresultcdescription = @mysql_query($sqlcdescription);
        $sqlresultccounting = @mysql_query($sqlccounting);

        $cname = mysql_result($sqlresultcdescription,"0","name");
        $ctype = mysql_result($sqlresultcdescription,"0","type");

        $numdays = mysql_num_rows($sqlresultccounting);



        ?>
          <TR>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $counterid; ?>
            </TD>
            <TD WIDTH="90%">
              <? echo $cname; ?>
            </TD>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $ctype; ?>
            </TD>
            <? if (($yearselect == "All Years (Average)") || ($monthselect == "All Months (Average)")) { ?>
              <TD NOWRAP ALIGN=CENTER>
                <? echo $monthcount; ?>
              </TD>
            <? }; ?>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $totalcounterhits; ?>
            </TD>
         </TR>
         <TR>
           <TD></TD>
           <TD NOWRAP ALIGN=RIGHT>
             <TABLE BORDER="1" CELLSPACING="0" CELLPADDING="1" WIDTH="90%">

        <?

        for ($daycounter=0; $daycounter < $numdays; $daycounter++) {

          $cday = mysql_result($sqlresultccounting,$daycounter,"DAYOFMONTH(time)");

          if (($monthselect == "All Months (Average)") || ($yearselect == "All Years (Average)")) {
            $daycount = mysql_result($sqlresultccounting,$daycounter,"(count(*)/" . $monthcount . ")" );
          } else {
            $daycount = mysql_result($sqlresultccounting,$daycounter,"count(*)");
          };

          $daypercent = round(($daycount / $totalcounterhits)*100);

          ?>
               <TR>
                 <TD NOWRAP>
                   <? echo $cday; ?>: 
                 </TD>
                 <TD NOWRAP>
                   <IMG SRC="makebar.php?length=200&percent=<? echo $daypercent; ?>"> - <? echo $daycount; ?> Hits - <? echo $daypercent . "%"; ?>
                 </TD>
               </TR>
          <?

          //end for daycounter
        };

        ?>
              </TABLE>
            </TD>
          </TR>
        <?

        //end foreach
      };

      ?></TABLE><?




//------------------------------------------------------------------------------------------------------
    } elseif ($stattype == "DailyHitsOverAWeek") {
      $yearselect = $_POST["yearselect"];
      $weekselect = $_POST["weekselect"];

      ?>
        <H3>Statistics - Daily Hits Over a Week <A HREF="help.php#Stats_DailyHitsOverAWeek"><IMG BORDER=1 SRC="help.gif"></A></H3>

        <TABLE BORDER="1" CELLSPACING="0" CELLPADDING="5" WIDTH="90%">
          <TR>
            <TH NOWRAP>CID</TH>
            <TH NOWRAP>Name</TH>
            <TH NOWRAP>Type</TH>
            <? if ($yearselect == "All Years (Average)" || $weekselect == "All Weeks (Average)") { ?><TH NOWRAP>Avg. Weeks</TH><? }; ?>
            <TH NOWRAP>Weekly Counter Hits</TH>
          </TR>
      <?

      foreach($countersselected as $counterid) {

        //get number of weeks counter is being averaged on if it is being averaged

        if (($weekselect == "All Weeks (Average)") && ($yearselect == "All Years (Average)")) {
          //if (year) and (week) are being averaged then get total number of weeks
          $sqlweek = "SELECT (TO_DAYS(NOW()) - TO_DAYS(datetimestart)) / 7 FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";
          $sqlresultweek = @mysql_query($sqlweek);
          $weekcount = floor(mysql_result($sqlresultweek,"0","(TO_DAYS(NOW()) - TO_DAYS(datetimestart)) / 7")) + 1;


        } elseif ($weekselect == "All Weeks (Average)") {
          //if just (week) is being averaged then get total number of weeks 

          $sqlyear = "SELECT YEAR(NOW()),YEAR(datetimestart) FROM " . $tableprefix . "_counterdescription WHERE counterid='" . $counterid . "'";
          $sqlresultyear = @mysql_query($sqlyear);
          $thisyear = mysql_result($sqlresultyear,"0","YEAR(NOW())");
          $yearstarted = mysql_result($sqlresultyear,"0","YEAR(datetimestart)");
          
          if($thisyear == $yearstarted) {
            $sqlweek = "SELECT (TO_DAYS(NOW()) - TO_DAYS(datetimestart)) / 7 FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";
            $sqlresultweek = @mysql_query($sqlweek);
            $weekcount = floor(mysql_result($sqlresultweek,"0","(TO_DAYS(NOW()) - TO_DAYS(datetimestart)) / 7")) + 1;

          } elseif ($thisyear == $yearselect) {
            $sqlweek = "SELECT (TO_DAYS(NOW()) - TO_DAYS(CONCAT(YEAR(NOW()),'-01-01'))) / 7 FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";
            $sqlresultweek = @mysql_query($sqlweek);
            $weekcount = floor(mysql_result($sqlresultweek,"0","(TO_DAYS(NOW()) - TO_DAYS(CONCAT(YEAR(NOW()),'-01-01'))) / 7")) + 1;

          } elseif ($yearstarted == $yearselected) {
            $sqlweek = "SELECT (TO_DAYS(CONCAT(YEAR(datetimestarted),'-12-31')) - TO_DAYS(datetimestarted)) / 7 FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";
            $sqlresultweek = @mysql_query($sqlweek);
            $weekcount = floor(mysql_result($sqlresultweek,"0","(TO_DAYS(CONCAT(YEAR(datetimestarted),'-12-31')) - TO_DAYS(datetimestarted)) / 7")) + 1;

          } else {
            $weekcount = 52;

          };


        } elseif ($yearselect == "All Years (Average)") {
          //if just (year) is being averaged then get number of years with one week per year and offset for start time and end time
          $sqlweek = "SELECT WEEK(datetimestart,0),WEEK(NOW(),0),(TO_DAYS(NOW()) - TO_DAYS(datetimestart)) / 365 FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";
          $sqlresultweek = @mysql_query($sqlweek);
          $weekcount = floor(mysql_result($sqlresultweek,"0","(TO_DAYS(NOW()) - TO_DAYS(datetimestart)) / 365"));
          $weekstart = mysql_result($sqlresultweek,"0","WEEK(datetimestart,0)");
          $weeknow = mysql_result($sqlresultweek,"0","WEEK(NOW(),0)");

          //adjust for week offset as follows
          //if start <= selected <= now then add 1 to weekcount above
          //if selected < start < now then keep weekcount above
          //if start < now < selected then keep weekcount above
          //if now < selected < start then keep weekcount above
          //if selected < now < start then add 1 to weekcount above
          //if now < start < selected then add 1 to weekcount above

          if (($weekstart <= $weekselect) && ($weekselect <= $weeknow)) {
            $weekcount = $weekcount +1;
          } elseif (($weekselect < $weeknow) && ($weeknow <= $weekstart)) {
            $weekcount = $weekcount +1;
          } elseif (($weeknow <= $weekstart) && ($weekstart < $weekselect)) {
            $weekcount = $weekcount +1;
          };


        };

        //end getting week count for averages


        //get total hits for percent calculations

        if (($weekselect == "All Weeks (Average)") && ($yearselect == "All Years (Average)")) {
          //average on both year and week
          $sqlccounting = "SELECT (count(*)/" . $weekcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "'";

        } elseif ($weekselect == "All Weeks (Average)") {
          //average on week only, year is given
          $sqlccounting = "SELECT (count(*)/" . $weekcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND YEAR(time)='" . $yearselect . "'";

        } elseif ($yearselect == "All Years (Average)") {
          //average on year only, week is given
          $sqlccounting = "SELECT (count(*)/" . $weekcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND WEEK(time,0)='" . $weekselect . "'";

        } else {
          //no averages, both week and year are given
          $sqlccounting = "SELECT count(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND YEAR(time)='" . $yearselect . "' AND WEEK(time,0)='" . $weekselect . "'";
        };
        $sqlresultccounting = @mysql_query($sqlccounting);


        //extract total hits for counter

        if (($weekselect == "All Weeks (Average)") || ($yearselect == "All Years (Average)")) {
          $totalcounterhits = mysql_result($sqlresultccounting,"0","(count(*)/" . $weekcount . ")");

        } else {
          $totalcounterhits = mysql_result($sqlresultccounting,"0","count(*)");
        };


        //get counter description information
        $sqlcdescription = "SELECT name,type FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";


        //get daily hits

        if (($weekselect == "All Weeks (Average)") && ($yearselect == "All Years (Average)")) {
          $sqlccounting = "SELECT DAYOFWEEK(time),(count(*)/" . $weekcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' GROUP BY DAYOFWEEK(time) ORDER BY DAYOFWEEK(time)";

        } elseif ($weekselect == "All Weeks (Average)") {
          $sqlccounting = "SELECT DAYOFWEEK(time),(count(*)/" . $weekcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND YEAR(time)='" . $yearselect . "' GROUP BY DAYOFWEEK(time) ORDER BY DAYOFWEEK(time)";

        } elseif ($yearselect == "All Years (Average)") {
          $sqlccounting = "SELECT DAYOFWEEK(time),(count(*)/" . $weekcount . ") FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND WEEK(time,0)='" . $weekselect . "' GROUP BY DAYOFWEEK(time) ORDER BY DAYOFWEEK(time)";

        } else {
          $sqlccounting = "SELECT DAYOFWEEK(time),count(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND YEAR(time)='" . $yearselect . "' AND WEEK(time,0)='" . $weekselect . "' GROUP BY DAYOFWEEK(time) ORDER BY DAYOFWEEK(time)";
        };

        $sqlresultcdescription = @mysql_query($sqlcdescription);
        $sqlresultccounting = @mysql_query($sqlccounting);

        $cname = mysql_result($sqlresultcdescription,"0","name");
        $ctype = mysql_result($sqlresultcdescription,"0","type");

        //most of the time the following number will be 7, but just to be sure
        $numdays = mysql_num_rows($sqlresultccounting);



        ?>
          <TR>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $counterid; ?>
            </TD>
            <TD WIDTH="90%">
              <? echo $cname; ?>
            </TD>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $ctype; ?>
            </TD>
            <? if (($yearselect == "All Years (Average)") || ($weekselect == "All Weeks (Average)")) { ?>
              <TD NOWRAP ALIGN=CENTER>
                <? echo $weekcount; ?>
              </TD>
            <? }; ?>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $totalcounterhits; ?>
            </TD>
         </TR>
         <TR>
           <TD></TD>
           <TD NOWRAP ALIGN=RIGHT>
             <TABLE BORDER="1" CELLSPACING="0" CELLPADDING="1" WIDTH="90%">

        <?

        for ($daycounter=0; $daycounter < $numdays; $daycounter++) {

          //get day of the week and find the name
          $cdaynum = mysql_result($sqlresultccounting,$daycounter,"DAYOFWEEK(time)");

          if ($cdaynum == "1") { $cday = "Sunday"; };
          if ($cdaynum == "2") { $cday = "Monday"; };
          if ($cdaynum == "3") { $cday = "Tuesday"; };
          if ($cdaynum == "4") { $cday = "Wednesday"; };
          if ($cdaynum == "5") { $cday = "Thursday"; };
          if ($cdaynum == "6") { $cday = "Friday"; };
          if ($cdaynum == "7") { $cday = "Saturday"; };

          if (($weekselect == "All Weeks (Average)") || ($yearselect == "All Years (Average)")) {
            $daycount = mysql_result($sqlresultccounting,$daycounter,"(count(*)/" . $weekcount . ")" );
          } else {
            $daycount = mysql_result($sqlresultccounting,$daycounter,"count(*)");
          };

          $daypercent = round(($daycount / $totalcounterhits)*100);

          ?>
               <TR>
                 <TD NOWRAP>
                   <? echo $cday; ?>: 
                 </TD>
                 <TD NOWRAP>
                   <IMG SRC="makebar.php?length=200&percent=<? echo $daypercent; ?>"> - <? echo $daycount; ?> Hits - <? echo $daypercent . "%"; ?>
                 </TD>
               </TR>
          <?

          //end for daycounter
        };

        ?>
              </TABLE>
            </TD>
          </TR>
        <?

        //end foreach
      };

      ?></TABLE><?






//------------------------------------------------------------------------------------------------------
    } elseif ($stattype == "StatTableDump") {
      $yearselect = $_POST["yearselect"];

      ?>
        <H3>Statistics - Statistical Daily Counter Table <A HREF="help.php#Stats_CounterTable"><IMG BORDER=1 SRC="help.gif"></A></H3>
        <TABLE BORDER="1" CELLSPACING="0" CELLPADDING="5" WIDTH="99%">
          <TR>
            <TH NOWRAP>CID</TH>
            <TH NOWRAP>Type</TH>
            <TH NOWRAP>Name</TH>
          </TR>
      <?


      foreach($countersselected as $counterid) {

        $sqlcdescription = "SELECT name,type FROM " . $tableprefix . "counterdescription WHERE counterid='" . $counterid . "'";
        $sqlresultcdescription = @mysql_query($sqlcdescription);

        $cname = mysql_result($sqlresultcdescription,"0","name");
        $ctype = mysql_result($sqlresultcdescription,"0","type");

        ?>
          <TR>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $counterid; ?>
            </TD>
            <TD NOWRAP ALIGN=CENTER>
              <? echo $ctype; ?>
            </TD>
            <TD WIDTH="90%">
              <? echo $cname; ?>
            </TD>
         </TR>
         <TR>
           <TD></TD>
           <TD></TD>
           <TD NOWRAP ALIGN=LEFT>
             <TABLE BORDER="1" CELLSPACING="0" CELLPADDING="1" WIDTH="90%">
               <TR>
                 <TH>
                   Month:\Day:
                 </TH>
                 <?
                   for($hday = 1; $hday <= 31; $hday++) {
                     ?><TH NOWRAP><? echo "&nbsp;" . $hday . "&nbsp;"; ?></TH><?
                   };
                 ?>
               </TR>
        <?

          for($monthcounter = 1; $monthcounter <= 12; $monthcounter++) {
            //build one line of the table (one month) on each run of the for loop

            $sqlccounting = "SELECT dayofmonth(time),count(*) FROM " . $tableprefix . "counting WHERE counterid='" . $counterid . "' AND MONTH(time)='" . $monthcounter . "' AND year(time)='" . $yearselect . "' GROUP BY dayofmonth(time) ORDER BY dayofmonth(time)";
            $sqlresultccounting = @mysql_query($sqlccounting);

            ?><TR><?

            if ($monthcounter == 1) { echo "<TD>January</TD>"; };
            if ($monthcounter == 2) { echo "<TD>February</TD>"; };
            if ($monthcounter == 3) { echo "<TD>March</TD>"; };
            if ($monthcounter == 4) { echo "<TD>April</TD>"; };
            if ($monthcounter == 5) { echo "<TD>May</TD>"; };
            if ($monthcounter == 6) { echo "<TD>June</TD>"; };
            if ($monthcounter == 7) { echo "<TD>July</TD>"; };
            if ($monthcounter == 8) { echo "<TD>August</TD>"; };
            if ($monthcounter == 9) { echo "<TD>September</TD>"; };
            if ($monthcounter == 10) { echo "<TD>October</TD>"; };
            if ($monthcounter == 11) { echo "<TD>November</TD>"; };
            if ($monthcounter == 12) { echo "<TD>December</TD>"; };

            $linetotal = mysql_num_rows($sqlresultccounting);
            for($daycounter = 1; $daycounter <= 31; $daycounter++) {
              //build one square of the table (one day) on each run of the for loop

              for($linecounter = 0; $linecounter < $linetotal; $linecounter++) {
                //find the day in the mysql results that match the day we are on
                if ($daycounter == mysql_result($sqlresultccounting,$linecounter,"dayofmonth(time)")) {
                  $daycount = mysql_result($sqlresultccounting,$linecounter,"count(*)");
                };
              };

              if (isset($daycount)) {
                echo "<TD ALIGN=RIGHT>" . $daycount . "</TD>";
              } else {
                echo "<TD>&nbsp;</TD>";
              };
              unset ($daycount);

              //end for daycounter
            };

            ?></TR><?

            //end for monthcounter
          };

        ?>
              </TABLE>
            </TD>
          </TR>
        <?

        //end foreach
      };

      ?></TABLE><?




//------------------------------------------------------------------------------------------------------
    };


    @mysql_close($datastream);

  } else {
    //no counters selected
    ?>
      <H3>Statistics - Error</H3>
      No counters Selected.
    <?
  };

  include("footer.php");

};
?>