<?
	/*////////////////////////////////////////////////////////////
	
	iWare Professional 4.0.0
	Copyright (C) 2002,2003 David N. Simmons 
	http://www.dsiware.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	A COPY OF THE GPL LICENSE FOR THIS PROGRAM CAN BE FOUND WITHIN THE
	docs/ DIRECTORY OF THE INSTALLATION PACKAGE.

	/////////////////////////////////////////////////////////////*/	

class Calendar {
    function Calendar(){}
    function getDayNames(){ return $this->dayNames;}
    function setDayNames($names){$this->dayNames = $names;}
	function getMonthNames(){return $this->monthNames;}
    function setMonthNames($names){$this->monthNames = $names;}
	function getStartDay(){return $this->startDay;}
    function setStartDay($day){$this->startDay = $day;}
    function getStartMonth(){return $this->startMonth;}
    function setStartMonth($month){$this->startMonth = $month;}
	function getCalendarLink($month, $year){return "";}
    function getDateLink($day, $month, $year){return "";}
    function getCurrentMonthView(){$d = getdate(time());return $this->getMonthView($d["mon"], $d["year"]);}
    function getCurrentYearView(){$d = getdate(time());return $this->getYearView($d["year"]);}
    function getMonthView($month, $year){return $this->getMonthHTML($month, $year);}
    function getYearView($year){return $this->getYearHTML($year);}
    function getDaysInMonth($month, $year)
		{
        if ($month < 1 || $month > 12){return 0;}
        $d = $this->daysInMonth[$month - 1];
        if ($month == 2){if ($year%4 == 0){if ($year%100 == 0){if ($year%400 == 0){$d = 29;}}else{$d = 29;}}}    
        return $d;
		}
    function getMonthHTML($m, $y, $showYear = 1)
		{
		global $IW,$D;
        $s = "";
        $a = $this->adjustDate($m, $y);
        $month = $a[0];
        $year = $a[1];        
    	$daysInMonth = $this->getDaysInMonth($month, $year);
    	$date = getdate(mktime(12, 0, 0, $month, 1, $year));
    	$first = $date["wday"];
    	$monthName = $this->monthNames[$month - 1];
    	$prev = $this->adjustDate($month - 1, $year);
    	$next = $this->adjustDate($month + 1, $year);
    	if ($showYear == 1)
			{
			$prevMonth = $this->getCalendarLink($prev[0], $prev[1]);
			$nextMonth = $this->getCalendarLink($next[0], $next[1]);
			}
    	else
			{
			$prevMonth = "";
			$nextMonth = "";
			} 	
    	$header = $monthName . (($showYear > 0) ? " " . $year : "");
    	$s .= "<table class=\"calendar\" border=1 bordercolor=#000000>\n";
    	$s .= "<tr>\n";
    	$s .= "<td align=\"center\" valign=\"top\">" . (($prevMonth == "") ? "&nbsp;" : "<a href=\"$prevMonth\">&lt;&lt;</a>")  . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\" colspan=\"5\">$header</td>\n"; 
    	$s .= "<td align=\"center\" valign=\"top\">" . (($nextMonth == "") ? "&nbsp;" : "<a href=\"$nextMonth\">&gt;&gt;</a>")  . "</td>\n";
    	$s .= "</tr>\n";
    	$s .= "<tr>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . $this->dayNames[($this->startDay)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . $this->dayNames[($this->startDay+1)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . $this->dayNames[($this->startDay+2)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . $this->dayNames[($this->startDay+3)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . $this->dayNames[($this->startDay+4)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . $this->dayNames[($this->startDay+5)%7] . "</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" class=\"calendarHeader\">" . $this->dayNames[($this->startDay+6)%7] . "</td>\n";
    	$s .= "</tr>\n";
    	$d = $this->startDay + 1 - $first;
    	while ($d > 1){$d -= 7;}
        $today = getdate(time());
    	while ($d <= $daysInMonth)
    	{
    	    $s .= "<tr>\n";       
    	    for ($i = 0; $i < 7; $i++)
    	    {
        	    $class = ($year == $today["year"] && $month == $today["mon"] && $d == $today["mday"]) ? "calendarToday" : "calendar";
    	        $s .= "<td class=\"$class\" align=\"right\" valign=\"top\" height=30 width=30>";       
    	        if ($d > 0 && $d <= $daysInMonth)
    	        {
					// is there any events scheduled for this day ? //
					$result=$IW->query("select * from mod_eventcalendar where date_mm='$m' and date_dd='$d' and date_yyyy='$y' limit 1");
					$count=$IW->countResult($result);
					$IW->freeResult($result);
					if($count>0){$s.="<a href=\"?D=$D&show=1&day=$d&month=$m&year=$y&V=month|year|day|show\"><b>$d</b></a>";}
					else{$s.=$d;}
    	        }
    	        else
    	        {
    	            $s .= "&nbsp;";
    	        }
      	        $s .= "</td>\n";       
        	    $d++;
    	    }
    	    $s .= "</tr>\n";    
    	}
    	$s .= "</table>\n";
    	return $s;  	
    }
    function getYearHTML($year)
		{
        $s = "";
    	$prev = $this->getCalendarLink(0, $year - 1);
    	$next = $this->getCalendarLink(0, $year + 1);
        $s .= "<table class=\"calendar\" border=\"0\">\n";
        $s .= "<tr>";
    	$s .= "<td align=\"center\" valign=\"top\" align=\"left\">" . (($prev == "") ? "&nbsp;" : "<a href=\"$prev\">&lt;&lt;</a>")  . "</td>\n";
        $s .= "<td class=\"calendarHeader\" valign=\"top\" align=\"center\">" . (($this->startMonth > 1) ? $year . " - " . ($year + 1) : $year) ."</td>\n";
    	$s .= "<td align=\"center\" valign=\"top\" align=\"right\">" . (($next == "") ? "&nbsp;" : "<a href=\"$next\">&gt;&gt;</a>")  . "</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(0 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(1 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(2 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(3 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(4 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(5 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(6 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(7 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(8 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "<tr>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(9 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(10 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "<td class=\"calendar\" valign=\"top\">" . $this->getMonthHTML(11 + $this->startMonth, $year, 0) ."</td>\n";
        $s .= "</tr>\n";
        $s .= "</table>\n";
        return $s;
		}
    function adjustDate($month, $year)
		{
        $a = array();  
        $a[0] = $month;
        $a[1] = $year;        
        while ($a[0] > 12){$a[0] -= 12;$a[1]++;} 
        while ($a[0] <= 0){$a[0] += 12;$a[1]--;}    
        return $a;
		}
    var $startDay = 0;
    var $startMonth = 1;
    var $dayNames = array("S", "M", "T", "W", "T", "F", "S");
    var $monthNames = array("January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December");
    var $daysInMonth = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
// end class    
}
class MyCalendar extends Calendar {
	function getCalendarLink($month, $year)
		{
		global $D,$V;
		 return "?D=$D&month=$month&year=$year&V=month|year";
		}
// end class
}
if(!isset($month)){$month="";}
if(!isset($year)){$year="";}
$d = getdate(time());
if ($month == ""){$month = $d["mon"];}
if ($year == ""){$year = $d["year"];}
?>
<style type="text/css">
.calendarHeader { font-weight: bolder; }
.calendarToday { background-color: #e4e4e4}
.calendar { }
</style>
<center>
<?php
	$cal = new MyCalendar;
	echo $cal->getMonthView($month, $year);
?>
</center>
<?php
	if(isset($show)&&$show==1)
		{
		$result=$IW->query("select * from mod_eventcalendar where date_mm='$month' and date_dd='$day' and date_yyyy='$year' order by title");
		$count=$IW->countResult($result);
		for($i=0;$i<$count;$i++)
			{
			echo "<p>".$month . "/" . $day . "/" . $year . "<br />\n";
			echo "<b>" . $IW->result($result,$i,"title") . "</b><br />\n";
			echo "" . $IW->result($result,$i,"description") . "</p>\n";
			}
		$IW->freeResult($result);	
		}
?>