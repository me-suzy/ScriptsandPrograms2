<?
/* 
 *  ©2002 Proverbs, LLC. All rights reserved. 
 *
 *  This program is free software; you can redistribute it and/or modify it with the following stipulations:
 *  Changes or modifications must retain all Copyright statements, including, but not limited to the Copyright statement
 *  and Proverbs, LLC homepage link provided at the bottom of this page. 
 */ 

   require "layout.inc.php";
   require ".dbaccess.inc";

   if (!isset($month) || $month == "" || $month > 12 || $month < 1)
   {
      $month = date("m");
   }
   if (!isset($year) || $year == "" || $year < 1972 || $year > 2036)
   {
      $year = date("Y");
   }

   $timestamp = mktime(0, 0, 0, $month, 1, $year);
   
   $current = date("F Y", $timestamp);

   if ($month < 2)
   {
      $prevmonth = 12;
      $prevyear = $year - 1;
   }
   else
   {
      $prevmonth = $month - 1;
      $prevyear = $year;
   }

   if ($month > 11)
   {
      $nextmonth = 1;
      $nextyear = $year + 1;
   }
   else
   {
      $nextmonth = $month + 1;
      $nextyear = $year;
   }

   $backward = date("F Y", mktime(0, 0, 0, $prevmonth, 1, $prevyear));
   $forward = date("F Y", mktime(0, 0, 0, $nextmonth, 1, $nextyear));

   $first = date("w", mktime(0, 0, 0, $month, 1, $year));
   
   $lastday = 28;
   
   for ($i=$lastday;$i<32;$i++)
   {
      if (checkdate($month, $i, $year))
      {
         $lastday = $i;
      }
   }
   
   function AddDay($fday, $fmonth, $fyear, $fvar)
   {
      if (!isset($fday) || $fday == "")
      {
         echo '	<TD class="calendar" align="left" valign="top" width=90 height=70>
		&nbsp;
';
      }
      else
      {
         $schurl = 'schedule.php?day='.$fday.'&month='.$fmonth.'&year='.$fyear;
         if (date("m") == $fmonth && date("Y") == $fyear && date("j") == $fday)
         {
            echo '	<TD ID="day'.$fday.'" class="curday" style="cursor: hand" align="left" valign="top" width=90 height=70 
		onMouseOver="tdmouseover(\'day'.$fday.'\')"; onMouseOut="tdcurmouseout(\'day'.$fday.'\')"; 
		onClick="window.open(\''.$schurl.'\', \'schedule\', \'width=534,height=400,scrollbars=yes,resizable=yes\')">
';
         }
         else
         {
            echo '	<TD ID="day'.$fday.'" class="calendar" style="cursor: hand" align="left" valign="top" width=90 height=70 
		onMouseOver="tdmouseover(\'day'.$fday.'\')"; onMouseOut="tdmouseout(\'day'.$fday.'\')"; 
		onClick="window.open(\''.$schurl.'\', \'schedule\', \'width=534,height=400,scrollbars=yes,resizable=yes\')">
';
         }
         echo '		<b>'.$fday.'</b><br>
';
         if (isset($fvar) && $fvar != "")
         {
            echo '		<A class=\'calendar\' style="cursor: hand" onClick="javascript:window.open(\''.$schurl.'\', 
		\'schedule\', \'width=534,height=400,scrollbars=yes,resizable=yes\')">
';
            echo '		'.$fvar.'
		</A>';
         }
      }
      echo '	</TD>
';
   }

   function FillDay($caldb, $dayofweek, $dayofmonth, $thismonth, $thisyear)
   {
      $textbody = '';
      $nr = $caldb->GetByDate($thismonth, $dayofmonth, $thisyear);
      for ($k=0;$k<$nr;$k++)
      {
         $caldb->next_record();
         $textbody.= $caldb->f('shortevent').'<br>';
      }
      $nr = $caldb->GetYearly($thismonth, $dayofmonth);
      for ($k=0;$k<$nr;$k++)
      {
          $caldb->next_record();
          $textbody.= $caldb->f('shortevent').'<br>';
      }
      $nr = $caldb->GetYearlyRecurring($thismonth, $dayofweek);
      for ($k=0;$k<$nr;$k++)
      {
          $caldb->next_record();
          $test = $dayofmonth / 7;
          $periodlow = $caldb->f('period') - 1;
          if ($test <= $caldb->f('period') && $test > $periodlow)
          {
             $textbody.= $caldb->f('shortevent').'<br>';
          }
      }
      $nr = $caldb->GetMonthly($dayofmonth);
      for ($k=0;$k<$nr;$k++)
      {
          $caldb->next_record();
          $textbody.= $caldb->f('shortevent').'<br>';
      }
      $nr = $caldb->GetMonthlyRecurring($dayofweek);
      for ($k=0;$k<$nr;$k++)
      {
          $caldb->next_record();
          $test = $dayofmonth / 7;
          $periodlow = $caldb->f('period') - 1;
          if ($test <= $caldb->f('period') && $test > $periodlow)
          {
             $textbody.= $caldb->f('shortevent').'<br>';
          }
      }
      $nr = $caldb->GetWeekly($dayofweek);
      for ($k=0;$k<$nr;$k++)
      {
         $caldb->next_record();
         $textbody.= $caldb->f('shortevent').'<br>';
      }
      return $textbody;
   }

   echo '<HTML>
