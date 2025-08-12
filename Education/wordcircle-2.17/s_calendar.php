<?php

/*

CLASS
-----
CALENDAR


PROPERTIES
----------
calendarArray()


METHODS
-------
getEventInfo()
setEventInfo()
deleteEvent()
createEvent()
getEvents()


*/

class calendar {

function show_days_in_month($month,$year){

	   {
       if(checkdate($month, 31, $year)) return 31;
       if(checkdate($month, 30, $year)) return 30;
       if(checkdate($month, 29, $year)) return 29;
       if(checkdate($month, 28, $year)) return 28;
       return 0; // error
   }

}

function show(){

//MAKE SURE TO INCLUDE THE OVERLIB HTML FILE!
echo('<SCRIPT language="JavaScript" src="j_overlib.js"></SCRIPT>');
echo('<DIV id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;">
	</DIV>');
	
if(!isset($_REQUEST['date'])){ 
   $date = mktime(0,0,0,date('m'), date('d'), date('Y')); 
} else { 
   $date = $_REQUEST['date']; 
} 

$day = date('d', $date); 
$month = date('m', $date); 
$year = date('Y', $date); 

//Get things that are happening


$monthcal = $GLOBALS['db']->execQuery("select calendar_id, date_format(dateOf,'%H') as dHour,date_format(dateOf,'%i') as dMin,date_format(dateOf,'%m') as dMon,date_format(dateOf,'%d') as dDay,date_format(dateOf,'%y') as dYear,replace(calendar,'\'','&acute;') as calendar from calendar where group_id = ".$_GET['gid']." and dateOf like '".$year."-".$month."%' order by dateOf asc");

$dayEvents = array();
$dayEventList = array();

while ($row = mysql_fetch_assoc($monthcal)){
$tdate = mktime($row['dHour'],$row['dMin'],0,$row['dMon'], $row['dDay'], $row['dYear']); 
$ddate = mktime(0,0,0,$row['dMon'], $row['dDay'], $row['dYear']); 
array_push($dayEventList,$ddate);
if(array_key_exists($ddate,$dayEvents))
	{$dayEvents[$ddate] .= "<p>" . date('h',$tdate).":".date('i',$tdate)."".date('a',$tdate)." ".htmlentities($row['calendar'],ENT_QUOTES);}
	else
	{$dayEvents[$ddate] = date('h',$tdate).":".date('i',$tdate)."".date('a',$tdate)." ". htmlentities($row['calendar'],ENT_QUOTES);}
}

// Get the first day of the month 
$month_start = mktime(0,0,0,$month, 1, $year); 

// Get friendly month name 
$month_name = date('M', $month_start); 

// Figure out which day of the week 
// the month starts on. 
$month_start_day = date('D', $month_start); 

switch($month_start_day){ 
    case "Sun": $offset = 0; break; 
    case "Mon": $offset = 1; break; 
    case "Tue": $offset = 2; break; 
    case "Wed": $offset = 3; break; 
    case "Thu": $offset = 4; break; 
    case "Fri": $offset = 5; break; 
    case "Sat": $offset = 6; break; 
} 

// determine how many days are in the last month. 
if($month == 1){ 
   $num_days_last = $this->show_days_in_month(12, ($year -1)); 
} else { 
   $num_days_last = $this->show_days_in_month(($month -1), $year); 
} 
// determine how many days are in the current month. 
$num_days_current = $this->show_days_in_month($month, $year); 

// Build an array for the current days 
// in the month 
for($i = 1; $i <= $num_days_current; $i++){ 
    $num_days_array[] = $i; 
} 

// Build an array for the number of days 
// in last month 
for($i = 1; $i <= $num_days_last; $i++){ 
    $num_days_last_array[] = $i; 
} 

// If the $offset from the starting day of the 
// week happens to be Sunday, $offset would be 0, 
// so don't need an offset correction. 

if($offset > 0){ 
    $offset_correction = array_slice($num_days_last_array, -$offset, $offset); 
    $new_count = array_merge($offset_correction, $num_days_array); 
    $offset_count = count($offset_correction); 
} 

// The else statement is to prevent building the $offset array. 
else { 
    $offset_count = 0; 
    $new_count = $num_days_array; 
} 

// count how many days we have with the two 
// previous arrays merged together 
$current_num = count($new_count); 

// Since we will have 5 HTML table rows (TR) 
// with 7 table data entries (TD) 
// we need to fill in 35 TDs 
// so, we will have to figure out 
// how many days to appened to the end 
// of the final array to make it 35 days. 


if($current_num > 35){ 
   $num_weeks = 6; 
   $outset = (42 - $current_num); 
} elseif($current_num < 35){ 
   $num_weeks = 5; 
   $outset = (35 - $current_num); 
} 
if($current_num == 35){ 
   $num_weeks = 5; 
   $outset = 0; 
} 
// Outset Correction 
for($i = 1; $i <= $outset; $i++){ 
   $new_count[] = $i; 
} 

// Now let's "chunk" the $all_days array 
// into weeks. Each week has 7 days 
// so we will array_chunk it into 7 days. 
$weeks = array_chunk($new_count, 7); 


// Build Previous and Next Links 
$previous_link = "<a href=\"".$_SERVER['PHP_SELF']."?gid=".$_GET['gid']."&a=calendar&date="; 
if($month == 1){ 
   $previous_link .= mktime(0,0,0,12,$day,($year -1)); 
} else { 
   $previous_link .= mktime(0,0,0,($month -1),$day,$year); 
} 
$previous_link .= "\"><< Prev</a>"; 

$next_link = "<a href=\"".$_SERVER['PHP_SELF']."?gid=".$_GET['gid']."&a=calendar&date="; 
if($month == 12){ 
   $next_link .= mktime(0,0,0,1,$day,($year + 1)); 
} else { 
   $next_link .= mktime(0,0,0,($month +1),$day,$year); 
} 
$next_link .= "\">Next >></a>"; 

// Build the heading portion of the calendar table 
echo "<br><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"230\" class=\"calendar\" align='center'>\n". 
     "<tr>\n". 
     "<td colspan=\"7\">\n". 
     "<table align=\"center\">\n". 
     "<tr>\n". 
     "<td colspan=\"7\" align=\"center\" nowrap='nowrap'>$previous_link <img src='icon_singlepx.gif' width='5' height='1' /> <a href=\"".$_SERVER['PHP_SELF']."?gid=".$_GET['gid']."&a=calendar&date=".mktime(0,0,0,$month,$day	,$year)."\">$month_name $year</a> <img src='icon_singlepx.gif' width='5' height='1' />  $next_link</td>\n".
     "</tr>\n". 
     "</table>\n". 
     "</td>\n". 
     "<tr>\n". 
     "<td align='center'>S</td><td align='center'>M</td><td align='center'>T</td><td align='center'>W</td><td align='center'>T</td><td align='center'>F</td><td align='center'>S</td>\n". 
     "</tr>\n"; 

// Now we break each key of the array 
// into a week and create a new table row for each 
// week with the days of that week in the table data 

$i = 0; 
foreach($weeks AS $week){ 
       echo "<tr>\n"; 
       foreach($week as $d){ 
         if($i < $offset_count){ 
             $day_link = "<a href=\"".$_SERVER['PHP_SELF']."?gid=".$_GET['gid']."&a=calendar&date=".mktime(0,0,0,$month -1,$d,$year)."\">$d</a>"; 
             echo "<td class='nonmonthdays' align='center'>$day_link</td>\n"; 
         } 
         if(($i >= $offset_count) && ($i < ($num_weeks * 7) - $outset)){ 
            $day_link = "<a href=\"".$_SERVER['PHP_SELF']."?gid=".$_GET['gid']."&a=calendar&date=".mktime(0,0,0,$month,$d,$year)."\">$d</a>"; 
           if($date == mktime(0,0,0,$month,$d,$year)){ 
		   
               echo "<td ".(in_array(mktime(0,0,0,$month,$d,$year),$dayEventList)?" class='todayE' onMouseOver=\"return overlib('".$dayEvents[mktime(0,0,0,$month,$d,$year)]."');\" onMouseOut=\"return nd();\" ":" class='today' ")." align='center'><a href=\"".$_SERVER['PHP_SELF']."?gid=".$_GET['gid']."&a=calendar&date=".mktime(0,0,0,$month,$d,$year)."\">$d</a></td>\n"; 
           } else { 
               echo "<td ".(in_array(mktime(0,0,0,$month,$d,$year),$dayEventList)?" class='daysE' onMouseOver=\"return overlib('".$dayEvents[mktime(0,0,0,$month,$d,$year)]."');\" onMouseOut=\"return nd();\"  ":" class='days' ")." align='center'>$day_link</td>\n"; 
           } 
        } elseif(($outset > 0)) { 
            if(($i >= ($num_weeks * 7) - $outset)){ 
               $day_link = "<a href=\"".$_SERVER['PHP_SELF']."?gid=".$_GET['gid']."&a=calendar&date=".mktime(0,0,0,$month +1,$d,$year)."\">$d</a>"; 
               echo "<td  class='nonmonthdays' align='center'>$day_link</td>\n"; 
           } 
        } 
        $i++; 
      } 
      echo "</tr>\n";    
} 

// Close out your table and that's it! 
echo '</table>'; 

}

}


?>