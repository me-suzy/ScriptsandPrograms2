<?php

if ($Submit) {
	include "data.inc.php";
	$CONNECT = mysql_connect($DB_host, $DB_user, $DB_pass) or die(Mysql_error());
	mysql_select_db($DB_name);

	$notes = stripslashes(htmlspecialchars($notes));
	$date = $year . "-" . $month . "-" . $day;

	$insert = mysql_query("INSERT INTO $Table_scheduled_notes SET date=\"$date\", note=\"$notes\"") or die(mysql_error());
	
	mysql_close($CONNECT);
	header("location: scheduled_notes.php?Sec=schedule");
}

include "header.inc.php";
echo ("&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Home</a> > Add sceduled note<br><br>");
?>

<table width="95%" border="0" align="center" cellpadding="1" cellspacing="0" bgcolor="#000000">
  <tr>
    <td><table width="100%" border="0" cellpadding="4" cellspacing="0" bgcolor="#FFFFFF">
        <tr>
          <td><img src="images/schedulenote.gif" width="200" height="50"><br><br>
            <table width="450" border="0" align="center" cellpadding="4" cellspacing="0">
              <tr>
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr>
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#6699FF">
                          <tr>
                            <td class="Title" style="padding='3'"><div align="center"><strong>Add schedule note</strong></div></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#000000">
                    <tr>
                      <td><table width="100%" border="0" cellpadding="1" cellspacing="0" bgcolor="#FFFFCC">
                          <tr>
                            <td><form name="form1" method="post" action="">
                                <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                  <tr> 
                                    <td width="150">Date:</td>
                                    <td> <select name="month" id="select" class="select">
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
                                        <OPTION VALUE=12>December </select> <select name="day" id="select2" class="select">
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
                                        <OPTION>31 </select> <select name="year" id="select3" class="select">
                                        <option value="2003">2003</option>
                                        <option value="2004">2004</option>
                                        <option value="2005">2005</option>
                                      </select> </td>
                                  </tr>
                                  <tr> 
                                    <td width="150" valign="top">Notes:</td>
                                    <td><textarea name="notes" cols="32" rows="5" id="notes"></textarea></td>
                                  </tr>
                                  <tr> 
                                    <td width="150" valign="top">&nbsp;</td>
                                    <td><input type="submit" name="Submit" value="   Add scheduled note   "></td>
                                  </tr>
                                </table>
                              </form></td>
                          </tr>
                        </table></td>
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