<?php
include "header.inc.php";

if(isset($today)) {
	$Qtime = strftime("%Y-%m-%d", $today);
	$today = strftime("%b %d, %Y", $today);

}


if(!isset($today)) {
	$Qtime = date("Y-m-d");
	$today = date("M j, Y");
}

$GetTasks = mysql_query("SELECT * FROM $Table_tasks WHERE due_date=\"$Qtime\" && completed=\"0\"ORDER BY T_ID DESC") or die(mysql_error());
$GetAppointments = mysql_query("SELECT * FROM $Table_appointments WHERE date=\"$Qtime\"") or die(mysql_error());
$GetNotes = mysql_query("SELECT * FROM $Table_scheduled_notes WHERE date=\"$Qtime\"") or die(mysql_error());


while ($AP = mysql_fetch_row($GetAppointments)) {

	if($AP[2]=="5:00 am") { 
		$T5 = $AP[3]; 
	}

	if($AP[2]=="6:00 am") { 
		$T6 = $AP[3]; 
	}

	if($AP[2]=="7:00 am") { 
		$T7 = $AP[3]; 
	}

	if($AP[2]=="8:00 am") { 
		$T8 = $AP[3]; 
	}

	if($AP[2]=="9:00 am") { 
		$T9 = $AP[3]; 
	}

	if($AP[2]=="10:00 am") {
		$T10 = $AP[3]; 
	}

	if($AP[2]=="11:00 am") { 
		$T11 = $AP[3]; 
	}

	if($AP[2]=="12:00 am") { 
		$T12 = $AP[3]; 
	}

	if($AP[2]=="1:00 pm") { 
		$T1P = $AP[3]; 
	}

	if($AP[2]=="2:00 pm") { 
		$T2P = $AP[3]; 
	}

	if($AP[2]=="3:00 pm") { 
		$T3P = $AP[3]; 
	}

	if($AP[2]=="4:00 pm") { 
		$T4P = $AP[3]; 
	}

	if($AP[2]=="5:00 pm") { 
		$T5P = $AP[3]; 
	}

	if($AP[2]=="6:00 pm") { 
		$T6P = $AP[3]; 
	}

	if($AP[2]=="7:00 pm") { 
		$T7P = $AP[3]; 
	}

	if($AP[2]=="8:00 pm") { 
		$T8P = $AP[3]; 
	}

	if($AP[2]=="9:00 pm") { 
		$T9P = $AP[3]; 
	}

	if($AP[2]=="10:00 pm") { 
		$T10P = $AP[3]; 
	}
}


echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > Day Planner<br><br>");
?>

<table width="95%" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
  <tr>
    <td><table width="100%" border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
          <td><img src="images/myday.gif" width="200" height="50"><br><br>
            <table width="95%" border="0" align="center" cellpadding="3" cellspacing="0">
              <tr>
                <td valign="top">
				  <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                      <td width="41"><img src="images/tablet_r1_c1.gif" width="41" height="81"></td>
                      <td background="images/tablet_r1_c2.gif" class="BottomBorderB"><font size="3"><strong>Today's Schedule </strong><br></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em><?= $today; ?></em></td>
                      <td width="48"><img src="images/tablet_r1_c3.gif" width="48" height="81"></td>
                    </tr>
                    <tr valign="top"> 
                      <td width="41" background="images/tablet_r2_c1.gif" valign="bottom"> 
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><img src="images/tablet_r2_c1.gif" width="41" height="212"></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                        </table><img src="images/tablet_r3_c1.gif" width="41" height="64"></td>
                      <td background="images/tablet_r2_c2.gif"><table width="100%" border="0" cellspacing="1" cellpadding="2">
                          <tr> 
                            <td width="22"><div align="center"><strong>5<font size="1">am</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T5; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>6<font size="1">am</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T6; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>7<font size="1">am</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T7; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>8<font size="1">am</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T8; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>9<font size="1">am</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T9; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>10<font size="1">am</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T10; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>11<font size="1">am</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T11; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>12<font size="1">pm</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T12; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>1<font size="1">pm</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T1P; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>2<font size="1">pm</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T2P; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>3<font size="1">pm</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T3P; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>4<font size="1">pm</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T4P; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>5<font size="1">pm</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T5P; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>6<font size="1">pm</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T6P; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>7<font size="1">pm</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T7P; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>8<font size="1">pm</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T8P; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>9<font size="1">pm</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T9P; ?></td>
                          </tr>
                          <tr> 
                            <td width="22"><div align="center"><strong>10<font size="1">pm</font></strong></div></td>
                            <td class="BottomBorderB">&nbsp;<?= $T10P; ?></td>
                          </tr>
                        </table></td>
                      <td width="48" background="images/tablet_r2_c3.gif">&nbsp;</td>
                    </tr>
                    <tr>
                      <td width="41"><img src="images/tablet_r4_c1.gif" width="41" height="43"></td>
                      <td background="images/tablet_r4_c2.gif">&nbsp;</td>
                      <td width="48"><img src="images/tablet_r4_c3.gif" width="48" height="43"></td>
                    </tr>
                  </table>
                </td>
                <td width="250" valign="top"><table width="95%" border="0" align="center" cellpadding="2" cellspacing="1">
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr> 
                      <td align="center">
					  
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
<table width=250> 
<tr bgcolor="#003366"><td colspan=7> 
<table border=0 width="100%"> 
  <tr> 
    <td><font size=2 color="#ffffff"><b><?php echo "$month_full  $year" ; ?></b></font> 
    </td> 
    <td align="right"> 
       <? echo "<a href='dialy.php?Sec=schedule&req_month=$prev_month&year=$prev_year'><img src=images/pmonth.gif border=0></a>&nbsp;&nbsp;"; ?> 
       <? echo "<a href='dialy.php?Sec=schedule&req_month=$next_month&year=$next_year'><img src=images/nmonth.gif border=0></a>&nbsp;&nbsp;"; ?> 
    </td> 
  </tr> 
