<?php
if ($Submit) {
	if ($title == "" || $title == " " || $task == "" || $task == " ") {
		include "header.inc.php";
			echo ("<p>&nbsp;</p><p align=center>You entered an empty entry into the database.<br>Please make sure you enter a title and task or we will have to reject your task entry.</p><p align=center><a href=\"$HTTP_REFERER\">Go Back</a></p>");
		include "footer.inc.php";
		exit;
	}

	else {
		include "data.inc.php";
		$CONNECT = mysql_connect($DB_host, $DB_user, $DB_pass) or die(Mysql_error());
		mysql_select_db($DB_name);


		$Mdate = date("Y-m-d");
		$task = stripslashes(htmlspecialchars($task));
		$title = stripslashes(htmlspecialchars($title));
		$Ddate = $year . "-" . $month . "-" . $day;

		if ($email == 1) {
			$subject = "Reminder: " . $title;
			$remind = $title . " is due today!\n\rHere is what you have,\n\r\n\r" . $task;
			$Add = mysql_query("INSERT INTO $Table_reminders SET date=\"$Ddate\", subject=\"$subject\", message=\"$remind\", status=\"0\"") or die();
		}

		$Insert = mysql_query("INSERT INTO $Table_tasks SET due_date=\"$Ddate\", inserted=\"$Mdate\", priority=\"$priority\", title=\"$title\", task=\"$task\", completed=\"0\", email=\"$email\"") or die(mysql_error());

		mysql_close($CONNECT);
		header("location: tasks.php?Sec=tasks");
	}
}

include "header.inc.php";
echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > <a href=\"tasks.php?Sec=tasks\">Tasks</a> > Add task<br><br>");
?>

<table width="98%" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
  <tr>
    <td><table width="100%" border="0" cellpadding="8" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
              <tr> 
                <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
                    <tr> 
                      <td width="50%"><img src="images/mytasks.gif" width="200" height="50"></td>
                      <td>&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
              <tr> 
                <td>&nbsp;</td>
              </tr>
              <tr> 
                <td><div align="center"> 
                    <table width="90%" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
                      <tr> 
                        <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                            <tr> 
                              <td class="Title" style="padding='3'"><div align="center">Add new task</div></td>
                            </tr>
                          </table></td>
                      </tr>
                    </table>
                  </div></td>
              </tr>
              <tr> 
                <td><form name="form1" method="post" action="">
                    <table width="90%" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
                      <tr> 
                        <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFCC">
                            <tr> 
                              <td><table width="98%" border="0" align="center" cellpadding="1" cellspacing="0">
                                  <tr> 
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr> 
                                    <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                        <tr> 
                                          <td width="125">Priority:</td>
                                          <td><select name="priority" id="priority">
                                              <option value="2">High</option>
                                              <option value="1">Medium</option>
                                              <option value="0">Low</option>
                                            </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;eMail Reminder: <input name="email" type="checkbox" id="email" value="1" class="select"></td>
                                        </tr>
                                        <tr> 
                                          <td width="125">Title:</td>
                                          <td><input name="title" type="text" id="title" size="60"></td>
                                        </tr>
                                        <tr valign="top"> 
                                          <td width="125">Task:</td>
                                          <td><textarea name="task" cols="50" rows="12" id="task"></textarea></td>
                                        </tr>
                                      </table></td>
                                  </tr>
								  <tr>
								    <td>

											<div align="center">Date Due: 
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
											  
											</div>					
									</td>
								  </tr>
                                  <tr> 
                                    <td>&nbsp;</td>
                                  </tr>
								  <tr>
                                    <td><div align="center">
                                        <input type="submit" name="Submit" value="  Add Task  ">
                                      </div></td>
                                  </tr>
                                  <tr> 
                                    <td>&nbsp;</td>
                                  </tr>
                                </table></td>
                            </tr>
                          </table></td>
                      </tr>
                    </table>
                  </form></td>
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