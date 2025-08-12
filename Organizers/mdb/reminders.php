<?php
if (isset($Mod)) {
	include "data.inc.php";
	$CONNECT = mysql_connect($DB_host, $DB_user, $DB_pass) or die(Mysql_error());
	mysql_select_db($DB_name);

	$Delete = mysql_query("DELETE FROM $Table_reminders WHERE R_ID=\"$ID\"") or die(mysql_error());

	mysql_close($CONNECT);
	header("location: $HTTP_REFERER");
}

if (isset($Submit)) {
	include "data.inc.php";
	$CONNECT = mysql_connect($DB_host, $DB_user, $DB_pass) or die(Mysql_error());
	mysql_select_db($DB_name);

	$Mdate = $year . "-" . $month . "-" . $day;
	$contents = stripslashes(htmlspecialchars($contents));
	$subject = stripslashes(htmlspecialchars($subject));

	$Update = mysql_query("INSERT INTO $Table_reminders SET date=\"$Mdate\", subject=\"$subject\", message=\"$contents\", status=\"0\"") or die(mysql_error());

	mysql_close($CONNECT);
	header("location: $HTTP_REFERER");
	
}

include "header.inc.php";

$GetReminder = mysql_query("SELECT subject,R_ID,status,message,date_format(date, '%M, %d, %Y')as date FROM $Table_reminders ORDER BY R_ID DESC") or die(mysql_error());

echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > Reminders<br><br>");

?>

<table width="650" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
  <tr>
    <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
          <td><table width="100%" border="0" cellspacing="2" cellpadding="1">
              <tr> 
                <td><table width="100%" border="0" cellspacing="2" cellpadding="1">
                    <tr valign="top"> 
                      <td width="250"><?php include "cal.php"; ?><br>
					  
						<table width="225" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
						  <tr>
							<td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#E3E3E3">
								<tr>
								  <td><img src="images/warning.gif" width="16" height="16"> 
									<font size="1"><strong>Cron Required for eMail reminder</strong><br>
									&nbsp;&nbsp;In order for you ro get the reminder sent to you via eMail you must 
									have a cron job running. Please refer to the help section for more 
									information.</font></td>
								</tr>
							  </table></td>
						  </tr>
						</table>
					  
					  </td>
                      <td>

						<table width="325" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
						  <tr>
							<td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#F0F0F0">
								<tr>
								  <td><form name="form1" method="post" action="">
									  <table width="100%" border="0" cellspacing="1" cellpadding="1">
										<tr> 
										  <td><div align="center"><font size="3"><strong>Add Reminder</strong></font></div></td>
										</tr>
										<tr> 
										  <td><div align="center">Date:<br>
											  <select name="month" id="select" class="select">
												<OPTION VALUE=01>January
												<OPTION VALUE=02>February
												<OPTION VALUE=03>March
												<OPTION VALUE=04>April
												<OPTION VALUE=05>May
												<OPTION VALUE=06>June
												<OPTION VALUE=07>July
												<OPTION VALUE=08>August
												<OPTION VALUE=09>September
												<OPTION VALUE=10>October
												<OPTION VALUE=11>November
												<OPTION VALUE=12>December
											  </select>
											  <select name="day" id="select2" class="select">
												<OPTION VALUE=01>1
												<OPTION VALUE=02>2
												<OPTION VALUE=03>3
												<OPTION VALUE=04>4
												<OPTION VALUE=05>5
												<OPTION VALUE=06>6
												<OPTION VALUE=07>7
												<OPTION VALUE=08>8
												<OPTION VALUE=09>9
												<OPTION>10
												<OPTION>11
												<OPTION>12
												<OPTION>13
												<OPTION>14
												<OPTION>15
												<OPTION>16
												<OPTION>17
												<OPTION>18
												<OPTION>19
												<OPTION>20
												<OPTION>21
												<OPTION>22
												<OPTION>23
												<OPTION>24
												<OPTION>25
												<OPTION>26
												<OPTION>27
												<OPTION>28
												<OPTION>29
												<OPTION>30
												<OPTION>31
											  </select>
											  <select name="year" id="select3" class="select">
												<option value="2003">2003</option>
												<option value="2004">2004</option>
												<option value="2005">2005</option>
											  </select>
											</div></td>
										</tr>
										<tr> 
										  <td><div align="center">Subject Name:<br>
											  <input name="subject" type="text" id="subject3" size="40" maxlength="48">
											</div></td>
										</tr>
										<tr> 
										  <td><div align="center">Reminder:<br>
											  <textarea name="contents" cols="32" rows="6" id="textarea"></textarea>
											</div></td>
										</tr>
										<tr> 
										  <td><div align="center"> 
											  <input type="submit" name="Submit" value="  Set Reminder  ">
											</div></td>
										</tr>
									  </table>
									</form></td>
								</tr>
							  </table></td>
						  </tr>
						</table>

					 </td>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td>

					<table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
					  <tr>
						<td><table width="100%" border="0" cellpadding="1" cellspacing="1" bgcolor="#F0F0F0">
							<tr> 
							  <td><strong>&raquo; Current Reminders</strong></td>
							</tr>
							<tr>
							  <td><table width="98%" border="0" align="center" cellpadding="3" cellspacing="1">
								  <tr> 
									<td width="100" bgcolor="#6699FF" class="Title"> <div align="left"> &nbsp;Scheduled</div></td>
									<td width="150" bgcolor="#6699FF" class="Title"> <div align="left">&nbsp;Subject</div></td>
									<td bgcolor="#6699FF" class="Title"> <div align="left">&nbsp;Message</div></td>
									<td width="16"> 
									  <div align="center"></div></td>
								  </tr>

								<?php
									$Count = mysql_num_rows($GetReminder);
									if ($Count == 0) {

										?>
										  <tr  bgcolor="#FFFFFF" onMouseOver="bgColor='#FFFFCC'" onMouseOut="bgColor='#FFFFFF'"> 
											<td valign="top" colspan="3"><div align="center"><strong>You have no reminders setup</strong></div></td>
										  </tr>
										<?php

									}
									
									while ($Q = mysql_fetch_object($GetReminder)) {
										if ($Q->status == 1) {
											$done = "<br><font color=\"red\"><strong>Notified</strong></font>";
										}

										else if ($Q->status != 1) {
											$done = "";
										}

										?>
										  <tr  bgcolor="#FFFFFF" onMouseOver="bgColor='#FFFFCC'" onMouseOut="bgColor='#FFFFFF'"> 
											<td valign="top"><div align="center"><?= $Q->date; ?><?= $done; ?></div></td>
											<td valign="top"><?= $Q->subject; ?></td>
											<td valign="top"><?= nl2br($Q->message); ?></td>
											<td valign="top" bgcolor="#F0F0F0"><div align="center"><a href="<?= $PHP_SELF; ?>?Sec=reminders&Mod=0&ID=<?= $Q->R_ID; ?>"><img src="images/delete.gif" width="16" height="16" border="0" alt="Delete Reminder"></a></div></td>
										  </tr>
										<?php
									}
								?>

								  <tr> 
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
									<td>&nbsp;</td>
								  </tr>
								</table></td>
							</tr>
						  </table></td>
					  </tr>
					</table>

				</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>

<?php

include "footer.inc.php";
?>