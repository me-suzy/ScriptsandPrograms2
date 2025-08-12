<?php
//***************************************************************************//
//                                                                           //
//  Copyright (c) 2004-2005 Jonathon J. Freeman                              //
//  All rights reserved.                                                     //
//                                                                           //
//  This program is free software. You may use, modify, and/or redistribute  //
//  it under the terms of the OvBB License Agreement v2 as published by the  //
//  OvBB Project at www.ovbb.org.                                            //
//                                                                           //
//***************************************************************************//

	// Initialize OvBB.
	require('includes/init.inc.php');

	// Does the user have authorization to view the calendar?
	if(!$aPermissions['ccalendar'])
	{
		// No. Let them know the bad news.
		Unauthorized();
	}

	// What month do they want?
	$iMonth = (int)$_REQUEST['month'];
	if($iMonth == 0)
	{
		// They don't know what they want. Give them the current month.
		$iMonth = gmtdate('n', $CFG['globaltime']);
	}
	else
	{
		$iMonth = $iMonth % 12;
	}

	// What year do they want?
	$iYear = (int)$_REQUEST['year'];
	if($iYear == 0)
	{
		// They don't know what they want. Give them the current year.
		$iYear = gmtdate('Y', $CFG['globaltime']);
	}

	// Get the start of the week for this user... Are they logged in?
	if($_SESSION['loggedin'])
	{
		// Yes, use their preference.
		$iStartOfWeek = $_SESSION['weekstart'];
	}
	else
	{
		// No, use the forum default.
		$iStartOfWeek = $CFG['default']['weekstart'];
	}

	// Create array containing days of week.
	$daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

	// What is the first day of the month in question?
	$firstDayOfMonth = mktime(0, 0, 0, $iMonth, 1, $iYear);

	// How many days does this month have?
	$iDayCount = date('t', $firstDayOfMonth);

	// Retrieve some information about the first day of the month in question.
	$dateComponents = getdate($firstDayOfMonth);
	$strMonthName = $dateComponents['month'];
	$iWeekOffset = $dateComponents['wday'];

	// Initiate the counters.
	$iCurrentDate = 1;
	$iCurrentWeek = 0;

	// What is today?
	$dateNow = mktime(0, 0, 0, gmtdate('m', $CFG['globaltime']), gmtdate('d', $CFG['globaltime']), gmtdate('Y', $CFG['globaltime']));

	// First thing to do is convert the offset of the first day of this month (based on Sunday being
	// first day of week) to our own offset system (based on whatever the user specifies).
	if(($iWeekOffset - $iStartOfWeek) < 0)
	{
		$iWeekOffset = 7 + $iWeekOffset - $iStartOfWeek;
	}
	else
	{
		$iWeekOffset = $iWeekOffset - $iStartOfWeek;
	}

	// If necessary, "display" the days of the calendar
	// which are not part of the current month.
	if($iWeekOffset != 0)
	{
		// What is the date of the first day of the first week in this month's calendar?
		$iPrevMonthDay = date('d', mktime(0, 0, 0, $iMonth, (1 - $iWeekOffset), $iYear));

		for($i = 0; $i < $iWeekOffset; $i++)
		{
			// What is the timestamp for this date?
			$dateTime = mktime(0, 0, 0, ($iMonth - 1), $iPrevMonthDay, $iYear);

			// Set the information.
			$aMaster[$iCurrentWeek][$i][0] = $iPrevMonthDay;
			$aMaster[$iCurrentWeek][$i][1] = $CFG['style']['calcolor']['datea']['bgcolor'];
			$aMaster[$iCurrentWeek][$i][2] = $CFG['style']['calcolor']['datea']['txtcolor'];
			$aMaster[$iCurrentWeek][$i][4] = TRUE;

			// Is this date today's date?
			if($dateTime == $dateNow)
			{
				// Yes. Mark it as such.
				$aMaster[$iCurrentWeek][$i][3] = TRUE;
			}

			// Next day please...
			$iPrevMonthDay++;
		}
	}

	// "Display" the days of the month.
	while($iCurrentDate <= $iDayCount)
	{
		$aMaster[$iCurrentWeek][$iWeekOffset][0] = $iCurrentDate;

		// What is the timestamp for this date?
		$dateTime = mktime(0, 0, 0, $iMonth, $iCurrentDate, $iYear);

		// Is this date today's date?
		if($dateTime == $dateNow)
		{
			// Yes. Mark it as such.
			$aMaster[$iCurrentWeek][$iWeekOffset][1] = $CFG['style']['calcolor']['today']['bgcolor'];
			$aMaster[$iCurrentWeek][$iWeekOffset][2] = $CFG['style']['calcolor']['today']['txtcolor'];
			$aMaster[$iCurrentWeek][$iWeekOffset][3] = TRUE;
		}
		else
		{
			// No. Just another old date.
			$aMaster[$iCurrentWeek][$iWeekOffset][1] = $CFG['style']['calcolor']['dateb']['bgcolor'];;
			$aMaster[$iCurrentWeek][$iWeekOffset][2] = $CFG['style']['calcolor']['dateb']['txtcolor'];;
		}

		// Next date please...
		$iCurrentDate++;

		// Update the week offset.
		$iWeekOffset++;
		if($iWeekOffset == 7)
		{
			// Reset week offset.
			$iWeekOffset = 0;

			// Increment the week.
			$iCurrentWeek++;
		}
	}

	// If necessary, display the remaining days of the calendar
	// which are not part of the current month.
	if($iWeekOffset != 0)
	{
		// How many more days do we have left in this calendar week?
		$iDaysLeft = 7 - $iWeekOffset;

		// Display the remaining days.
		for($iNextMonthDay = 1; $iDaysLeft > 0; $iNextMonthDay++)
		{
			// What is the timestamp for this date?
			$dateTime = mktime(0, 0, 0, ($iMonth + 1), $iNextMonthDay, $iYear);

			// Set the information.
			$aMaster[$iCurrentWeek][$iWeekOffset][0] = $iNextMonthDay;
			$aMaster[$iCurrentWeek][$iWeekOffset][1] = $CFG['style']['calcolor']['datea']['bgcolor'];
			$aMaster[$iCurrentWeek][$iWeekOffset][2] = $CFG['style']['calcolor']['datea']['txtcolor'];
			$aMaster[$iCurrentWeek][$iWeekOffset][4] = TRUE;

			// Is this date today's date?
			if($dateTime == $dateNow)
			{
				// Yes. Mark it as such.
				$aMaster[$iCurrentWeek][$iWeekOffset][3] = TRUE;
			}

			$iDaysLeft--;
			$iWeekOffset++;
		}
	}

	// Get any birthdays that come this month.
	$sqlResult = sqlquery("SELECT id, username, birthday FROM member WHERE date_format(birthday, '%c') = $iMonth");
	while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
	{
		$aBirthdays[date('j', strtotime($aSQLResult['birthday']))][$aSQLResult['id']][0] = $aSQLResult['birthday'];
		$aBirthdays[date('j', strtotime($aSQLResult['birthday']))][$aSQLResult['id']][1] = $aSQLResult['username'];
	}

	// Get any events that come this month.
	$sqlResult = sqlquery("SELECT id, author, date, title, public FROM event WHERE date_format(date, '%c/%Y') = '$iMonth/$iYear'");
	while($aSQLResult = mysql_fetch_array($sqlResult, MYSQL_ASSOC))
	{
		$aEvents[date('j', strtotime($aSQLResult['date']))][$aSQLResult['id']][0] = $aSQLResult['author'];
		$aEvents[date('j', strtotime($aSQLResult['date']))][$aSQLResult['id']][1] = $aSQLResult['title'];
		$aEvents[date('j', strtotime($aSQLResult['date']))][$aSQLResult['id']][2] = $aSQLResult['public'];
	}

	// Header.
	$strPageTitle = " :: Calendar :. $strMonthName $iYear";
	require('includes/header.inc.php');
