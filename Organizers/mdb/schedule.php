<?php 
include "header.inc.php";

if(!$year){$year=date("Y"); } 
if(!$req_month || $req_month=="none") 
{ 
 $req_month=date("n"); 
 
} 
$dt_and_tm=mktime(0,0,0,$req_month,1,$year);   
       
// what are the months and year immediately ahead and behind the current one? 
$prev_result = getPrevMonth($req_month, $year); 
$prev_month = substr($prev_result, 0, 2); 
$prev_year = substr($prev_result, 2); 

$next_result = getNextMonth($req_month, $year); 
$next_month = substr($next_result, 0, 2); 
$next_year = substr($next_result, 2); 


function getPrevMonth($this_month, $this_year) 
{ 
    if ($this_month==1) 
    { 
        $pmonth = 12; 
        $pyear = $this_year - 1; 
                 
    } else 
    { 
        $pmonth = $this_month - 1; 
        $pyear = $this_year; 
         
    } 
    return str_pad($pmonth, 2, "0", STR_PAD_LEFT).$pyear; 
           
} 

function getNextMonth($this_month, $this_year) 
{ 
    if ($this_month==12) 
    { 
        $nmonth = 1; 
        $nyear = $this_year + 1; 
    } else 
    { 
        $nmonth = $this_month + 1; 
        $nyear = $this_year; 
         
    } 
    return str_pad($nmonth, 2, "0", STR_PAD_LEFT).$nyear; 
           
} 

    # *********************************************************************************** 
    # *** Find out the month number from the time stamp we just created     ************* 
    # *** Find out the day of the week from the same(0 to 6 for Sunday to   ************* 
    # *** Saturday) add "1" so that it becomes 1 to 7                       ************* 
    # ************************************************************************************* 
                         
         $month=date("n",$dt_and_tm); 
         $week_day=date("w",$dt_and_tm)+1;      
          
          
   # ************************************************************************************ 
   # *** Set number days in requested month                                 ************* 
   # ************************************************************************************                 
if($month==1 || $month==3 || $month==5 || $month==7 || $month==8 || $month==10 || $month==12) 
{     
   $no_of_days=31;     
} 
elseif($month==4 || $month==6 || $month==9 || $month==11) 
{   
   $no_of_days=30; 
}   
  # ************************************************************************************** 
  # *** If the month requested is Feb. Check whether it is Leap Year        ************** 
  # ************************************************************************************** 
elseif($month==2) 
{   
                   if(date("L",$dt_and_tm)) 
                   { $no_of_days=29 ;} 
                   else 
                   {$no_of_days=28;} 
} 
   $month_full=date("F",$dt_and_tm); 

// Is there a match in the calendar for today? 

$today_year = date(Y); 
$today_month = date(n);     
if ($today_year==$year && $today_month==$month) 
{ 
    $today_day = date(j); 
    $today_match = "true"; 
     
} else { 
    $today_match = "false"; 
} 

echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > Schedule<br><br>");

?>

<table width="95%" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
  <tr>
    <td><table width="100%" border="0" cellpadding="2" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
          <td><img src="images/myschedule.gif"><br><br>
<table width="97%" align="center"> 
<tr bgcolor="#003366"><td colspan=7> 
<table border=0 width="100%"> 
  <tr> 
    <td><font size=3 color="#ffffff"><b><?php echo "$month_full  $year" ; ?></b></font> 
    </td> 
    <td align="right"> 
       <? echo "<a href='schedule.php?Sec=schedule&req_month=$prev_month&year=$prev_year'><img src=images/pmonth.gif border=0></a>&nbsp;&nbsp;"; ?> 
       <? echo "<a href='schedule.php?Sec=schedule&req_month=$next_month&year=$next_year'><img src=images/nmonth.gif border=0></a>&nbsp;&nbsp;"; ?> 
    </td> 
  </tr> 