<HEAD>
   <TITLE>'.$calender_title.'</TITLE>
   <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">';
   echo '<STYLE TYPE="text/css">
	<!--
		BODY {background-color: #'.$background_color.'; border-style: none; border-width: 0px; color: #'.$plain_text_color.'; 
			font-family: Arial; font-size: 12px; font-style: normal; margin: 0px; padding: 4px;
			text-align: left; text-decoration: none; text-indent: 0px}
		A {border-style: none; border-width: 0px; color: #'.$link_color.'; font-family: Arial; font-size: 12px; 
			font-style: normal; margin: 0px; padding: 0px; text-align: left; text-decoration: none;
			text-indent: 0px}
		A.normal {font-size: 12px; text-decoration: underline}
		A.calendar {color: #'.$calendar_link_color.'; font-size:10px}
		A.bottom {color: #'.$link_color.'; font-size:10px}
		P {font-size: 10px; text-align:center; color: #'.$link_color,'}
		IMG {border-style: none; border-width: 0px; margin: 0px; padding: 0px}
		TABLE {border-style: none; margin: 0px; padding: 0px; border-width: none; font-size: 12px; text-indent: 0px;
			font-weight: normal; width: 630px; background-color: #'.$calendar_bg_color.'; color: #'.$plain_text_color.'}
		TABLE.top {width: 100%; height: 60px}
		TABLE.form {width: 100%; height: 60px; text-align: center; border-style:none; border-width: 0px; 
			background-color: #'.$background_color.'; color: #'.$plain_text_color.'}
		TR {border-style: none; border-width: 0px; margin: 0px; padding: 0px}
		TD {border-style: solid; border-width: thin; margin: 0px; padding: 0px; border-color: #'.$calendar_border_color.';
			font-weight: normal; background-color: #'.$calendar_bg_color.'}
		TD.top {padding: 4px; font-size: 16px; height: 60px; text-align:center; font-weight: bold; 
			border-style: none; border-width: 0px}
		TD.ends {padding: 4px; font-size: 12px; height: 60px; text-align: center; border-style: none; 
			border-width: 0px; font-weight: bold}
		TD.form {padding: 0px; font-size: 12px; border-style: none; border-width: 0px; 
			background-color: #'.$background_color.'; color: #'.$plain_text_color.'}
		TD.days {padding: 2px; font-size: 12px; width: 90px; height: 40px; text-align:center; font-weight: bold}
		TD.curday {width: 90px; text-align: left; font-size: 10px; height: 70px; background-color: #'.$current_day_color.'}
		TD.calendar {width: 90px; text-align: left; font-size: 10px; height: 70px}
	-->
	</STYLE>
';
	echo '
	<SCRIPT LANGUAGE="JavaScript">
	<!-- 
		var isIE;
		isIE = (document.all) ? true : false;

		function tdmouseover(itemID)
		{
		   if(isIE)
		   {
		      var theObj = eval("document.all." + itemID);
			
		      theObj.style.backgroundColor = \'#'.$mouse_over_color.'\';
		   }
		}

		function tdmouseout(itemID)
		{
		   if(isIE)
		   {
		      var theObj = eval("document.all." + itemID);

		      theObj.style.backgroundColor = \'#'.$calendar_bg_color.'\';
		   }
		}
		
		function tdcurmouseout(itemID)
		{
		   if(isIE)
		   {
		      var theObj = eval("document.all." + itemID);

		      theObj.style.backgroundColor = \'#'.$current_day_color.'\';
		   }
		}
	//-->
	</SCRIPT>
</HEAD>
';
	echo '<BODY TOPMARGIN=0 LEFTMARGIN=0 MARGINHEIGHT=0 MARGINWIDTH=0>
	<CENTER>
	<TABLE cellspacing=0 cellpadding=0 width=560 border=0>
	<TR>
	<TD class="form" align="center" valign="bottom" width="100%" COLSPAN=7>
		<FORM METHOD="post" ACTION="calendar.php">
		<TABLE class="form" cellspacing=0 cellpadding=0 width="100%" border=0>
		<TR>
		<TD class="form" align="left" valign="bottom">
			<b>Today\'s Date:</b> '.date("F j, Y").'
		</TD>
		<TD class="form" align="left" valign="bottom">
			<b>Month:</b> <select name="month">
';
			for ($j=1;$j<=12;$j++)
			{
			   echo'<option value='.$j;
			   if ($month == $j)
			   {
			      echo ' selected';
			   }
			   echo '>'.date("F", mktime(0, 0, 0, $j, 1, 0)).'
			   ';
			}
			echo '			</select>			
		         &nbsp;&nbsp;<b>Year:</b> <select name="year">
';
			for ($j=1972;$j<=2036;$j++)
			{
			   echo'<option value='.$j;
			   if ($year == $j)
			   {
			      echo ' selected';
			   }
			   echo '>'.$j.'
			   ';
			}
			echo '			</select>
			 &nbsp;&nbsp;<input type="submit" value="Submit">			
		</TD>
		</TR>
		<TR>
		<TD class="form" align="right" valign="bottom" colspan=2>
			<A style="cursor: hand" onClick="javascript:window.open(\'caladmin.php\', \'caladmin\', 
				\'width=414,height=422,scrollbars=yes,resizable=yes\')" 
				onMouseOver="window.status=\'\'">Administration</A>
		</TD>
		</TR>
		</TABLE>
		</FORM>
	</TD>	
	</TR>
	<TR>
	<TD align="center" valign="middle" height=60 COLSPAN=7>
		<TABLE class="top" cellspacing=0 cellpadding=0 width=560 border=0>
		<TR>
		<TD class="ends" nowrap align="center" valign="bottom">
			<A HREF="calendar.php?month='.$prevmonth.'&year='.$prevyear.'"><< '.$backward.'</a>
		</TD>
		<TD class="top" nowrap align="center" valign="middle" width=350>
';
   if (isset($calender_title_image) && $calender_title_image != '')
   {
      echo '			<img src="'.$calender_title_image.'">';
   }
   else
   {
      echo '			'.$calender_title;
   }
   echo '<br>'.$current.'
		</TD>
		<TD class="ends" nowrap align="center" valign="bottom">
			<A HREF="calendar.php?month='.$nextmonth.'&year='.$nextyear.'">'.$forward.' >></a>
		</TD>
		</TR>
		</TABLE>
	</TD>
	</TR>
	<TR>';
   if (isset($start_day) && $start_day <= 6 && $start_day >= 0)
   {
      $n = $start_day;
   }
   else
   {
      $n = 0;
   } 
   for ($i=0;$i<7;$i++)
   {
      if ($n > 6)
      {
         $n = 0;
      }
      if ($n == 0)
      {
         echo '	<TD class="days" nowrap align="center" valign="middle" width=90 height=40>
		Sunday
	</TD>';
      }
      if ($n == 1)
      {
         echo '	<TD class="days" nowrap align="center" valign="middle" width=90 height=40>
		Monday
	</TD>';
      }
      if ($n == 2)
      {
         echo '	<TD class="days" nowrap align="center" valign="middle" width=90 height=40>
		Tuesday
	</TD>';
      }
      if ($n == 3)
      {
         echo '	<TD class="days" nowrap align="center" valign="middle" width=90 height=40>
		Wednesday
	</TD>';
      }
      if ($n == 4)
      {
         echo '	<TD class="days" nowrap align="center" valign="middle" width=90 height=40>
		Thursday
	</TD>';
      }
      if ($n == 5)
      {
         echo '	<TD class="days" nowrap align="center" valign="middle" width=90 height=40>
		Friday
	</TD>';
      }
      if ($n == 6)
      {
         echo '	<TD class="days" nowrap align="center" valign="middle" width=90 height=40>
		Saturday
	</TD>';
      }
      $n++;
   }
   echo'	</TR>
';
   $calday = 1;
   while ($calday <= $lastday)
   {
/* Alternate beginning day of the week for calendar view was created by Marion Heider of clixworx.net. */
      echo '<TR>';
      for ($j=0;$j<7;$j++)
      {
         if ($j == 0)
         {
            $n = $start_day;
         }
         else
         {
            if ($n < 6)
            {
               $n = $n + 1;
            }
            else
            {
               $n = 0;
            }
         }
         if ($calday == 1)
         {
            if ($first == $n)
            {
               $info = FillDay($db, $n, $calday, $month, $year);
               AddDay($calday, $month, $year, $info);
               $calday++;
            }
            else
            {
               AddDay('', '', '', '');
            }
         }
         else
         {
            if ($calday > $lastday)
            {
               AddDay('', '', '', '');
            }
            else
            {
               $info = FillDay($db, $n, $calday, $month, $year);
               AddDay($calday, $month, $year, $info);
               $calday++;
            }
         }
      } 
      echo '</TR>';
   }
   echo '	</TABLE>
	</CENTER>
	<P>©2002 <a class="bottom" href="http://www.proverbs.biz">Proverbs</a>, LLC. All rights reserved.</P>
</BODY>
</HTML>';
?>
