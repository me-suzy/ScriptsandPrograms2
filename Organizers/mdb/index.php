<?php
include "header.inc.php";


?>

<table width="95%" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
  <tr>
    <td><table width="100%" border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
        <tr> 
          <td><div align="center"><strong>Welcome to My DataBook, Your personal organizer and planner</strong></div><br>
            <table width="95%" border="0" align="center" cellpadding="4" cellspacing="1">
              <tr valign="top"> 
                <td width="175">
				  <table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr> 
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFCC">
                          <tr> 
                            <td><br>
							
							&#8226; <a href="contacts.php?Sec=contacts" class="link" onMOuseOver="this.style.color='#000000'" onMouseOut="this.style.color=''">Contacts</a><br>
							&nbsp;&#8226; <a href="add_contact.php?Sec=contacts" class="link" onMOuseOver="this.style.color='#000000'" onMouseOut="this.style.color=''">Add Contact</a><br>
							&nbsp;&#8226; <a href="mod_groups.php?Sec=contacts" class="link" onMOuseOver="this.style.color='#000000'" onMouseOut="this.style.color=''">Manage Groups</a><br>
							     <hr size="1">
							&#8226; <a href="schedule.php?Sec=schedule" class="link" onMOuseOver="this.style.color='#000000'" onMouseOut="this.style.color=''">Schedule</a><br>
							&nbsp;&#8226; <a href="dialy.php?Sec=schedule" class="link" onMOuseOver="this.style.color='#000000'" onMouseOut="this.style.color=''">Day Planner</a><br>
							&nbsp;&#8226; <a href="scheduled_notes.php?Sec=schedule" class="link" onMOuseOver="this.style.color='#000000'" onMouseOut="this.style.color=''">View scheduled notes</a><br>
							&nbsp;&#8226; <a href="add_scheduleed_note.php?Sec=schedule" class="link" onMOuseOver="this.style.color='#000000'" onMouseOut="this.style.color=''">Add Scheduled note</a><br>
							&nbsp;&#8226; <a href="appointments.php?Sec=schedule" class="link" onMOuseOver="this.style.color='#000000'" onMouseOut="this.style.color=''">View Appintments</a><br>
							&nbsp;&#8226; <a href="add_appointment.php?Sec=schedule" class="link" onMOuseOver="this.style.color='#000000'" onMouseOut="this.style.color=''">Add Appointments</a><br>
							     <hr size="1">
							&#8226; <a href="notes.php?Sec=notes" class="link" onMOuseOver="this.style.color='#000000'" onMouseOut="this.style.color=''">Notes</a><br>
							&nbsp;&#8226; <a href="add_note.php?Sec=notes" class="link" onMOuseOver="this.style.color='#000000'" onMouseOut="this.style.color=''">Add Note </a><br>
								<hr size="1"> 
                            &#8226; <a href="tasks.php?Sec=tasks" class="link" onMOuseOver="this.style.color='#000000'" onMouseOut="this.style.color=''">Tasks</a><br>
							&nbsp;&#8226; <a href="add_task.php?Sec=tasks" class="link" onMOuseOver="this.style.color='#000000'" onMouseOut="this.style.color=''">Add Task</a> <br>
								<hr size="1">
							&#8226; <a href="reminders.php?Sec=reminders" class="link" onMOuseOver="this.style.color='#000000'" onMouseOut="this.style.color=''">Reminders</a><br>
							&#8226; <a href="diary.php?Sec=diary" class="link" onMOuseOver="this.style.color='#000000'" onMouseOut="this.style.color=''">Diary </a><br>
							
							<br></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table>
                </td>
                <td><table width="98%" border="0" align="center" cellpadding="1" cellspacing="0">
                    <tr>
                      <td>

<?php
    # ********************************************************************************** 
    # ***  Set time stamp.Check for month requested(value from request form) ***********    
    # ***  If no value has been set then make it for current month           *********** 
    # ********************************************************************************** 
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



# *************************************************************************************** 
# ************ HTML code goes from here                                 ***************** 
# ************ First row in HTML table displays month and year          ***************** 
# ************ Second row is allotted for week days                     ***************** 
# ************ Table contains six more rows (total 42 table cells)      ***************** 
# *************************************************************************************** 
?> 
<table width=375 align="center"> 
<tr bgcolor="#003366"><td colspan=7> 
<table border=0 width="100%"> 
  <tr> 
    <td><font size=2 color="#ffffff"><b><?php echo "$month_full  $year" ; ?></b></font> 
    </td> 
    <td align="right"> 
       <? echo "<a href='index.php?Sec=home&req_month=$prev_month&year=$prev_year'><img src=images/pmonth.gif border=0></a>&nbsp;&nbsp;"; ?> 
       <? echo "<a href='index.php?Sec=home&req_month=$next_month&year=$next_year'><img src=images/nmonth.gif border=0></a>&nbsp;&nbsp;"; ?> 
    </td> 
  </tr> 
</table> 
</td></tr> 
<tr bgcolor="#6699FF"> 
<td width="40" class="cal" style="border-color='black'"><div align="center"><font color="#ffffff">Sun</font></div></td>
<td width="40" class="cal" style="border-color='black'"><div align="center"><font color="#ffffff">Mon</font></div></td>
<td width="40" class="cal" style="border-color='black'"><div align="center"><font color="#ffffff">Tue</font></div></td>
<td width="40" class="cal" style="border-color='black'"><div align="center"><font color="#ffffff">Wed</font></div></td>
<td width="40" class="cal" style="border-color='black'"><div align="center"><font color="#ffffff">Thu</font></div></td>
<td width="40" class="cal" style="border-color='black'"><div align="center"><font color="#ffffff">Fri</font></div></td>
<td width="40" class="cal" style="border-color='black'"><div align="center"><font color="#ffffff">Sat</font></div></td> 
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
   if($i==$week_day){$day=1;} 
   if($i<$week_day || $i>=$last_cell) 
   { 
         
     echo "<td>&nbsp;</td>"; 
      if($i%7==0){echo "</tr><tr>\n";}    
   } 
   else 
   { 
         
        if(($today_match)&& ($today_day==$day))           // if the day is today, paint it orange 
        { 
            echo "<td height=\"40\" class=\"cal\" style=\"border-color='red'\" onMouseOver=\"bgColor='#F0F0F0'; this.style.color='#000000'\" onMouseOut=\"bgColor=''; this.style.color='#000000'\"><strong>$day</strong></td>"; 
        } else { 
            echo "<td height=\"40\" class=\"cal\" onMouseOver=\"bgColor='#F0F0F0'; this.style.color='#000000'\" onMouseOut=\"bgColor=''; this.style.color='#000000'\">$day</td>"; 
        } 
             
        $day++; 
       if($i%7==0) { echo "</tr><tr>\n"; } 
          
   } 
} 
?> 
</table>



					  </td>
                    </tr>
                  </table></td>
              </tr>
            </table> </td>
        </tr>
      </table></td>
  </tr>
</table>

<?php


include "footer.inc.php";
?>