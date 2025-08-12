<?php

/***************************************************************************

 m_viewstatistics.php
 ---------------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

$GLOBALS["ScreenWidthMultiplier"] = (float) 3.75;


include_once ("rootdatapath.php");

$GLOBALS["form"] = 'viewstatistics';
$GLOBALS["validaccess"] = VerifyAdminLogin();

includeLanguageFiles('admin','statistics');


if ($_GET["page"] != "") {
	$_POST["page"] = $_GET["page"];
}

force_page_refresh();
frmStats($page);


function frmStats()
{
	global $_GET;

	adminheader();
	admintitle(2,$GLOBALS["tFormTitle"]);
	StatsOptions();
	?>
	</table>
	<table border="0" width="100%" cellspacing="3" cellpadding="3">
		<?php
		switch ($_GET["page"]) {
			case 1	: frmStatsWhen();
					  break;
			case 2	: frmStatsWho();
					  break;
			case 3	: frmStatsWhere();
					  break;
			case 4	: frmStatsHow();
					  break;
			default	: frmStatsSummary();
					  break;
		}
		?>
	</table>
	</body>
	</html>
	<?php
} // function frmStats()


function StatsOptions()
{
	$statspage = BuildLink('m_viewstatistics.php');
	?>

	<tr class="topmenuback">
		<td align="center" valign="bottom" class="content">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="20%" align="center" valign="bottom"><b><a class="topmenulink" href="<?php echo $statspage; ?>">> <?php echo $GLOBALS["tFormTitle0"]; ?></a></b></td>
					<td width="20%" align="center" valign="bottom"><b><a class="topmenulink" href="<?php echo $statspage; ?>&page=1">> <?php echo $GLOBALS["tFormTitle1"]; ?></a></b></td>
					<td width="20%" align="center" valign="bottom"><b><a class="topmenulink" href="<?php echo $statspage; ?>&page=2">> <?php echo $GLOBALS["tFormTitle2"]; ?></a></b></td>
					<td width="20%" align="center" valign="bottom"><b><a class="topmenulink" href="<?php echo $statspage; ?>&page=3">> <?php echo $GLOBALS["tFormTitle3"]; ?></a></b></td>
					<td width="20%" align="center" valign="bottom"><b><a class="topmenulink" href="<?php echo $statspage; ?>&page=4">> <?php echo $GLOBALS["tFormTitle4"]; ?></a></b></td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
} // function StatsOptions()


function frmStatsSummary()
{
	statstitle(2,$GLOBALS["tFormTitle0"]);

	$startdate  = StartDate();
	$todaycount = TodayCount();
	$totalcount = TotalCount();
	$dayaverage = DayAverage($startdate,$totalcount);
	$topvisitors = TopCount();
	$old_locale = setlocale(LC_ALL, 0);
	setlocale (LC_TIME,$GLOBALS["locale"]);
	$DateFormat = '%d-%b-%Y %H:%M';
	$displaydate = strftime($DateFormat,strtotime($startdate));
	$DateFormat = '%d-%b-%Y';
	$topvisitors["displaydate"] = strftime($DateFormat,strtotime($topvisitors["topdate"]));
	setlocale(LC_ALL, $old_locale);
	?>
	<tr class="teasercontent">
		<td valign="top" class="content"><?php echo $GLOBALS["stStartDate"]; ?>:</td>
		<td valign="top" class="content"><?php echo $displaydate; ?></td>
	</tr>
	<tr class="teasercontent">
		<td valign="top" class="content"><?php echo $GLOBALS["stTodayCount"]; ?>:</td>
		<td valign="top" class="content"><?php echo $todaycount; ?> <?php echo $GLOBALS["tVisitors"]; ?></td>
	</tr>
	<tr class="teasercontent">
		<td valign="top" class="content"><?php echo $GLOBALS["stTotalCount"]; ?>:</td>
		<td valign="top" class="content"><?php echo $totalcount; ?></td>
	</tr>
	<tr class="teasercontent">
		<td valign="top" class="content"><?php echo $GLOBALS["stAverageCount"]; ?>:</td>
		<td valign="top" class="content"><?php echo $dayaverage; ?></td>
	</tr>
	<tr class="teasercontent">
		<td valign="top" class="content"><?php echo $GLOBALS["stTopCount"]; ?>:</td>
		<td valign="top" class="content"><?php echo $topvisitors["displaydate"]; ?> <?php echo $GLOBALS["tWith"]; ?> <?php echo $topvisitors["tophits"]; ?> <?php echo $GLOBALS["tVisitors"]; ?></td>
	</tr>
	<?php
} // function frmStatsSummary()


function StartDate()
{
	global $EZ_SESSION_VARS;

	if ($EZ_SESSION_VARS["Site"] != '') {
		$sqlQuery  = "SELECT visitdate FROM ".$GLOBALS["eztbVisitorstats"]." WHERE site='".$EZ_SESSION_VARS["Site"]."' ORDER BY visitdate";
	} else {
		$sqlQuery  = "SELECT visitdate FROM ".$GLOBALS["eztbVisitorstats"]." ORDER BY visitdate";
	}
	$result    = dbRetrieve($sqlQuery,true,0,1);
	$rs        = dbFetch($result);
	$startdate = $rs["visitdate"];
	dbFreeResult($result);
	return $startdate;
} // function StartDate()


function TodayCount()
{
	global $EZ_SESSION_VARS;

	if ($EZ_SESSION_VARS["Site"] != '') {
		$sqlQuery   = "SELECT sum(countnumber) AS visitorcount FROM ".$GLOBALS["eztbVisitorstats"]." WHERE site='".$EZ_SESSION_VARS["Site"]."' AND visitdate LIKE '".date("Y-m-d")."%'";
	} else {
		$sqlQuery   = "SELECT sum(countnumber) AS visitorcount FROM ".$GLOBALS["eztbVisitorstats"]." WHERE visitdate LIKE '".date("Y-m-d")."%'";
	}
	$result     = dbRetrieve($sqlQuery,true,0,0);
	$rs         = dbFetch($result);
	$todaycount = $rs["visitorcount"];
	if ($todaycount == '') $todaycount = 0;
	dbFreeResult($result);
	return $todaycount;
} // function TodayCount()


function TotalCount()
{
	global $EZ_SESSION_VARS;

	if ($EZ_SESSION_VARS["Site"] != '') {
		$sqlQuery   = "SELECT sum(countnumber) AS visitorcount FROM ".$GLOBALS["eztbVisitorstats"]." WHERE site='".$EZ_SESSION_VARS["Site"]."'";
	} else {
		$sqlQuery   = "SELECT sum(countnumber) AS visitorcount FROM ".$GLOBALS["eztbVisitorstats"];
	}
	$result     = dbRetrieve($sqlQuery,true,0,0);
	$rs         = dbFetch($result);
	$totalcount = $rs["visitorcount"];
	dbFreeResult($result);
	return $totalcount;
} // function TotalCount()


function PeriodStart($ptype)
{
	$pstart = '';
	switch ($ptype) {
		case 'day'		: $pstart = sprintf("%04d-%02d-%02d %02d:%02d:%02d",strftime("%Y"),strftime("%m"),strftime("%d"),0,0,0);
						  break;
		case 'week'		: $offset = date("w",mktime(0,0,0,strftime("%m"),strftime("%d"),strftime("%Y")));
						  if (($offset == 7) || ($offset == 0)) {
								$pstart = sprintf("%04d-%02d-%02d %02d:%02d:%02d",strftime("%Y"),strftime("%m"),strftime("%d"),0,0,0);
						  } else {
								$pstart = strftime("%Y-%m-%d",DateSub('d',$offset,time()))." 00:00:00";
						  }
						  break;
		case 'month'	: $pstart = sprintf("%04d-%02d-%02d %02d:%02d:%02d",strftime("%Y"),strftime("%m"),1,0,0,0);
						  break;
		case 'year'		: $pstart = sprintf("%04d-%02d-%02d %02d:%02d:%02d",strftime("%Y"),1,1,0,0,0);
						  break;
	}
	return $pstart;
} // function PeriodStart()


function PeriodCount($pstart)
{
	global $EZ_SESSION_VARS;

	if ($EZ_SESSION_VARS["Site"] != '') {
		$sqlQuery   = "SELECT sum(countnumber) AS visitorcount FROM ".$GLOBALS["eztbVisitorstats"]." WHERE site='".$EZ_SESSION_VARS["Site"]."' AND visitdate>='".$pstart."'";
	} else {
		$sqlQuery   = "SELECT sum(countnumber) AS visitorcount FROM ".$GLOBALS["eztbVisitorstats"]." WHERE visitdate>='".$pstart."'";
	}
	$result	= dbRetrieve($sqlQuery,true,0,0);
	$rs		= dbFetch($result);
	$totalcount	= $rs["visitorcount"];
	dbFreeResult($result);
	return $totalcount;
} // function PeriodCount()


function DayAverage($startdate,$totalcount)
{
	$today = time() / 86400;
	$base = strtotime($startdate) / 86400;
	$daydiff = floor($today - $base) + 1;
	$dayaverage = $totalcount / $daydiff;
	if ($dayaverage < 1) { $dayaverage = round($dayaverage,2);
	} elseif ($dayaverage < 10) { $dayaverage = round($dayaverage,1);
	} else { $dayaverage = round($dayaverage,0); }
	return $dayaverage;
} // function DayAverage()


function TopCount()
{
	global $EZ_SESSION_VARS;

	$topvisitors["topdate"] = date("Y-m-d");
	$topvisitors["tophits"] = 0;
	if ($EZ_SESSION_VARS["Site"] != '') {
		$sqlQuery = "SELECT DATE_FORMAT(visitdate,'%Y-%m-%d') AS date, sum(countnumber) AS hits FROM ".$GLOBALS["eztbVisitorstats"]." WHERE site='".$EZ_SESSION_VARS["Site"]."' GROUP BY DATE_FORMAT(visitdate,'%Y-%m-%d')";
	} else {
		$sqlQuery = "SELECT DATE_FORMAT(visitdate,'%Y-%m-%d') AS date, sum(countnumber) AS hits FROM ".$GLOBALS["eztbVisitorstats"]." GROUP BY DATE_FORMAT(visitdate,'%Y-%m-%d')";
	}
	$result	= dbRetrieve($sqlQuery,true,0,0);
	while ($rs = dbFetch($result)) {
		if ($rs["hits"] >= $topvisitors["tophits"]) {
			$topvisitors["topdate"] = $rs["date"];
			$topvisitors["tophits"] = $rs["hits"];
		}
	}
	dbFreeResult($result);
	return $topvisitors;
}


function frmStatsWhen()
{
   global $_SERVER, $_GET, $EZ_SESSION_VARS;

   if (!isset($_GET["dtype"])) { $dtype = 'date';
   } else { $dtype = $_GET["dtype"]; }

   $groups["hour"]    = "HOUR(visitdate)";
   $groups["weekday"] = "DATE_FORMAT(visitdate, '%W')";
   $groups["date"]    = "DATE_FORMAT(visitdate, '%D %M')";
   $groups["week"]    = "DATE_FORMAT(visitdate, '%U %Y')";
   $groups["month"]   = "DATE_FORMAT(visitdate, '%M %Y')";
   $groups["year"]    = "YEAR(visitdate)";
   $order["hour"]     = "HOUR(visitdate)";
   $order["weekday"]  = "WEEKDAY(visitdate)";
   $order["date"]     = "DATE_FORMAT(visitdate, '%Y %m %d') DESC";
   $order["week"]     = "YEAR(visitdate) DESC,WEEK(visitdate) DESC";
   $order["month"]    = "YEAR(visitdate) DESC,MONTH(visitdate) DESC";
   $order["year"]     = "YEAR(visitdate) DESC";
   $limits["hour"]    = 24;
   $limits["weekday"] = 7;
   $limits["date"]    = 28;
   $limits["week"]    = 26;
   $limits["month"]   = 12;
   $limits["year"]    = 10;

   statstitle(4,$GLOBALS["tFormTitle1"]);
   ?>
   <form action="<?php echo $GLOBALS["REQUEST_URI"]; ?>" method="GET" enctype="multipart/form-data">
   <tr class="topmenuback">
      <td colspan="4" align="<?php echo $GLOBALS["left"]; ?>" nowrap>
            <select name="dtype" size="1" onChange="submit();">
                <option value="hour"<?php if ($dtype == 'hour') echo ' selected'; ?>><?php echo $GLOBALS["otHours"]; ?>
                <option value="weekday"<?php if ($dtype == 'weekday') echo ' selected'; ?>><?php echo $GLOBALS["otWeekdays"]; ?>
                <option value="date"<?php if ($dtype == 'date') echo ' selected'; ?>><?php echo $GLOBALS["otDates"]; ?>
                <option value="week"<?php if ($dtype == 'week') echo ' selected'; ?>><?php echo $GLOBALS["otWeeks"]; ?>
                <option value="month"<?php if ($dtype == 'month') echo ' selected'; ?>><?php echo $GLOBALS["otMonths"]; ?>
                <option value="year"<?php if ($dtype == 'year') echo ' selected'; ?>><?php echo $GLOBALS["otYears"]; ?>
            </select>&nbsp;
            <input type="image" name="submit" src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>go.gif" alt="Go" value="Go">
            <input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>">
            <input type="hidden" name="page" value="<?php echo $_GET["page"]; ?>">
      </td>
   </tr>
   </form>

   <tr class="teaserheadercontent">
       <?php
       adminlistitem(20,$GLOBALS["ot".ucfirst($dtype)],'');
       adminlistitem(10,$GLOBALS["tHits"],'c');
       adminlistitem(10,$GLOBALS["tPercentage"],'c');
       adminlistitem(60,'&nbsp;','');
       ?>
   </tr>
   <?php
   $hitstotal = TotalCount();

   $i = 0;

   if ($EZ_SESSION_VARS["Site"] != '') {
      $sqlQuery = "SELECT ".$groups[$dtype]." as name, sum(countnumber) as hits FROM ".$GLOBALS["eztbVisitorstats"]." WHERE site='".$EZ_SESSION_VARS["Site"]."' GROUP BY ".$groups[$dtype]." ORDER BY ".$order[$dtype];
   } else {
      $sqlQuery = "SELECT ".$groups[$dtype]." as name, sum(countnumber) as hits FROM ".$GLOBALS["eztbVisitorstats"]." GROUP BY ".$groups[$dtype]." ORDER BY ".$order[$dtype];
   }
   $result = dbRetrieve($sqlQuery,true,0,$limits[$dtype]);
   while($r = dbFetch($result)) {
      ?>
      <tr class="teasercontent">
          <td valign="top" class="content"><?php echo $r["name"]; ?></td>
          <td valign="top" align="center" class="content"><?php echo $r["hits"] ?></td>
          <td valign="top" align="center" class="content"><?php echo PercentValue($r["hits"],$hitstotal); ?></td>
          <td valign="top" class="content"><?php echo GraphValue($r["hits"],$hitstotal,$i); ?></td>
      </tr>
      <?php
      $i++;
   }
   dbFreeResult($result);
} // function frmStatsWhen()


function frmStatsWho()
{
   global $EZ_SESSION_VARS;

   statstitle(5,$GLOBALS["tFormTitle2"]);
   ?>
   <tr class="teaserheadercontent">
       <?php
       adminlistitem(20,$GLOBALS["tDateTime"],'');
       adminlistitem(30,$GLOBALS["tReferer"],'');
       adminlistitem(15,$GLOBALS["stBrowser"],'');
       adminlistitem(15,$GLOBALS["stOS"],'');
       adminlistitem(20,$GLOBALS["tCountry"],'');
       ?>
   </tr>
   <?php

   $old_locale = setlocale(LC_ALL, 0);
   setlocale (LC_TIME,$GLOBALS["locale"]);
   $DateFormat = '%d-%b-%Y %H:%M';

   if ($EZ_SESSION_VARS["Site"] != '') {
      $sqlQuery = "SELECT s.visitdate AS date, s.visitorreferrer AS referrer, s.visitorip AS ip, s.visitorbrowser AS browser, s.visitoros AS os, s.country AS domain, c.countryname AS country, c.flag AS flag FROM ".$GLOBALS["eztbVisitorstats"]." s LEFT JOIN ".$GLOBALS["eztbCountries"]." c ON c.countrycode=s.country WHERE s.site='".$EZ_SESSION_VARS["Site"]."' ORDER BY s.visitdate DESC";
   } else {
      $sqlQuery = "SELECT s.visitdate AS date, s.visitorreferrer AS referrer, s.visitorip AS ip, s.visitorbrowser AS browser, s.visitoros AS os, s.country AS domain, c.countryname AS country, c.flag AS flag FROM ".$GLOBALS["eztbVisitorstats"]." s LEFT JOIN ".$GLOBALS["eztbCountries"]." c ON c.countrycode=s.country ORDER BY s.visitdate DESC";
   }
   $result = dbRetrieve($sqlQuery,true,0,20);
   while($r = dbFetch($result)) {
      $displaydate = strftime($DateFormat,strtotime($r[date]));
      $hostdisplay = '';
      if (($r["referrer"] != '') && (substr($r["referrer"],0,8) != 'http:///')) {
         $url_parts = parse_url($r["referrer"]);
         $host = $url_parts['scheme'].'://'.$url_parts['host'].'/';
         $hostdisplay = '<a href="'.$host.'" target="_blank">'.$host.'</a>';
      } else {
         $hostdisplay = $r["ip"];
      }
      ?>
      <tr class="teasercontent">
          <td valign="top" class="content"><?php echo $displaydate; ?></td>
          <td valign="top" class="content"><?php echo $hostdisplay; ?></td>
          <td valign="top" class="content"><?php echo imagehtmltag($GLOBALS["icon_home"],'stats/'.$r[browser].'.gif',$r[browser],'',0); ?>&nbsp;&nbsp;<?php echo $r[browser]; ?></td>
          <td valign="top" class="content"><?php echo imagehtmltag($GLOBALS["icon_home"],'stats/'.$r[os].'.gif',$r[os],'',0); ?>&nbsp;&nbsp;<?php echo $r[os]; ?></td>
          <td valign="top" class="content"><?php echo DisplayCountry($r["country"],$r["domain"],$r["country"]); ?></td>
      </tr>
      <?php
   }
   dbFreeResult($result);
   setlocale(LC_ALL, $old_locale);
} // function frmStatsWho()


function frmStatsWhere()
{
   global $_SERVER, $_GET, $EZ_SESSION_VARS;

   if (!isset($_GET["ptype"])) { $ptype = 'all';
   } else { $ptype = $_GET["ptype"]; }

   statstitle(4,$GLOBALS["tFormTitle3"]);
   ?>
   <form action="<?php echo $GLOBALS["REQUEST_URI"]; ?>" method="GET" enctype="multipart/form-data">
   <tr class="topmenuback">
      <td colspan="4" align="<?php echo $GLOBALS["left"]; ?>" nowrap>
            <select name="ptype" size="1" onChange="submit();">
                <option value="day"<?php if ($ptype == 'day') echo ' selected'; ?>><?php echo $GLOBALS["tpToday"]; ?>
                <option value="week"<?php if ($ptype == 'week') echo ' selected'; ?>><?php echo $GLOBALS["tpThisWeek"]; ?>
                <option value="month"<?php if ($ptype == 'month') echo ' selected'; ?>><?php echo $GLOBALS["tpThisMonth"]; ?>
                <option value="year"<?php if ($ptype == 'year') echo ' selected'; ?>><?php echo $GLOBALS["tpThisYear"]; ?>
                <option value="all"<?php if ($ptype == 'all') echo ' selected'; ?>><?php echo $GLOBALS["tpAllTime"]; ?>
            </select>&nbsp;&nbsp;
            <input type="image" name="submit" src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>go.gif" alt="Go" value="Go">
            <input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>">
            <input type="hidden" name="page" value="<?php echo $_GET["page"]; ?>">
      </td>
   </tr>
   </form>

   <tr class="teaserheadercontent">
       <?php
       adminlistitem(20,$GLOBALS["tCountry"],'');
       adminlistitem(10,$GLOBALS["tHits"],'c');
       adminlistitem(10,$GLOBALS["tPercentage"],'c');
       adminlistitem(60,'&nbsp;','');
       ?>
   </tr>
   <?php
   $pstart = '';
   if ($_GET["ptype"] == 'all') {
      $hitstotal = TotalCount();
   } else {
      $pstart = PeriodStart($_GET["ptype"]);
      $hitstotal = PeriodCount($pstart);
   }

   $i = 0;
   if ($pstart == '') {
      if ($EZ_SESSION_VARS["Site"] != '') {
         $sqlQuery = "SELECT sum(s.countnumber) as hits, s.country AS domain, c.countryname AS country, c.flag AS flag FROM ".$GLOBALS["eztbVisitorstats"]." s LEFT JOIN ".$GLOBALS["eztbCountries"]." c ON c.countrycode=s.country WHERE s.site='".$EZ_SESSION_VARS["Site"]."' AND s.country != '' GROUP BY s.country ORDER BY hits DESC";
      } else {
         $sqlQuery = "SELECT sum(s.countnumber) as hits, s.country AS domain, c.countryname AS country, c.flag AS flag FROM ".$GLOBALS["eztbVisitorstats"]." s LEFT JOIN ".$GLOBALS["eztbCountries"]." c ON c.countrycode=s.country WHERE s.country != '' GROUP BY s.country ORDER BY hits DESC";
      }
   } else {
      if ($EZ_SESSION_VARS["Site"] != '') {
         $sqlQuery = "SELECT sum(s.countnumber) as hits, s.country AS domain, c.countryname AS country, c.flag AS flag FROM ".$GLOBALS["eztbVisitorstats"]." s LEFT JOIN ".$GLOBALS["eztbCountries"]." c ON c.countrycode=s.country WHERE s.site='".$EZ_SESSION_VARS["Site"]."' AND visitdate>='".$pstart."' AND s.country != '' GROUP BY s.country ORDER BY hits DESC";
      } else {
         $sqlQuery = "SELECT sum(s.countnumber) as hits, s.country AS domain, c.countryname AS country, c.flag AS flag FROM ".$GLOBALS["eztbVisitorstats"]." s LEFT JOIN ".$GLOBALS["eztbCountries"]." c ON c.countrycode=s.country WHERE visitdate>='".$pstart."' AND s.country != '' GROUP BY s.country ORDER BY hits DESC";
      }
   }
   $result = dbRetrieve($sqlQuery,true,0,0);
   while($r = dbFetch($result)) {
         
      ?>
      <tr class="teasercontent">
          <td valign="top" class="content"><?php echo DisplayCountry($r["country"],$r["domain"],$r["country"]); ?></td>
          <td valign="top" align="center" class="content"><?php echo $r["hits"]; ?></td>
          <td valign="top" align="center" class="content"><?php echo PercentValue($r["hits"],$hitstotal); ?></td>
          <td valign="top" class="content"><?php echo GraphValue($r["hits"],$hitstotal,$i); ?></td>
      </tr>
      <?php
      $i++;
   }
} // function frmStatsWhere()


function frmStatsHow()
{
   global $_SERVER, $_GET;

   if (!isset($_GET["dtype"])) {
      $dtype = 'browser';
      $ptype = 'all';
   } else {
      $dtype = $_GET["dtype"];
      $ptype = $_GET["ptype"];
   }

   statstitle(4,$GLOBALS["tFormTitle4"]);
   ?>
   <form action="<?php echo $GLOBALS["REQUEST_URI"]; ?>" method="GET" enctype="multipart/form-data">
   <tr class="topmenuback">
      <td colspan="4" align="<?php echo $GLOBALS["left"]; ?>" nowrap>
            <select name="dtype" size="1" onChange="submit();">
                <option value="browser"<?php if ($dtype == 'browser') echo ' selected'; ?>><?php echo $GLOBALS["stBrowser"]; ?>
                <option value="agent"<?php if ($dtype == 'agent') echo ' selected'; ?>><?php echo $GLOBALS["stAgent"]; ?>
                <option value="os"<?php if ($dtype == 'os') echo ' selected'; ?>><?php echo $GLOBALS["stOS"]; ?>
            </select>&nbsp;&nbsp;
            <select name="ptype" size="1" onChange="submit();">
                <option value="day"<?php if ($ptype == 'day') echo ' selected'; ?>><?php echo $GLOBALS["tpToday"]; ?>
                <option value="week"<?php if ($ptype == 'week') echo ' selected'; ?>><?php echo $GLOBALS["tpThisWeek"]; ?>
                <option value="month"<?php if ($ptype == 'month') echo ' selected'; ?>><?php echo $GLOBALS["tpThisMonth"]; ?>
                <option value="year"<?php if ($ptype == 'year') echo ' selected'; ?>><?php echo $GLOBALS["tpThisYear"]; ?>
                <option value="all"<?php if ($ptype == 'all') echo ' selected'; ?>><?php echo $GLOBALS["tpAllTime"]; ?>
            </select>&nbsp;&nbsp;
            <input type="image" name="submit" src="<?php echo $GLOBALS["rootdp"].$GLOBALS["icon_home"]; ?>go.gif" alt="Go" value="Go">
            <input type="hidden" name="ezSID" value="<?php echo $GLOBALS["ezSID"]; ?>">
            <input type="hidden" name="page" value="<?php echo $_GET["page"]; ?>">
      </td>
   </tr>
   </form>
   <?php
   $pstart = '';
   if ($_GET["ptype"] == 'all') {
      $hitstotal = TotalCount();
   } else {
      $pstart = PeriodStart($_GET["ptype"]);
      $hitstotal = PeriodCount($pstart);
   }
   switch ($dtype) {
      case 'browser' : HowStats("browser",$GLOBALS["stBrowser"], $hitstotal,$pstart);
                       break;
      case 'agent'   : HowStats("agent",$GLOBALS["stAgent"], $hitstotal,$pstart);
                       break;
      case 'os'      : HowStats("os",$GLOBALS["stOS"], $hitstotal,$pstart);
   }
} // function frmStatsHow()


function HowStats($field, $type, $hitstotal, $pstart)
{
   global $EZ_SESSION_VARS;

   ?>
   <tr class="teaserheadercontent">
       <?php
       adminlistitem(20,$type,'');
       adminlistitem(10,$GLOBALS["tHits"],'c');
       adminlistitem(10,$GLOBALS["tPercentage"],'c');
       adminlistitem(60,'&nbsp;','');
       ?>
   </tr>
   <?php
   $i = 0;
   if ($pstart == '') {
      if ($EZ_SESSION_VARS["Site"] != '') {
         $sqlQuery = "SELECT visitor".$field." as name, sum(countnumber) as hits FROM ".$GLOBALS["eztbVisitorstats"]." WHERE site='".$EZ_SESSION_VARS["Site"]."' GROUP BY visitor".$field." ORDER BY hits DESC";
      } else {
         $sqlQuery = "SELECT visitor".$field." as name, sum(countnumber) as hits FROM ".$GLOBALS["eztbVisitorstats"]." GROUP BY visitor".$field." ORDER BY hits DESC";
      }
   } else {
      if ($EZ_SESSION_VARS["Site"] != '') {
         $sqlQuery = "SELECT visitor".$field." as name, sum(countnumber) as hits FROM ".$GLOBALS["eztbVisitorstats"]." WHERE site='".$EZ_SESSION_VARS["Site"]."' AND visitdate>='".$pstart."' GROUP BY visitor".$field." ORDER BY hits DESC";
      } else {
         $sqlQuery = "SELECT visitor".$field." as name, sum(countnumber) as hits FROM ".$GLOBALS["eztbVisitorstats"]." WHERE visitdate>='".$pstart."' GROUP BY visitor".$field." ORDER BY hits DESC";
      }
   }
   $result = dbRetrieve($sqlQuery,true,0,10);
   while($r = dbFetch($result)) {
      ?>
      <tr class="teasercontent">
          <td valign="top" class="content"><?php if ($field == 'agent') { echo $r["name"]; } else { echo imagehtmltag($GLOBALS["icon_home"],'stats/'.$r["name"].'.gif',$r["name"],'',0).'&nbsp;&nbsp;'.$r["name"]; } ?></td>
          <td valign="top" align="center" class="content"><?php echo $r["hits"]; ?></td>
          <td valign="top" align="center" class="content"><?php echo PercentValue($r["hits"],$hitstotal); ?></td>
          <td valign="top" class="content"><?php echo GraphValue($r["hits"],$hitstotal,$i); ?></td>
      </tr>
      <?php
      $i++;
   }
   dbFreeResult($result);
} // function HowStats()


function DisplayCountry($country,$domain,$flag)
{
   ?>
   <table border="0" cellpadding="0" cellspacing="0" width="100%">
       <tr>
           <td valign="top">
               <?php
               if ($country != '') { echo $country; } else { echo $domain; }
               //echo 'country='.$country.', domain='.$domain.', flag='.$flag;

?>
           </td>
           <td valign="top" align="<?php echo $GLOBALS["right"]; ?>">
               <?php
               if ($flag != '') { 
                    echo '<img src="../contentimage/flags/'.strtolower($flag).'_small.gif" border=0>';
					//echo imagehtmltag($GLOBALS["icon_home"].'flags/',$flag.'_small.gif',$country,0,'');
               }
               ?>
           </td>
       </tr>
   </table>
   <?php
} // function DisplayCountry()


function PercentValue($hits,$hitstotal)
{
   $percentvalue = '';
   if ($hitstotal > 0) { $percentvalue = number_format(($hits / $hitstotal) * 100, "2");
   } else { $percentvalue = '0'; }
   $percentvalue .= '%';
   return $percentvalue;
} // function PercentValue()


function GraphValue($hits,$hitstotal,$colour)
{
   $colourbars = array('blue','pink','yellow','darkgreen','purple','gold','green','brown','orange','aqua','grey','red');

   $colourval = ($colour % count($colourbars));
   $graphvalue = '';
   $percentage = ceil(($hits / $hitstotal) * 100);
   if (($hitstotal > 0) && ($percentage > 0)) {
      $imagelength = $percentage * $GLOBALS["ScreenWidthMultiplier"];
      $graphvalue='<IMG SRC="'.$GLOBALS["rootdp"].$GLOBALS["icon_home"].'graphbar_'.$colourbars[$colourval].'.gif" HEIGHT="10" WIDTH="'.$imagelength.'">';
   }
   return $graphvalue;
} // function GraphValue()


function statstitle($colspan,$title)
{
   ?>
   <tr class="headercontent">
      <td colspan="<?php echo $colspan; ?>" align="center" class="header">
         <b><?php echo $title; ?></b>
      </td>
   </tr>
   <?php
} // function statstitle()

?>
