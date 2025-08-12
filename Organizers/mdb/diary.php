<?php

if(isset($Mod)) {
	if ($Mod == "yes") {
		if ($entry == "" || $entry == " ") {
			include "header.inc.php";
				echo ("<p>&nbsp;</p><p align=center>You entered an empty entry into the database.<br>Please make some type of note or we will have to reject your diary entry.</p><p align=center><a href=\"$HTTP_REFERER\">Go Back</a></p>");
			include "footer.inc.php";
			exit;
		}

		else {
			include "data.inc.php";
			$CONNECT = mysql_connect($DB_host, $DB_user, $DB_pass) or die(Mysql_error());
			mysql_select_db($DB_name);

			$Mdate = date("Y-m-d h:i:s");
			$entry = stripslashes(htmlspecialchars($entry));

			$Update = mysql_query("INSERT INTO $Table_diary SET date=\"$Mdate\", entry=\"$entry\"") or die(mysql_error());

			mysql_close($CONNECT);
			header("location: $HTTP_REFERER");
		}
	}
}


include "header.inc.php";

if(!$year){
	$year=date("Y"); 
}

if(!$req_month || $req_month=="none") { 
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

         $month=date("n",$dt_and_tm); 
         $week_day=date("w",$dt_and_tm)+1;      
          
          
if($month==1 || $month==3 || $month==5 || $month==7 || $month==8 || $month==10 || $month==12) 
{     
   $no_of_days=31;     
} 
elseif($month==4 || $month==6 || $month==9 || $month==11) 
{   
   $no_of_days=30; 
}   

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

if (isset($Day)) {
	$Query = mysql_query("SELECT *,date_format(date, '%M, %d, %Y (%r)')as date FROM $Table_diary WHERE DAYOFMONTH(date)=$Day && MONTH(date)=$month && YEAR(date)=$year ORDER BY D_ID DESC,date DESC") or die(mysql_error());
}

else if (isset($M)) {
	$Query = mysql_query("SELECT *,date_format(date, '%M, %d, %Y (%r)')as date FROM $Table_diary WHERE MONTH(date)=$month && YEAR(date)=$year ORDER BY D_ID DESC,date DESC") or die(mysql_error());
}

else {
	$Query = mysql_query("SELECT *,date_format(date, '%M, %d, %Y ( %r )')as date FROM $Table_diary WHERE MONTH(date)=$month && YEAR(date)=$year ORDER BY  D_ID DESC,date DESC") or die(mysql_error());
}

$QueryAll = mysql_query("SELECT * FROM $Table_diary") or die(mysql_error());
$CountedEntries = mysql_num_rows($QueryAll);

echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > Diary<br><br>");

?>

<table width="95%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000" align="center">
  <tr>
    <td>
	  <table width="100%" border="0" cellpadding="10" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
          <td> 
            <table width="95%" border="0" align="center" cellpadding="1" cellspacing="1">
              <tr> 
                <td><img src="images/journal.gif"><div align="center"><br>
				
					<table width="600" border="0" align="center" cellpadding="1" cellspacing="0">
					  <tr>
						<td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
							<tr>
							  <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#003366">
								  <tr>
									<td><table width="100%" border="0" cellspacing="0" cellpadding="1">
										<tr>
										  <td width="75%"><font size=1 color="#ffffff"><strong>&nbsp;&nbsp;<?php echo "$month_full  $year"; ?></strong></font></td>
										  <td><div align="right">
											<? echo "<a href='diary.php?Sec=diary&req_month=$prev_month&year=$prev_year&M=$prev_month'><img src=images/pmonth.gif border=0 width=\"15\" hieght=\"15\" alt=\"Prev Month\"></a>&nbsp;&nbsp;"; ?> 
											<? echo "<a href='diary.php?Sec=diary&req_month=$next_month&year=$next_year&M=$next_month'><img src=images/nmonth.gif border=0 width=\"15\" hieght=\"15\" alt=\"Next Month\"></a>&nbsp;&nbsp;"; ?>
										  </div></td>
										</tr>
									  </table></td>
								  </tr>
								</table></td>
							</tr>
						  </table>
						  
						  </td>
					  </tr>
					  </table>
					 <table width="615">
					  <tr>
						<td>

					<?php 
					$last_cell=$week_day+$no_of_days;       
					for($i=1;$i<=42;$i++) 
					{ 
					   if($i==$week_day){$day=1;} 
					   if($i<$week_day || $i>=$last_cell) 
					   {  }

					   else 
					   { 
							 
							if(($today_match)&& ($today_day==$day))           
							{ 
								echo "<td width=\"20\" class=\"cal\" style=\"border-color='red'; cursor:hand\" onClick=\"window.location='diary.php?Sec=diary&req_month=$month&year=$year&Day=$day'\" onMouseOver=\"bgColor='#F0F0F0'; this.style.color='#000000'\" onMouseOut=\"bgColor=''; this.style.color='#000000'\"><strong>$day</strong></td>"; 
							} else { 
								echo "<td width=\"20\" style=\"cursor:hand\" class=\"cal\" onClick=\"window.location='diary.php?Sec=diary&req_month=$month&year=$year&Day=$day'\" onMouseOver=\"bgColor='#F0F0F0'; this.style.color='#000000'\" onMouseOut=\"bgColor=''; this.style.color='#000000'\">$day</td>"; 
							} 
								 
							$day++; 
							  
					   } 
					} 

					?>
						</td>
					  </tr>
					</table>				
				
				</div></td>
              </tr>
              <tr> 
                <td height="3"></td>
              </tr>
              <tr> 
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr> 
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                          <tr> 
                            <td class="Title" style="padding='3'">&nbsp; This month</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>


						<?php
							$Count = mysql_num_rows($Query);

							if ($Count == 0) {
								 echo "<tr><td><div align=center style=\"padding='2'\"><strong>No entries found</strong></div></td></tr>";
							}

							else if ($Count != 0) {
								while ($Row = mysql_fetch_object($Query)) {

									if (isset($View)) {
										if ($Row->D_ID == $View) {
											$Get = mysql_query("SELECT * FROM $Table_diary WHERE D_ID=$View") or die(mysql_error());
											$Info = mysql_fetch_object($Get);

											$Entry = nl2br($Row->entry);

											$Print = "<tr><td align=center><table width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n<tr>\n<td width=\"51\"><img src=\"images/paper_r1_c1.gif\" width=\"51\" height=\"55\"></td>\n<td background=\"images/paper_r1_c2.gif\"><font size=3><strong>$Row->date</strong></font></td>\n<td width=\"27\"><img src=\"images/paper_r1_c3.gif\" width=\"27\" height=\"55\"></td>\n</tr>\n<tr valign=\"top\">\n<td background=\"images/paper_r2_c1.gif\">&nbsp;</td>\n<td background=\"images/paper_r2_c2.gif\">\n<p><font size=3>$Entry</font></p>\n<p>&nbsp;</p></td>\n<td background=\"images/paper_r2_c3.gif\">&nbsp;</td>\n</tr>\n<tr>\n<td><img src=\"images/paper_r3_c1.gif\" width=\"51\" height=\"25\"></td>\n<td background=\"images/paper_r3_c2.gif\">&nbsp;</td>\n<td><img src=\"images/paper_r3_c3.gif\" width=\"27\" height=\"25\"></td>\n</tr>\n</table>\n</td></tr>";

											$img = "opened";
											$Link = "diary.php?Sec=diary&req_month=$req_month&year=$year&M=$month";
										}

										if ($Row->D_ID != $View) {
											$img = "closed";
											$Link = $PHP_SELF . "?" . $_SERVER['QUERY_STRING'] . "&View=$Row->D_ID";
										}
									}

									if(!isset($View)) {
										$img = "closed";
										$Link = $PHP_SELF . "?" . $_SERVER['QUERY_STRING'] . "&View=$Row->D_ID";
									}

											?>

										  <tr> 
											<td>
											  <table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
												<tr> 
												  <td>
													 <table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFCC">
													  <tr> 
														<td valign="bottom"><a href="<?= $Link; ?>"><img src="images/<?= $img; ?>.gif" width="11" height="11" alt="Expand/Collapse" border="0"></a>&nbsp;&nbsp;<a href="<?= $Link; ?>" class="MiniLink"><font size="1" color="#000000"><?= $Row->date; ?></font></a></td>
													  </tr>
													  <?= $Print; ?>
													 </table>
													</td>
												</tr>
											  </table>
											  </td>
										  </tr>
							  
									<?php
									$Print = "";
									if ($img == "opened") {
										$img = "closed";
									}
								}
							}

						?>

              <tr>
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr> 
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                          <tr> 
                            <td><font color="#FFFFFF" size="-2">&nbsp; Total entries in database: <?= $CountedEntries; ?></font></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
			  <tr><td>&nbsp;</td></tr>
			  <tr><td>&nbsp;</td></tr>
			  <tr><td align="center"><p><font size=3><strong>Add new entry</strong></font></p></td></tr>
			  <tr><td><div align="center">

				<form name="form1" method="post" action="">
				<input type="hidden" name="Mod" value="yes">
				  <textarea name="entry" cols="60" rows="8" id="entry"></textarea>
				  <br>
				  <input type="submit" name="Submit" value="  Add Entry  ">
				</form>
				
				</td></tr>
            </table>
          </td>
        </tr>
      </table></td>
  </tr>
</table>

<?php

include "footer.inc.php";
?>