?>

<TABLE width="100%" cellspacing=0 cellpadding=2 border=0 align=center>
<TR>
	<TD align=left valign=top><A href="index.php"><IMG src="images/ovbb.png" align=middle border=0 alt="<?php echo(htmlspecialchars($CFG['general']['name'])); ?> :: Powered by OvBB"></A></TD>
	<TD width="50%" align=left valign=top class=medium><B><A href="index.php"><?php echo(htmlspecialchars($CFG['general']['name'])); ?></A> &gt; <A href="calendar.php">Calendar</A> &gt; <?php echo("$strMonthName $iYear"); ?></B>
	<TD width="50%" align=right valign=top><A href="event.php?action=add&amp;type=public"><IMG src="images/public_event.png" border=0 alt="Add a public event"></A><IMG src="images/space.png" width=8 height=1 alt=""><A href="event.php?action=add&amp;type=private"><IMG src="images/private_event.png" border=0 alt="Add a private event"></A>
</TR>
</TABLE><BR>

<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellpadding=4 cellspacing=1 border=0 width="100%">

<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['section']['bgcolor']); ?>" width="100%" align=left colspan=7><FONT class=medium color="<?php echo($CFG['style']['table']['section']['txtcolor']); ?>"><B><?php echo(htmlspecialchars($CFG['general']['name'])); ?> Calendar</B></FONT></TD>
</TR>

