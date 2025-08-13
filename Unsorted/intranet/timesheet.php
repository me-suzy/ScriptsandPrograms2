<?php
include("config.php");
include("identity.php");
if ($refok == "yes")
{
	$appheaderstring='Time Sheet';
	include("header.php");

if($useraccount == '')
	{ $useraccount = $currentuser; }
if($employee)
	{ $useraccount = $employee; }

dbconnect($dbusername,$dbuserpasswd);
$karma=mysql_query("select * from userinfo where login='$useraccount'");
$account=mysql_fetch_array($karma);	

if ($action == 'createnew')
	{
	dbconnect($dbusername,$dbuserpasswd);
	mysql_query("insert into timesheets set login='$useraccount', endyear='$endyear', endmonth='$endmonth', endday='$endday'");
	}
if ($action == 'markdone')
	{
	dbconnect($dbusername,$dbuserpasswd);
	mysql_query("update timesheets set sheetfinished='y' where id='$target'");
	}
if ($action == 'delete')
	{
	dbconnect($dbusername,$dbuserpasswd);
	mysql_query("delete from timesheets where id='$target'");
	mysql_query("delete from timesheetrow where sheetid='$target'");
	}	
if ($action == 'update')
	{
/*	$x = 0;
	echo "sheetid:", $sheetid;
	echo "<Br>projectrows:", $projectrows;
	while ($x <= $projectrows)
		{
		$x++;
                echo "<br>projectid,taskid:", $projectid[$x], " - ", $taskid[$x];
		}
*/
        $x = 0;
	while ($x <= $projectrows)
		{
		$x++;
                if ($projectid[$x] or $taskid[$x])
			{
	dbconnect($dbusername,$dbuserpasswd);
			$sqlquery = "update timesheetrow set sheetid='$sheetid', projectid = '$projectid[$x]',
					taskid='$taskid[$x]', monday='$monday[$x]', tuesday='$tuesday[$x]',
					wednesday='$wednesday[$x]', thursday='$thursday[$x]', friday='$friday[$x]',
					weekend='$weekend[$x]' where id = '$recordid[$x]'";
                        mysql_query($sqlquery);
			// echo $sqlquery;
			}
		}
	dbconnect($dbusername,$dbuserpasswd);
	mysql_query("update timesheets set mondaydate='$mondaydate', tuesdaydate='$tuesdaydate', wednesdaydate='$wednesdaydate',
				thursdaydate='$thursdaydate', fridaydate='$fridaydate', mondayin='$mondayin', mondayout='$mondayout',
				tuesdayin='$tuesdayin', tuesdayout='$tuesdayout', wednesdayin='$wednesdayin', wednesdayout='$wednesdayout',
				thursdayin='$thursdayin', thursdayout='$thursdayout', fridayin='$fridayin', fridayout='$fridayout',
				weekendin='$weekendin', weekendout='$weekendout',
				lunchmoin='$lunchmoin', lunchmoout='$lunchmoout', lunchtuin='$lunchtuin', lunchtuout='$lunchtuout',
				lunchwein='$lunchwein', lunchweout='$lunchweout', lunchthin='$lunchthin', lunchthout='$lunchthout',
				lunchfrin='$lunchfrin', lunchfrout='$lunchfrout', lunchwkendin='$lunchwkendin',
				lunchwkendout='$lunchwkendout'
				where id ='$sheetid'");
	$x = "new";
	if ($projectid[$x])
		{
	dbconnect($dbusername,$dbuserpasswd);
                mysql_query("insert into timesheetrow set sheetid='$sheetid', projectid = '$projectid[$x]', taskid='$taskid[$x]', monday='$monday[$x]', tuesday='$tuesday[$x]', wednesday='$wednesday[$x]', thursday='$thursday[$x]', friday='$friday[$x]', weekend='$weekend[$x]'");
		}
	$action = 'showsheet';
	}

if ($action != 'printerfriendly')
	{
	echo "<center><table width='95%' border='0' cellpadding='0' cellspacing='0'><tr><td valign='top'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td valign='top'>";

	echo "<table border='0' cellpadding='0' cellspacing='10'>";

	if($setting[want_timesheetcp] == 'y' and $setting[perm_timesheetcp] == 'y')
		{
                echo "<form method='post' action='", $PHP_SELF, "'><tr><td colspan='3' bgcolor='", $setting[headinghighlight], "'>&nbsp; Account: <select name='useraccount'>";
		dbconnect($dbusername,$dbuserpasswd);
		$karma=mysql_query("select * from userinfo order by lastname, firstname");
		while($userlist=mysql_fetch_array($karma))
			{
                        echo "<option value='", $userlist[login],"'";
			if($userlist[login] == $account[login]) { echo " selected"; }
			echo ">", $userlist[firstname], " ", $userlist[lastname];
			}
		echo "</select> <input type='hidden' name='action' value='changeuser'><input type='submit' value='Go'></td></tr></form>";
		}

	echo "<tr><Td><ul><li><a href='timesheet.php?action=showprojects'>List Projects</a>
	<li><a href='timesheet.php?action=showtasks'>List Tasks</a><p>";
	dbconnect($dbusername,$dbuserpasswd);
		$result=mysql_query( "select * from timesheets where login = '$account[login]'");

	echo "</ul></td>
	<td align='center'>
	<table border='0' cellpadding='0' cellspacing='0'>
<form method='post' action='timesheet.php'><input type='hidden' name='action' value='createnew'>
<input type='hidden' name='useraccount' value='", $useraccount, "'>
	<tr><td rowspan='2' valign='top'> <font face='Arial'><b>New:</b></font> &nbsp;</td><td colspan='4'><font size='1'>Week ending date:</font></td></tr>
	<tr><td><font size='2' face='Arial'>Year</font><br><input type='text' size='4' maxlength='4' name='endyear'></td>
	<td><font size='2' face='Arial'>Mo.</font><br><input type='text' size='2' maxlength='2' name='endmonth'></td>
	<td><font size='2' face='Arial'>Day</font><br><input type='text' size='2' maxlength='2' name='endday'> </td>
	<td valign='bottom'> <input type='submit' value='Add'></td>
	</tr></table>
	</td>";
	echo "</form><td><ul>";
		while ($row=mysql_fetch_array($result))
			{
			if ($row[sheetfinished] != 'y')
				{
                		echo "<li><a href='timesheet.php?action=showsheet&endyear=", $row[endyear], "&endmonth=", $row[endmonth], "&endday=", $row[endday], "&useraccount=", $useraccount, "'>Timesheet for week ending ", $row[endyear], "-", $row[endmonth], "-", $row[endday], "</a>";
				if ($tsharddelete == 'yes')
					{
			                echo " <a href='timesheet.php?action=delete&target=", $row[id], "'";
?>
 onClick="if (confirm('<?php echo "You are about to delete ", $row[endyear], "-", $row[endmonth], "-", $row[endday]; ?>.\nThe record will be deleted forever unless you click Cancel right now.') == true) { return true; } else { return false; }"
<?php
					echo "><img src='icons/delete.gif' border='0' alt='Delete!'></a>";
					} else
						{
				                echo " <a href='timesheet.php?action=markdone&target=", $row[id], "'><img src='icons/delete.gif' border='0' alt='Mark Done'></a>";
						}
				}
			}
	echo "</tr></table>";
	}
if ($action == 'showprojects')
	{
	echo "<hr noshade>";
	dbconnect($dbusername,$dbuserpasswd);
	$result = mysql_query( "select id, name, description from projects order by name");
	echo "<table border='0' cellpadding='0' cellspacing='5'>";
	while ($row=mysql_fetch_row($result))
		{
		echo "<tr><td bgcolor='", $setting[headinghighlight], "'><b>", $row[1], "</b><br><font size='-1'>&nbsp; &nbsp;<i>", $row[2], "</font></td></tr>";
		}
	echo "</table>";
	}
if ($action == 'showtasks')
	{
	echo "<hr noshade>";
	dbconnect($dbusername,$dbuserpasswd);
	$result = mysql_query( "select id, name, description from tasks order by name");
	echo "<table border='0' cellpadding='0' cellspacing='5'>";
	while ($row=mysql_fetch_row($result))
		{
		echo "<tr><td bgcolor='", $setting[headinghighlight], "'><b>", $row[1], "</b><br><font size='-1'>&nbsp; &nbsp;<i>", $row[2], "</font></td></tr>";
		}
	echo "</table>";
	}
if ($action == 'showsheet')
	{
	echo "<hr noshade><form method='post' action='timesheet.php'><input type='hidden' name='useraccount' value='", $useraccount, "'>";
	dbconnect($dbusername,$dbuserpasswd);
	$result=mysql_query( "select * from timesheets where login = '$account[login]' and endyear ='$endyear' and endmonth='$endmonth' and endday='$endday'");
	$row=mysql_fetch_array($result);
	echo "<table border='0' cellpadding='0' cellspacing='0'>";
	echo "<tr><td colspan='8'><font size='4' face='Verdana'>Week Ending ", $row[endyear], "-", $row[endmonth], "-", $row[endday], "</font></td><td align='right'>";
	if ($printtimesheet == 'yes')
		{
                echo "<a href='timesheet.php?action=printerfriendly&useraccount=", $useraccount, "&endyear=", $row[endyear], "&endmonth=", $row[endmonth], "&endday=", $row[endday], "' target='_new'><img src='icons/printer.gif' border='0' alt='Printer-friendly Version'></a>";
		}
	echo "</td></tr>";
// FIGURE DATES
	if ($row[mondaydate] == "")
		{
		if($endday >= 5)
			{
			$day = $endday-4; $row[mondaydate] = $endmonth . "/" . $day;
			} else
				{
				if($endmonth == 11 or $endmonth == 04 or $endmonth == 06 or $endmonth == 09)
					{
                                        $day = ($endday + 30) - 4;
					} else {
                                               $day = ($endday + 31) - 4;
						}
				$row[mondaydate] = $endmonth -1 . "/" . $day;
			        }
		}
	if ($row[tuesdaydate] == "")
		{
		if($endday >= 4)
			{
			$day = $endday-3; $row[tuesdaydate] = $endmonth . "/" . $day;
			} else {
				if($endmonth == 11 or $endmonth == 04 or $endmonth == 06 or $endmonth == 09)
					{
                                        $day = ($endday + 30) - 3;
					} else {
                                               $day = ($endday + 31) - 3;
						}
				$row[tuesdaydate] = $endmonth -1 . "/" . $day;
			        }
		}
	if ($row[wednesdaydate] == "")
		{
		if($endday >= 3)
			{
			$day = $endday-2; $row[wednesdaydate] = $endmonth . "/" . $day;
			} else {
				if($endmonth == 11 or $endmonth == 04 or $endmonth == 06 or $endmonth == 09)
					{
                                        $day = ($endday + 30) - 2;
					} else {
                                               $day = ($endday + 31) - 2;
						}
				$row[wednesdaydate] = $endmonth -1 . "/" . $day;
			        }
		}
	if ($row[thursdaydate] == "")
		{
		if($endday >= 2)
			{
			$day = $endday-1; $row[thursdaydate] = $endmonth . "/" . $day;
			} else {
				if($endmonth == 11 or $endmonth == 04 or $endmonth == 06 or $endmonth == 09)
					{
                                        $day = ($endday + 30) - 1;
					} else {
                                               $day = ($endday + 31) - 1;
						}
				$row[thursdaydate] = $endmonth -1 . "/" . $day;
			        }
		}
	if ($row[fridaydate] == "") { $row[fridaydate] = $endmonth . "/" . $endday; }

		echo "<tr><td align='center'><b>Project</b></td><td align='center'><b>Task</b></td><td align='center'><b>Mon<br><input type='text' size='5' name='mondaydate' value='", $row[mondaydate], "'></b></td><td align='center'><b>Tue<br><input type='text' size='5' name='tuesdaydate' value='", $row[tuesdaydate], "'></b></td><td align='center'><b>Wed<br><input type='text' size='5' name='wednesdaydate' value='", $row[wednesdaydate], "'></b></td><td align='center'><b>Thur<br><input type='text' size='5' name='thursdaydate' value='", $row[thursdaydate], "'></b></td><td align='center'><b>Fri<br><input type='text' size='5' name='fridaydate' value='", $row[fridaydate], "'></b></td><TD align='center'><b>Week-<br>end</b></td><td bgcolor='", $setting[headinghighlight], "'><b>TOTAL</b></td></tr>
		<tr><td colspan='2' align='right' ";
			if($setting[linkbarbg] != 'xxxxxx') { echo "bgcolor='", $setting[linkbarbg], "'"; }
		echo "><font face='Arial' size='2' color='", $setting[lb_fontcolor], "'><i><b>Time In</b></i></font> &nbsp;</td><td bgcolor='", $setting[linkbarbg], "' align='center'><input type='text' size='5' name='mondayin' value='", $row[mondayin], "'></td><td bgcolor='", $setting[linkbarbg], "'   align='center'><input type='text' size='5' name='tuesdayin' value='", $row[tuesdayin], "'></td><td bgcolor='", $setting[linkbarbg], "'   align='center'><input type='text' size='5' name='wednesdayin' value='", $row[wednesdayin], "'></td><td bgcolor='", $setting[linkbarbg], "'   align='center'><input type='text' size='5' name='thursdayin' value='", $row[thursdayin], "'></td><td bgcolor='", $setting[linkbarbg], "'   align='center'><input type='text' size='5' name='fridayin' value='", $row[fridayin], "'></td><td bgcolor='", $setting[linkbarbg], "'   align='center'><input type='text' size='5' name='weekendin' value='", $row[weekendin], "'></td><td bgcolor='", $setting[headinghighlight], "'>&nbsp;</td></tr>
		<tr><td colspan='2' align='right' ";
			if($setting[adminlbbg] != 'xxxxxx') { echo "bgcolor='", $setting[adminlbbg], "'"; }
		echo "><font face='Arial' size='2' color='", $setting[ab_fontcolor], "'><i><b>Lunch Start</b></i></font> &nbsp;</td><td bgcolor='", $setting[adminlbbg], "' align='center'><input type='text' size='5' name='lunchmoin' value='", $row[lunchmoin], "'></td><td bgcolor='", $setting[adminlbbg], "' align='center'><input type='text' size='5' name='lunchtuin' value='", $row[lunchtuin], "'></td><td bgcolor='", $setting[adminlbbg], "' align='center'><input type='text' size='5' name='lunchwein' value='", $row[lunchwein], "'></td><td bgcolor='", $setting[adminlbbg], "' align='center'><input type='text' size='5' name='lunchthin' value='", $row[lunchthin], "'></td><td bgcolor='", $setting[adminlbbg], "' align='center'><input type='text' size='5' name='lunchfrin' value='", $row[lunchfrin], "'></td><td bgcolor='", $setting[adminlbbg], "' align='center'><input type='text' size='5' name='lunchwkendin' value='", $row[lunchwkendin], "'></td><td bgcolor='", $setting[headinghighlight], "'>&nbsp;</td></tr>
		<tr><td colspan='2' align='right' ";
			if($setting[linkbarbg] != 'xxxxxx') { echo "bgcolor='", $setting[linkbarbg], "'"; }
		echo "><font face='Arial' size='2' color='", $setting[lb_fontcolor], "'><i><b>Lunch End</b></i></font> &nbsp;</td><td bgcolor='", $setting[linkbarbg], "' align='center'><input type='text' size='5' name='lunchmoout' value='", $row[lunchmoout], "'></td><td bgcolor='", $setting[linkbarbg], "'   align='center'><input type='text' size='5' name='lunchtuout' value='", $row[lunchtuout], "'></td><td bgcolor='", $setting[linkbarbg], "'   align='center'><input type='text' size='5' name='lunchweout' value='", $row[lunchweout], "'></td><td bgcolor='", $setting[linkbarbg], "'   align='center'><input type='text' size='5' name='lunchthout' value='", $row[lunchthout], "'></td><td bgcolor='", $setting[linkbarbg], "'   align='center'><input type='text' size='5' name='lunchfrout' value='", $row[lunchfrout], "'></td><td bgcolor='", $setting[linkbarbg], "'   align='center'><input type='text' size='5' name='lunchwkendout' value='", $row[lunchwkendout], "'></td><td bgcolor='", $setting[headinghighlight], "'>&nbsp;</td></tr>
		<tr><td colspan='2' align='right' ";
			if($setting[adminlbbg] != 'xxxxxx') { echo "bgcolor='", $setting[adminlbbg], "'"; }
		echo "><font face='Arial' size='2' color='", $setting[ab_fontcolor], "'><i><b>Time Out</b></i></font> &nbsp;</td><td bgcolor='", $setting[adminlbbg], "' align='center'><input type='text' size='5' name='mondayout' value='", $row[mondayout], "'></td><td bgcolor='", $setting[adminlbbg], "' align='center'><input type='text' size='5' name='tuesdayout' value='", $row[tuesdayout], "'></td><td bgcolor='", $setting[adminlbbg], "' align='center'><input type='text' size='5' name='wednesdayout' value='", $row[wednesdayout], "'></td><td bgcolor='", $setting[adminlbbg], "' align='center'><input type='text' size='5' name='thursdayout' value='", $row[thursdayout], "'></td><td bgcolor='", $setting[adminlbbg], "' align='center'><input type='text' size='5' name='fridayout' value='", $row[fridayout], "'></td><td bgcolor='", $setting[adminlbbg], "' align='center'><input type='text' size='5' name='weekendout' value='", $row[weekendout], "'></td><td bgcolor='", $setting[headinghighlight], "'>&nbsp;</td></tr>";

	dbconnect($dbusername,$dbuserpasswd);
		$result=mysql_query( "select *, monday + tuesday + wednesday + thursday + friday + weekend AS weektotal from timesheetrow where sheetid='$row[id]'");
		$i = 0;
		while ($prow=mysql_fetch_array($result))
			{
			$i++;
                        echo "<tr><td><select name='projectid[", $i, "]'><option>";
	dbconnect($dbusername,$dbuserpasswd);
				$result2=mysql_query( "select * from projects order by name");
				while ($projlist=mysql_fetch_array($result2))
					{
                                        echo "<option value='$projlist[id]'";
					if ($projlist[id] == $prow[projectid]) { echo " selected"; }
					echo ">", $projlist[name];
					}
			echo "</select></td><td><select name='taskid[", $i, "]'><option>";
	dbconnect($dbusername,$dbuserpasswd);
				$result3=mysql_query( "select * from tasks order by name");
				while ($tasklist=mysql_fetch_array($result3))
					{
                                        echo "<option value='$tasklist[id]'";
					if ($tasklist[id] == $prow[taskid]) { echo " selected"; }
					echo ">", $tasklist[name];
					}
			echo "</select></td><td align='center'><input type='text' size='5' name='monday[", $i, "]' value='", $prow[monday], "'></td>";
			echo "<td align='center'><input type='text' size='5' name='tuesday[", $i, "]' value='", $prow[tuesday], "'></td>";
			echo "<td align='center'><input type='text' size='5' name='wednesday[", $i, "]' value='", $prow[wednesday], "'></td>";
			echo "<td align='center'><input type='text' size='5' name='thursday[", $i, "]' value='", $prow[thursday], "'></td>";
			echo "<td align='center'><input type='text' size='5' name='friday[", $i, "]' value='", $prow[friday], "'></td>";
			echo "<TD align='center'><input type='text' size='5' name='weekend[", $i, "]' value='", $prow[weekend], "'></td>";
			echo "<input type='hidden' name='recordid[", $i, "]' value='", $prow[id], "'>";
			echo "<td align='center' bgcolor='", $setting[headinghighlight], "'>", $prow[weektotal], "</td></tr>";
			}
        echo "<tr><td><select name='projectid[new]'><option>";
	dbconnect($dbusername,$dbuserpasswd);
	$result=mysql_query( "select * from projects order by name");
	while ($projlist=mysql_fetch_array($result))
		{
                echo "<option value='$projlist[id]'";
		if ($projlist[id] == $prow[projectid]) { echo " selected"; }
		echo "'>", $projlist[name];
		}
	echo "</select></td><td><select name='taskid[new]'><option>";
	dbconnect($dbusername,$dbuserpasswd);
	$result=mysql_query( "select * from tasks order by name");
	while ($tasklist=mysql_fetch_array($result))
		{
                echo "<option value='$tasklist[id]'";
		if ($tasklist[id] == $prow[taskid]) { echo " selected"; }
		echo "'>", $tasklist[name];
		}
	echo "</select></td><td align='center'><input type='text' size='5' name='monday[new]' value='0'></td>";
	echo "<td align='center'><input type='text' size='5' name='tuesday[new]' value='0'></td>";
	echo "<td align='center'><input type='text' size='5' name='wednesday[new]' value='0'></td>";
	echo "<td align='center'><input type='text' size='5' name='thursday[new]' value='0'></td>";
	echo "<td align='center'><input type='text' size='5' name='friday[new]' value='0'></td>";
	echo "<TD align='center'><input type='text' size='5' name='weekend[new]' value='0'></td>";
	echo "<td align='center' bgcolor='", $setting[headinghighlight], "'>0</td></tr>";
	dbconnect($dbusername,$dbuserpasswd);
	$result4=mysql_query( "select sum(monday) AS mondaysum, sum(tuesday) AS tuesdaysum,
				sum(wednesday) AS wednesdaysum, sum(thursday) AS thursdaysum,
				sum(friday) AS fridaysum, sum(weekend) AS weekendsum, sum(monday) + sum(tuesday) + sum(wednesday) + sum(thursday) + sum(friday) + sum(weekend) AS weektotal from timesheetrow where sheetid = '$row[id]'");
	$weektotals = mysql_fetch_array($result4);
	echo "<tr><td colspan='2' align='right' bgcolor='", $setting[headinghighlight], "'><b>TOTAL</b></td><td align='center' bgcolor='", $setting[headinghighlight], "'>", $weektotals[mondaysum], "</td><td bgcolor='", $setting[headinghighlight], "' align='center'>", $weektotals[tuesdaysum], "</td><td bgcolor='", $setting[headinghighlight], "' align='center'>", $weektotals[wednesdaysum], "</td><td bgcolor='", $setting[headinghighlight], "' align='center'>", $weektotals[thursdaysum], "</td><td bgcolor='", $setting[headinghighlight], "' align='center'>", $weektotals[fridaysum], "</td><td bgcolor='", $setting[headinghighlight], "' align='center'>", $weektotals[weekendsum], "</td><td bgcolor='", $setting[headinghighlight], "' align='center'>", $weektotals[weektotal], "</td></tr>";
	echo "<tr><td colspan='9' align='right'><input type='submit' value='Update'></td></tr>";
	echo "<input type='hidden' name='action' value='update'>";
	echo "<input type='hidden' name='projectrows' value='", $i, "'>";
	echo "<input type='hidden' name='sheetid' value='", $row[id], "'>";
	echo "<input type='hidden' name='endyear' value='", $endyear, "'>";
	echo "<input type='hidden' name='endmonth' value='", $endmonth, "'>";
	echo "<input type='hidden' name='endday' value='", $endday, "'>";
	echo "</form></table>";
	}
if ($action == 'printerfriendly')
	{
	if ($employee)
		{
		$targetuser=$employee;
		} else
			{
			$targetuser = $account[login];
			}
	echo "<html><body bgcolor='#ffffff' text='#000000'>";
	if ($printtsbw == 'yes')
		{
                $color1 = 'ffffff'; $color2 = 'ffffff';
		} else
			{
			$color1 = 'ffff66'; $color2 = 'ddddff';
			}
	dbconnect($dbusername,$dbuserpasswd);
	$sqlquery = "select * from timesheets where login = '" . $targetuser . "' and endyear ='" . $endyear . "' and endmonth='" . $endmonth . "' and endday='" . $endday . "'";
	$result=mysql_query($sqlquery);
	$row=mysql_fetch_array($result);
	echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
	echo "<tr><td><font size='", $setting[heading_fontsize], "' face='", $setting[heading_fontface], "'>", $account[firstname], " ", $account[lastname], "<br>Week Ending ", $row[endyear], "-", $row[endmonth], "-", $row[endday], "</font></td><td align='right'><p>";
	echo "<font size='1'>";
	if ($printtssig == 'yes')
		{
		if ($printtssigsup == 'yes') { echo "Employee "; }
		echo "Signature: ________________________________</font>";
		}
	echo "</td></tr></table><br>&nbsp;<br><center><table border='1' cellpadding='0' cellspacing='0'>";
	if ($row[mondaydate] == "") { $day = $endday-4; $row[mondaydate] = $endmonth . "/" . $day; }
	if ($row[tuesdaydate] == "") { $day = $endday-3; $row[tuesdaydate] = $endmonth . "/" . $day; }
	if ($row[wednesdaydate] == "") { $day = $endday-2; $row[wednesdaydate] = $endmonth . "/" . $day; }
	if ($row[thursdaydate] == "") { $day = $endday-1; $row[thursdaydate] = $endmonth . "/" . $day; }
	if ($row[fridaydate] == "") { $row[fridaydate] = $endmonth . "/" . $endday; }
		echo "<tr><td align='center'><b>Project</b></td><td align='center'><b>Task</b></td><td align='center'><b>Mon<br>", $row[mondaydate], "</b></td><td align='center'><b>Tue<br>", $row[tuesdaydate], "</td><td align='center'><b>Wed<br>", $row[wednesdaydate], "</td><td align='center'><b>Thur<br>", $row[thursdaydate], "</td><td align='center'><b>Fri<br>", $row[fridaydate], "</td><TD align='center'><b>Week-<br>end</b></td><td bgcolor='", $color2, "'><b>TOTAL</b></td></tr>
		<tr><td colspan='2' align='right' bgcolor='white'><font face='Arial' size='2'><i><b>Time In</b></i></font> &nbsp;</td><td bgcolor='white'  align='center'>", $row[mondayin], "</td><td bgcolor='white'   align='center'>", $row[tuesdayin], "</td><td bgcolor='white'   align='center'>", $row[wednesdayin], "</td><td bgcolor='white'   align='center'>", $row[thursdayin], "</td><td bgcolor='white'   align='center'>", $row[fridayin], "</td><td bgcolor='white'   align='center'>", $row[weekendin], "</td><td bgcolor='", $color2, "'>&nbsp;</td></tr>

		<tr><td colspan='2' align='right' bgcolor='", $color1, "'><font face='Arial' size='2'><i><b>Lunch Start</b></i></font> &nbsp;</td><td bgcolor='", $color1, "' align='center'>", $row[lunchmoin], "</td><td bgcolor='", $color1, "' align='center'>", $row[lunchtuin], "</td><td bgcolor='", $color1, "' align='center'>", $row[lunchwein], "</td><td bgcolor='", $color1, "' align='center'>", $row[lunchthin], "</td><td bgcolor='", $color1, "' align='center'>", $row[lunchfrin], "</td><td bgcolor='", $color1, "' align='center'>", $row[lunchwkendin], "</td><td bgcolor='", $color2, "'>&nbsp;</td></tr>
		<tr><td colspan='2' align='right' bgcolor='white'><font face='Arial' size='2'><i><b>Lunch End</b></i></font> &nbsp;</td><td bgcolor='white'  align='center'>", $row[lunchmoout], "</td><td bgcolor='white'   align='center'>", $row[lunchtuout], "</td><td bgcolor='white'   align='center'>", $row[lunchweout], "</td><td bgcolor='white'   align='center'>", $row[lunchthout], "</td><td bgcolor='white'   align='center'>", $row[lunchfrout], "</td><td bgcolor='white'   align='center'>", $row[lunchwkendout], "</td><td bgcolor='", $color2, "'>&nbsp;</td></tr>

		<tr><td colspan='2' align='right' bgcolor='", $color1, "'><font face='Arial' size='2'><i><b>Time Out</b></i></font> &nbsp;</td><td bgcolor='", $color1, "' align='center'>", $row[mondayout], "</td><td bgcolor='", $color1, "' align='center'>", $row[tuesdayout], "</td><td bgcolor='", $color1, "' align='center'>", $row[wednesdayout], "</td><td bgcolor='", $color1, "' align='center'>", $row[thursdayout], "</td><td bgcolor='", $color1, "' align='center'>", $row[fridayout], "</td><td bgcolor='", $color1, "' align='center'>", $row[weekendout], "</td><td bgcolor='", $color2, "'>&nbsp;</td></tr>";

	dbconnect($dbusername,$dbuserpasswd);
		$result=mysql_query( "select *, monday + tuesday + wednesday + thursday + friday + weekend AS weektotal from timesheetrow where sheetid='$row[id]'");
		$i = 0;
		while ($prow=mysql_fetch_array($result))
			{
			$i++;
                        echo "<tr><td><select name='projectid[", $i, "]'><option>";
	dbconnect($dbusername,$dbuserpasswd);
				$result2=mysql_query( "select * from projects where id ='$prow[projectid]'");
				$projlist=mysql_fetch_array($result2);
				echo $projlist[name];
			echo "</td><td><select name='taskid[", $i, "]'><option>";
	dbconnect($dbusername,$dbuserpasswd);
				$result3=mysql_query( "select * from tasks where id ='$prow[taskid]'");
				$tasklist=mysql_fetch_array($result3);
                                echo $tasklist[name];
			echo "</select></td><td align='center'>", $prow[monday], "</td>";
			echo "<td align='center'>", $prow[tuesday], "</td>";
			echo "<td align='center'>", $prow[wednesday], "</td>";
			echo "<td align='center'>", $prow[thursday], "</td>";
			echo "<td align='center'>", $prow[friday], "</td>";
			echo "<TD align='center'>", $prow[weekend], "</td>";
			echo "<td align='center' bgcolor='", $color2, "'>", $prow[weektotal], "</td></tr>";
			}
	dbconnect($dbusername,$dbuserpasswd);
	$result4=mysql_query( "select sum(monday) AS mondaysum, sum(tuesday) AS tuesdaysum,
				sum(wednesday) AS wednesdaysum, sum(thursday) AS thursdaysum,
				sum(friday) AS fridaysum, sum(weekend) AS weekendsum, sum(monday) + sum(tuesday) + sum(wednesday) + sum(thursday) + sum(friday) + sum(weekend) AS weektotal from timesheetrow where sheetid = '$row[id]'");
	$weektotals = mysql_fetch_array($result4);
	echo "<tr><td colspan='2' align='right' bgcolor='", $color2, "'><b>TOTAL</b></td><td align='center' bgcolor='", $color2, "'>", $weektotals[mondaysum], "</td><td bgcolor='", $color2, "' align='center'>", $weektotals[tuesdaysum], "</td><td bgcolor='", $color2, "' align='center'>", $weektotals[wednesdaysum], "</td><td bgcolor='", $color2, "' align='center'>", $weektotals[thursdaysum], "</td><td bgcolor='", $color2, "' align='center'>", $weektotals[fridaysum], "</td><td bgcolor='", $color2, "' align='center'>", $weektotals[weekendsum], "</td><td bgcolor='", $color2, "' align='center'>", $weektotals[weektotal], "</td></tr>";
	echo "</table><br>&nbsp;<p>";
	if ($printtssigsup == 'yes')
		{
		echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
		echo "<tr><td><font size='1'>___________________________________________<br>Supervisor Signature</font></td><td><font size='1'>________________________<br>Date</font></td></tr>";
		echo "</table>";
		}
	}
if ($action != 'printerfriendly')
	{
	echo "</td></tr></table> </td></tr></table>";
	}
}
?>