</table> 
</td></tr> 
<tr bgcolor="#6699FF"> 
<td width="75" class="Title" style="border-color='black'"><div align="center"><font color="#ffffff">Sunday</font></div></td>
<td width="75" class="Title" style="border-color='black'"><div align="center"><font color="#ffffff">Monday</font></div></td>
<td width="75" class="Title" style="border-color='black'"><div align="center"><font color="#ffffff">Tuesday</font></div></td>
<td width="75" class="Title" style="border-color='black'"><div align="center"><font color="#ffffff">Wednesday</font></div></td>
<td width="75" class="Title" style="border-color='black'"><div align="center"><font color="#ffffff">Thursday</font></div></td>
<td width="75" class="Title" style="border-color='black'"><div align="center"><font color="#ffffff">Friday</font></div></td>
<td width="75" class="Title" style="border-color='black'"><div align="center"><font color="#ffffff">Saturday</font></div></td> 
</tr> 
<tr> 
<?php 
# ************************************************************************************** 
# *** We need to start form week day and print total number of days    ***************** 
# *** in this month.For that we need to find out the last cell.        ***************** 
# *** While looping end current row and start new row for each 7 cells ***************** 
# ************************************************************************************** 
$last_cell=$week_day+$no_of_days;       
for($i=1;$i<=42;$i++) 
{ 

if($day == "") {
	$day = "1";
}

$Date = $year . "-" . $month . "-" . $day;

$QueryTask = mysql_query("SELECT * FROM $Table_tasks WHERE due_date=\"$Date\" && completed=\"0\"") or die(mysql_error());
$Task_count = mysql_num_rows($QueryTask);

$QueryApp = mysql_query("SELECT * FROM $Table_appointments WHERE date=\"$Date\"") or die(mysql_error());
$QueryNotes = mysql_query("SELECT * FROM $Table_scheduled_notes WHERE date=\"$Date\"") or die(mysql_error());

if ($Task_count != 0) {
	while ($TaskArray = mysql_fetch_object($QueryTask)) {
		$Pop_title = "<strong>Task: </strong>" . addslashes($TaskArray->title) . "<br>" . addslashes($TaskArray->task);
		$Pop_title = ereg_replace("\r\n","",$Pop_title);
		$TaskOne = substr($TaskArray->title, 0, 9);
		$Task .= "<a href=\"tasks.php?Sec=tasks&View=$TaskArray->T_ID\" onmouseover=\"return overlib('$Pop_title');\" onmouseout=\"return nd();\">" . $TaskOne . " ...</a><br>";
	}
}

	while ($AppArray = mysql_fetch_object($QueryApp)) {
		$Pop_app = "<strong>Appointment: </strong>" . addslashes($AppArray->notes);
		$Pop_app = ereg_replace("\r\n","",$Pop_app);
		$AppOne = substr($AppArray->type, 0, 9);
		$App .= "<a href=\"javascrip:void(0)\" onmouseover=\"return overlib('$Pop_app');\" onmouseout=\"return nd();\" style=\"color='#FF0000'\">" . $AppOne . "</a><br>";
	}

	while ($NotesArray = mysql_fetch_object($QueryNotes)) {
		$Pop_note = "<strong>Note: </strong>" . addslashes($NotesArray->note);
		$Pop_note = ereg_replace("\r\n","",$Pop_note);
		$NoteOne = substr($NotesArray->note, 0, 9);
		$Note .= "<a href=\"javascript:void(0)\" onmouseover=\"return overlib('$Pop_note');\" onmouseout=\"return nd();\" style=\"color='#339933'\">" . $NoteOne . " ...</a><br>";
	}

   if($i==$week_day){$day=1;} 
   if($i<$week_day || $i>=$last_cell) 
   { 
        
     echo "<td height=\"75\" bgcolor=\"#F0F0F0\">&nbsp;</td>"; 
      if($i%7==0){echo "</tr><tr>\n";}    
   } 
   else 
   { 
         
        if(($today_match)&& ($today_day==$day))
        { 
			$Var = mktime(0,0,0,$month,$day,$year);

            echo "<td width=\"75\" class=\"calBig\" style=\"border-color='red'\" onMouseOver=\"bgColor='#FFFFCC'; this.style.color='#000000'\" onMouseOut=\"bgColor=''; this.style.color='#000000'\" height=\"75\"><strong><a href=\"dialy.php?Sec=schedule&today=$Var\" style=\"color='#000000'\"><strong>$day</strong></a></strong><br><div align=\"left\"><font size=1>$Task $App $Note</font></div></td>"; 
        } else {
			$Var = mktime(0,0,0,$month,$day,$year);

            echo "<td width=\"75\" class=\"calBig\" onMouseOver=\"bgColor='#FFFFCC'; this.style.color='#000000'\" onMouseOut=\"bgColor=''; this.style.color='#000000'\" height=\"75\"><a href=\"dialy.php?Sec=schedule&today=$Var\" style=\"color='#000000'\"><strong>$day</strong></a></strong><br><div align=\"left\"><font size=1>$Task $App $Note</font></div></td>"; 
        } 
             
        $day++; 
       if($i%7==0) { echo "</tr><tr>\n"; } 
          
   } 
$Note = "";
$App = "";
$Task = "";
} 

echo ("</table></td></tr></table></td></tr></table>");

include "footer.inc.php";
?> 