<TR>
<?php
	// Display the days of the week.
	for($i = $iStartOfWeek; $i < 7; $i++)
	{
		echo("	<TD bgcolor=\"{$CFG['style']['table']['heading']['bgcolor']}\" width=\"14%\" align=center><FONT class=smaller color=\"{$CFG['style']['table']['heading']['txtcolor']}\"><B>$daysOfWeek[$i]</B></FONT></TD>\n");
	}
	if($iStartOfWeek != 0)
	{
		for($i = 0; $i < $iStartOfWeek; $i++)
		{
			echo("	<TD bgcolor=\"{$CFG['style']['table']['heading']['bgcolor']}\" width=\"14%\" align=center><FONT class=smaller color=\"{$CFG['style']['table']['heading']['txtcolor']}\"><B>$daysOfWeek[$i]</B></FONT></TD>\n");
		}
	}
?>
</TR>

<?php
	// Display the calendar weeks table.
	foreach($aMaster as $aWeek)
	{
		// Start a new calendar week.
		echo("<TR>\n");

		// Display the days in the week.
		foreach($aWeek as $aDay)
		{
			$iDate = $aDay[0];
			$strBGColor = $aDay[1];
			$strFGColor = $aDay[2];
			$bToday = (bool)$aDay[3];
			$bOtherMonth = $aDay[4];
?>	<TD align=left valign=top height="100" bgcolor="<?php echo($strBGColor); ?>" class=medium<?php if($bToday){echo(' style="border: 2px; border-style: outset;"');} ?>>
		<FONT color="<?php echo($strFGColor); ?>"><?php echo($iDate); ?></FONT>
<?php
			// Display any birthdays for this day.
			if(($aBirthdays[$iDate]) && ($bOtherMonth == FALSE))
			{
?>		<DIV class=smaller style="margin: 3px;">
<?php
				foreach($aBirthdays[$iDate] as $k => $v)
				{
					// Calculate their age.
					list($year,,) = sscanf($v[0], "%04u-%02u-%02u");
					if($year)
					{
						$iAge = $iYear - $year;
					}
?>			- <A href="profile.php?userid=<?php echo($k); ?>"><?php echo(htmlspecialchars($v[1])); ?>'s birthday</A><?php if($iAge > 0){echo(" ($iAge)");} ?><BR>
<?php
					// Reset the age for the next guy.
					$iAge = NULL;
				}
?>		</DIV>
<?php
			}

			// Display any events for this day.
			if(($aEvents[$iDate]) && ($bOtherMonth == FALSE))
			{
?>		<DIV class=smaller style="margin: 3px;">
<?php
				foreach($aEvents[$iDate] as $k => $v)
				{
					// Only display if it's either public or if it's private and the user that created it is logged in.
					if(($v[2] == 1) || (($v[2] == 0) && ($_SESSION['userid'] == $v[0])))
					{
?>			- <A href="event.php?action=view&amp;eventid=<?php echo($k); ?>"><?php echo(htmlspecialchars($v[1])); ?></A><BR>
<?php
					}
				}
?>		</DIV>
<?php
			}
?>	</TD>
<?php
		}

		// End the week row.
		echo("</TR>\n\n");
	}

	$datePrevMonth = mktime(0, 0, 0, ($iMonth - 1), 1, $iYear);
	$dateNextMonth = mktime(0, 0, 0, ($iMonth + 1), 1, $iYear);