</table> 
</td></tr> 
<tr bgcolor="#6699FF"> 
<td width="35" class="cal" style="border-color='black'"><div align="center"><font color="#ffffff">Sun</font></div></td>
<td width="35" class="cal" style="border-color='black'"><div align="center"><font color="#ffffff">Mon</font></div></td>
<td width="35" class="cal" style="border-color='black'"><div align="center"><font color="#ffffff">Tue</font></div></td>
<td width="35" class="cal" style="border-color='black'"><div align="center"><font color="#ffffff">Wed</font></div></td>
<td width="35" class="cal" style="border-color='black'"><div align="center"><font color="#ffffff">Thu</font></div></td>
<td width="35" class="cal" style="border-color='black'"><div align="center"><font color="#ffffff">Fri</font></div></td>
<td width="35" class="cal" style="border-color='black'"><div align="center"><font color="#ffffff">Sat</font></div></td> 
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
			$Var = mktime(0,0,0,$month,$day,$year);

            echo "<td height=\"20\" class=\"cal\" onClick=\"window.location='dialy.php?Sec=schedule&today=$Var&req_month=$req_month&req_year=$req_year'\" style=\"border-color='red'; cursor:hand\" onMouseOver=\"bgColor='#F0F0F0'; this.style.color='#000000'\" onMouseOut=\"bgColor=''; this.style.color='#000000'\"><strong>$day</strong></td>"; 
        } else { 
			$Var = mktime(0,0,0,$month,$day,$year);

            echo "<td height=\"20\" class=\"cal\" onClick=\"window.location='dialy.php?Sec=schedule&today=$Var&req_month=$req_month&req_year=$req_year'\" style=\"cursor:hand\" onMouseOver=\"bgColor='#F0F0F0'; this.style.color='#000000'\" onMouseOut=\"bgColor=''; this.style.color='#000000'\">$day</td>"; 
        } 
             
        $day++; 
       if($i%7==0) { echo "</tr><tr>\n"; } 
          
   } 
} 
?> 
</table>					  
					  </td>
                    </tr>
                    <tr> 
                      <td>

			<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0">
              <tr>
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr>
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                          <tr>
                            <td class="Title" style="padding='3'"><div align="center"><strong>Today's Tasks</strong></div></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

<?php
	$Count = mysql_num_rows($GetTasks);

	if ($Count == 0) {
		  ?>

		  <tr>
			<td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
				<tr>
				  <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFCC">
					  <tr>
						<td><div align="center">No Tasks setup for today</div></td>
					  </tr>
					</table></td>
				</tr>
			  </table></td>
		  </tr>

		  <?php
	}
	
	else {
		while ($A=mysql_fetch_object($GetTasks)){
				  ?>

				  <tr>
					<td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
						<tr>
						  <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFCC">
							  <tr>
								<td><img src="images/icon13.gif" width="15" height="15">&nbsp;<a href="tasks.php?Sec=tasks&View=<?= $A->T_ID; ?>" class="UpLink" onMouseOver="this.style.color='#FF0000'" onMouseOut="this.style.color='#000000'"><?= $A->title; ?></a></td>
							  </tr>
							</table></td>
						</tr>
					  </table></td>
				  </tr>

				  <?php
	}

	}


?>



						</table>
					  </td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					</tr>
                    <tr>
                      <td>

			<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0">
              <tr>
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr>
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                          <tr>
                            <td class="Title" style="padding='3'"><div align="center"><strong>Today's Notes</strong></div></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

<?php
	$Count = mysql_num_rows($GetNotes);

	if ($Count == 0) {
		?>

              <tr>
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr>
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFCC">
                          <tr>
                            <td><div align="center">No scheduled notes found</div></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>

		<?php
	}

	else {
		while ($N=mysql_fetch_object($GetNotes)) {


				?>

				  <tr>
					<td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
						<tr>
						  <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFCC">
							  <tr>
								<td><img src="images/star.gif">&nbsp;<?= $N->note; ?></td>
							  </tr>
							</table></td>
						</tr>
					  </table></td>
				  </tr>

				<?php
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