?>
</TABLE><BR>

<TABLE bgcolor="<?php echo($CFG['style']['table']['bgcolor']); ?>" cellpadding=4 cellspacing=1 border=0 width="100%">
<TR>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="34%" align=center valign=middle class=smaller><A href="event.php?action=add&amp;type=public"><IMG src="images/public_event.png" border=0 alt="Add a public event"></A><IMG src="images/space.png" width=8 height=1 alt=""><A href="event.php?action=add&amp;type=private"><IMG src="images/private_event.png" border=0 alt="Add a private event"></A></TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cella']); ?>" width="33%" align=center valign=middle class=smaller>
	<FORM style="margin: 0px;" action="calendar.php" method=get>
	<TABLE cellpadding=0 cellspacing=0 border=0>
	<TR>
		<TD valign=middle><SELECT name=month>
			<OPTION value="1"<?php if($iMonth==1){echo(' selected');} ?>>January</OPTION>
			<OPTION value="2"<?php if($iMonth==2){echo(' selected');} ?>>February</OPTION>
			<OPTION value="3"<?php if($iMonth==3){echo(' selected');} ?>>March</OPTION>
			<OPTION value="4"<?php if($iMonth==4){echo(' selected');} ?>>April</OPTION>
			<OPTION value="5"<?php if($iMonth==5){echo(' selected');} ?>>May</OPTION>
			<OPTION value="6"<?php if($iMonth==6){echo(' selected');} ?>>June</OPTION>
			<OPTION value="7"<?php if($iMonth==7){echo(' selected');} ?>>July</OPTION>
			<OPTION value="8"<?php if($iMonth==8){echo(' selected');} ?>>August</OPTION>
			<OPTION value="9"<?php if($iMonth==9){echo(' selected');} ?>>September</OPTION>
			<OPTION value="10"<?php if($iMonth==10){echo(' selected');} ?>>October</OPTION>
			<OPTION value="11"<?php if($iMonth==11){echo(' selected');} ?>>November</OPTION>
			<OPTION value="12"<?php if($iMonth==12){echo(' selected');} ?>>December</OPTION>
		</SELECT> <SELECT name=year>
<?php
	for($i = $iYear - 3; $i < ($iYear + 3); $i++)
	{
?>			<OPTION value="<?php echo($i); ?>"<?php if($i == $iYear){echo(' selected');} ?>><?php echo($i); ?></OPTION>
<?php
	}
?>		</SELECT></TD>
		<TD class=smaller valign=middle>&nbsp;<INPUT type=image src="images/go.png"></TD>
	</TR>
	</TABLE>
	</FORM>
	</TD>
	<TD bgcolor="<?php echo($CFG['style']['table']['cellb']); ?>" width="33%" align=center valign=middle class=smaller><IMG src="images/larrow.png" align=middle alt=""> <B><A href="calendar.php?month=<?php echo(date('n', $datePrevMonth)); ?>&amp;year=<?php echo(date('Y', $datePrevMonth)); ?>"><?php echo(date('F Y', $datePrevMonth)); ?></A></B> | <B><A href="calendar.php?month=<?php echo(date('n', $dateNextMonth)); ?>&amp;year=<?php echo(date('Y', $dateNextMonth)); ?>"><?php echo(date('F Y', $dateNextMonth)); ?></A></B> <IMG src="images/rarrow.png" align=middle alt=""></TD>
</TR>
</TABLE>

<?php
	// Footer.
	require('includes/footer.inc.php');
?>