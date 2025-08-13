<?php
include("config.php");
include("identity.php");
if ($refok == "yes")
{
$appheaderstring='Time Sheet Administrator';

include("header.php");
if ($action == 'delete')
	{
	dbconnect($dbusername,$dbuserpasswd);
	mysql_query("delete from projects where id = '$target'");
	$action = 'editprojects';
	}
if ($action == 'addproject')
	{
	dbconnect($dbusername,$dbuserpasswd);
	mysql_query("insert into projects set name='$name', description='$description'");
	$action = 'editprojects';
	}
if ($action == 'deletetask')
	{
	dbconnect($dbusername,$dbuserpasswd);
	mysql_query("delete from tasks where id = '$target'");
	$action = 'edittasks';
	}
if ($action == 'addtask')
	{
	dbconnect($dbusername,$dbuserpasswd);
	mysql_query("insert into tasks set name='$name', description='$description'");
	$action = 'edittasks';
	}
if ($action == 'sheetalter')
	{
	dbconnect($dbusername,$dbuserpasswd);
	mysql_query("update timesheets set $alterfield='$newvalue' where id='$target'");
	$action = 'timesheets';
	$subaction = 'specific';
	}

echo "<center><table width='95%' border='0' cellpadding='0' cellspacing='0'><tr><td valign='top'>";
echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td valign='top'>";
echo "&nbsp;<p><blockquote><ul><li><a href='timesheetcp.php?action=editprojects'>Edit List of Projects</a>
<li><a href='timesheetcp.php?action=edittasks'>Edit List of Tasks</a>";
/*
echo "<form method='post' action='", $PHP_SELF, "'><li>Get hour totals ";
$currenttime = time();
$timedata = getdate( $currenttime );
$lastmonth=$timedata[mon]-1;
if($lastmonth < 10) { $lastmonth = "0" . $lastmonth; }
	echo " for <input type='text' size='4' maxlength='4' name='year' value='", $timedata[year], "'><input type='text' size='2' maxlength='2' name='month' value='", $lastmonth, "'><input type='hidden' name='action' value='hourtotals'><input type='submit' value='Go'></form>";
*/
	echo "<p><li><a href='timesheetcp.php?action=timesheets'>Go to Time Sheets</a>";
// THIS FEATURE NOT YET IMPLEMENTED:
// echo "<li><a href='timesheetcp.php?action=hours'>Go to Hours Calculator</a>";
echo "</ul></blockquote>";
if ($action != "") { echo "<hr noshade>"; }
if($action == 'hourtotals')
	{
        echo "<blockquote> This function has not yet been implemented. </blockquote>";
	}
if ($action == 'editprojects')
	{
        echo "<blockquote>
	<font size='4' face='Verdana'><b>Projects</b></font><p>
	<table border='0' cellpadding='1' cellspacing='1'";
		if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
	echo "><tr><td>
<form method='post' action='", $PHP_SELF, "'>Name: <input type='text' name='name' size='20' maxlength='20'></td></tr><tr><td><font size='2'>Description:<br></font><textarea name='description' cols='50' rows='2' wrap='virtual' noscroll></textarea></td></tr><input type='hidden' name='action' value='addproject'><tr><td align='right'><input type='submit' value='Add'></td></tr></table></form>";
dbconnect($dbusername,$dbuserpasswd);
	$result = mysql_query( "select id, name, description from projects order by name");
	echo "<table border='0' cellpadding='0' cellspacing='5'>";
	while ($row=mysql_fetch_row($result))
		{
		echo "<tr><td";
		if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
		echo "><b>", $row[1], "</b><br><font size='-1'>&nbsp; &nbsp;<i>", $row[2], "</font></td><TD><a href='timesheetcp.php?action=delete&target=", $row[0], "'";
?>
onClick="if (confirm('<?php echo "You are about to delete ", $row[1]; ?>.') == true) { return true; } else { return false; }"
<?php
echo "><img src='icons/delete.gif' border='0' alt='Delete!'></a></td></tr>";
		}
	echo "</table>";
	echo "</blockquote>";
	}
if ($action == 'timesheets')
	{
        echo "<form method='post' action='timesheetcp.php'><table width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td>";
	echo "<select name='employee'><option>";
dbconnect($dbusername,$dbuserpasswd);
	$result5 = mysql_query("select * from userinfo");
	while ($employeelist = mysql_fetch_array($result5))
		{
                echo "<option value='", $employeelist[login], "'>", $employeelist[firstname], " ", $employeelist[lastname];
		}
	echo "</select><input type='submit' value='Go'></td>";
	echo "<input type='hidden' name='action' value='timesheets'>";
	echo "<input type='hidden' name='subaction' value='list'></form>";
        echo "<td align='right'><table border='0' cellpadding='0' cellspacing='0'><tr><td>";
	echo "<form method='post' action='timesheetcp.php'>";
	echo "<td><input type='text' size='4' maxlength='4' name='endyear'></td>";
	echo "<td><input type='text' size='2' maxlength='2' name='endmonth'></td>";
	echo "<td><input type='text' size='2' maxlength='2' name='endday'></td>";
	echo "<td><input type='submit' value='Go'></td></tr></table></td>";
	echo "<input type='hidden' name='action' value='timesheets'>";
	echo "<input type='hidden' name='bydate' value='y'>";
	echo "<input type='hidden' name='subaction' value='list'></form>";
	echo "</tr></table>";
	if ($subaction == 'delete')
		{
		dbconnect($dbusername,$dbuserpasswd);
		mysql_query("delete from timesheets where id = '$target'");
		mysql_query("delete from timesheetrow where sheetid = '$target'");
		$subaction = 'list';
		}
	if ($subaction == 'list')
		{
		if ($endmonth < 10 and substr($endmonth, 0, 1) != '0') { $endmonth = '0' . $endmonth; }
		if ($endday < 10 and substr($endday, 0, 1) != '0') { $endday = '0' . $endday; }
		dbconnect($dbusername,$dbuserpasswd);
		if ($bydate == 'y')
			{
                        $sqlquery = "select * from timesheets where endyear='" . $endyear . "' and endmonth='" . $endmonth . "' and endday='" . $endday . "'";
			} else
				{
                                $sqlquery = "select * from timesheets where login='" . $employee . "'";
				}
		$result6 = mysql_query($sqlquery);
		echo "<p>";
		if ($bydate == "y")
			{
                        echo "<font size='4'>", $endyear, "-", $endmonth, "-", $endday, "</font><ul>";
			} else
				{
		dbconnect($dbusername,$dbuserpasswd);
				$result8=mysql_query( "select * from userinfo where login = '$employee'");
				$user=mysql_fetch_array($result8);
				echo "<font size='4'>", $user[firstname], " ", $user[lastname], "</font><ul>";
				}
		while ($sheetlist = mysql_fetch_array($result6))
			{
			if ($bydate == 'y')
				{
		dbconnect($dbusername,$dbuserpasswd);
				$result8=mysql_query( "select * from userinfo where login = '$sheetlist[login]'");
				$user=mysql_fetch_array($result8);
             			echo "<li><a href='timesheetcp.php?action=timesheets&subaction=specific&target=", $sheetlist[id], "&employee=", $sheetlist[login], "'>", $user[firstname], " ", $user[lastname], "</a>";
		       		if ($sheetlist[sheetfinished] == 'y') { echo " (Done) "; } else { echo " (Not Done) "; }
				if($sheetlist[sheetfinished] == 'y')
					{
				echo " <a href='timesheetcp.php?action=timesheets&subaction=delete&target=", $sheetlist[id], "&employee=", $sheetlist[login], "'";
				?>
 onClick="if (confirm('<?php echo "You are about to delete ", $sheetlist[endyear], "-", $sheetlist[endmonth], "-", $sheetlist[endday], " for ", $user[firstname], " ", $user[lastname]; ?>.\nThe record will be deleted forever unless you click Cancel right now.') == true) { return true; } else { return false; }"
				<?php
				echo "><img src='icons/delete.gif' border='0' alt='Delete!'></a>";
					}
				} else
					{
             				echo "<li><a href='timesheetcp.php?action=timesheets&subaction=specific&target=", $sheetlist[id], "&employee=", $employee, "'>", $sheetlist[endyear], "-", $sheetlist[endmonth], "-", $sheetlist[endday], "</a>";
		       			if ($sheetlist[sheetfinished] == 'y') { echo " (Done) "; } else { echo " (Not Done) "; }
					if($sheetlist[sheetfinished] == 'y')
						{
				echo " <a href='timesheetcp.php?action=timesheets&subaction=delete&target=", $sheetlist[id], "&employee=", $employee, "'";
				?>
 onClick="if (confirm('<?php echo "You are about to delete ", $sheetlist[endyear], "-", $sheetlist[endmonth], "-", $sheetlist[endday], " for ", $user[firstname], " ", $user[lastname]; ?>.\nThe record will be deleted forever unless you click Cancel right now.') == true) { return true; } else { return false; }"
				<?php
				echo "><img src='icons/delete.gif' border='0' alt='Delete!'></a>";
						}
					}
			}
		echo "</ul>";
		}
	if ($subaction == 'specific')
		{
		dbconnect($dbusername,$dbuserpasswd);
		$result7=mysql_query( "select * from timesheets where login = '$employee' and id = '$target'");
		$row=mysql_fetch_array($result7);
		echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
		echo "<tr><td><font size='4' face='Verdana'>";
		dbconnect($dbusername,$dbuserpasswd);
		$result8=mysql_query( "select * from userinfo where login = '$employee'");
		$user=mysql_fetch_array($result8);
		echo $user[firstname], " ", $user[lastname], "<br>";
		echo "Week Ending ", $row[endyear], "-", $row[endmonth], "-", $row[endday], "</font></td><td align='right'>";
		if ($printtsadmin == 'yes')
			{
                        echo "<a href='timesheet.php?action=printerfriendly&endyear=", $row[endyear], "&endmonth=", $row[endmonth], "&endday=", $row[endday], "&employee=", $employee, "' target='_new'><img src='icons/printer.gif' border='0' alt='Printer-friendly Version'></a>";
			}
		echo "</td></tr></table><p><center><table border='1' cellpadding='0' cellspacing='0'>";
		if ($row[mondaydate] == "") { $day = $row[endday]-4; $row[mondaydate] = $row[endmonth] . "/" . $day; }
		if ($row[tuesdaydate] == "") { $day = $row[endday]-3; $row[tuesdaydate] = $row[endmonth] . "/" . $day; }
		if ($row[wednesdaydate] == "") { $day = $row[endday]-2; $row[wednesdaydate] = $row[endmonth] . "/" . $day; }
		if ($row[thursdaydate] == "") { $day = $row[endday]-1; $row[thursdaydate] = $row[endmonth] . "/" . $day; }
		if ($row[fridaydate] == "") { $row[fridaydate] = $row[endmonth] . "/" . $row[endday]; }
			echo "<tr><td align='center'><b>Project</b></td><td align='center'><b>Task</b></td><td align='center'><b>Mon<br>", $row[mondaydate], "</b></td><td align='center'><b>Tue<br>", $row[tuesdaydate], "</td><td align='center'><b>Wed<br>", $row[wednesdaydate], "</td><td align='center'><b>Thur<br>", $row[thursdaydate], "</td><td align='center'><b>Fri<br>", $row[fridaydate], "</td><TD align='center'><b>Week-<br>end</b></td><td";
			if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
			echo "><b>TOTAL</b></td></tr>
			<tr><td colspan='2' align='right'";
			if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
			echo "><font face='Arial' size='2'><i><b>Time In</b></i></font> &nbsp;</td><td";
			if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
			echo " align='center'>", $row[mondayin], "</td><td";
			if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
			echo " align='center'>", $row[tuesdayin], "</td><td";
			if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
			echo " align='center'>", $row[wednesdayin], "</td><td";
			if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
			echo " align='center'>", $row[thursdayin], "</td><td";
			if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
			echo " align='center'>", $row[fridayin], "</td><td";
			if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
			echo " align='center'>", $row[weekendin], "</td><td";
			if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
			echo ">&nbsp;</td></tr>


			<tr><td colspan='2' align='right'";
			if($setting[adminlbbg] != 'xxxxxx') { echo " bgcolor='", $setting[adminlbbg], "'"; }
			echo "><font face='Arial' size='2'><i><b>Lunch Start</b></i></font> &nbsp;</td><td";
			if($setting[adminlbbg] != 'xxxxxx') { echo " bgcolor='", $setting[adminlbbg], "'"; }
			echo " align='center'>", $row[lunchmoin], "</td><td";
			if($setting[adminlbbg] != 'xxxxxx') { echo " bgcolor='", $setting[adminlbbg], "'"; }
			echo " align='center'>", $row[lunchtuin], "</td><td";
			if($setting[adminlbbg] != 'xxxxxx') { echo " bgcolor='", $setting[adminlbbg], "'"; }
			echo " align='center'>", $row[lunchwein], "</td><td";
			if($setting[adminlbbg] != 'xxxxxx') { echo " bgcolor='", $setting[adminlbbg], "'"; }
			echo " align='center'>", $row[lunchthin], "</td><td";
			if($setting[adminlbbg] != 'xxxxxx') { echo " bgcolor='", $setting[adminlbbg], "'"; }
			echo " align='center'>", $row[lunchfrin], "</td><td";
			if($setting[adminlbbg] != 'xxxxxx') { echo " bgcolor='", $setting[adminlbbg], "'"; }
			echo " align='center'>", $row[lunchwkendin], "</td><td";
			if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
			echo ">&nbsp;</td></tr>
			<tr><td colspan='2' align='right'";
			if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
			echo "><font face='Arial' size='2'><i><b>Lunch End</b></i></font> &nbsp;</td><td";
			if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
			echo " align='center'>", $row[lunchmoout], "</td><td";
			if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
			echo " align='center'>", $row[lunchtuout], "</td><td";
			if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
			echo " align='center'>", $row[lunchweout], "</td><td";
			if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
			echo " align='center'>", $row[lunchthout], "</td><td";
			if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
			echo " align='center'>", $row[lunchfrout], "</td><td";
			if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
			echo " align='center'>", $row[lunchwkendout], "</td><td";
			if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
			echo ">&nbsp;</td></tr>


			<tr><td colspan='2' align='right'";
			if($setting[adminlbbg] != 'xxxxxx') { echo " bgcolor='", $setting[adminlbbg], "'"; }
			echo "><font face='Arial' size='2'><i><b>Time Out</b></i></font> &nbsp;</td><td";
			if($setting[adminlbbg] != 'xxxxxx') { echo " bgcolor='", $setting[adminlbbg], "'"; }
			echo " align='center'>", $row[mondayout], "</td><td";
			if($setting[adminlbbg] != 'xxxxxx') { echo " bgcolor='", $setting[adminlbbg], "'"; }
			echo " align='center'>", $row[tuesdayout], "</td><td";
			if($setting[adminlbbg] != 'xxxxxx') { echo " bgcolor='", $setting[adminlbbg], "'"; }
			echo " align='center'>", $row[wednesdayout], "</td><td";
			if($setting[adminlbbg] != 'xxxxxx') { echo " bgcolor='", $setting[adminlbbg], "'"; }
			echo " align='center'>", $row[thursdayout], "</td><td";
			if($setting[adminlbbg] != 'xxxxxx') { echo " bgcolor='", $setting[adminlbbg], "'"; }
			echo " align='center'>", $row[fridayout], "</td><td";
			if($setting[adminlbbg] != 'xxxxxx') { echo " bgcolor='", $setting[adminlbbg], "'"; }
			echo " align='center'>", $row[weekendout], "</td><td";
			if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
			echo ">&nbsp;</td></tr>";
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
				echo "<td align='center'";
				if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
				echo ">", $prow[weektotal], "</td></tr>";
				}
		dbconnect($dbusername,$dbuserpasswd);
		$result4=mysql_query( "select sum(monday) AS mondaysum, sum(tuesday) AS tuesdaysum,
					sum(wednesday) AS wednesdaysum, sum(thursday) AS thursdaysum,
					sum(friday) AS fridaysum, sum(weekend) AS weekendsum, sum(monday) + sum(tuesday) + sum(wednesday) + sum(thursday) + sum(friday) + sum(weekend) AS weektotal from timesheetrow where sheetid = '$row[id]'");
		$weektotals = mysql_fetch_array($result4);
		echo "<tr><td colspan='2' align='right'";
		if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
		echo "><b>TOTAL</b></td><td align='center'";
		if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
		echo ">", $weektotals[mondaysum], "</td><td";
		if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
		echo ">", $weektotals[tuesdaysum], "</td><td";
	        if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
		echo ">", $weektotals[wednesdaysum], "</td><td";
        	if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
	 	echo ">", $weektotals[thursdaysum], "</td><td";
		if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
 		echo " align='center'>", $weektotals[fridaysum], "</td><td";
		if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
		echo " align='center'>", $weektotals[weekendsum], "</td><td";
        	if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
	 	echo " align='center'>", $weektotals[weektotal], "</td></tr>";
		echo "</table><p>";
		echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'><tr><td>";
		if ($tsharddelete == 'no')
			{
			echo "<form method='post' action='timesheetcp.php'>This time sheet is <select name='newvalue'><option value='y'>done<option value='n'";
			if ($row[sheetfinished] == 'n') { echo " selected"; }
			echo ">not done</select>";
			echo "<input type='hidden' name='action' value='sheetalter'>";
			echo "<input type='hidden' name='alterfield' value='sheetfinished'>";
			echo "<input type='hidden' name='employee' value='", $employee, "'>";
			echo "<input type='hidden' name='target' value='", $target, "'>";
			echo "<input type='submit' value='Change'></form>";
			}
		echo "</td>";
		if($row[sheetfinished]=='y')
			{
			echo "<td align='right'><a href='timesheetcp.php?action=timesheets&subaction=delete&target=", $row[id], "&employee=", $employee, "'";
?>
 onClick="if (confirm('<?php echo "You are about to delete ", $row[endyear], "-", $row[endmonth], "-", $row[endday], " for ", $user[firstname], " ", $user[lastname]; ?>.\nThe record will be deleted forever unless you click Cancel right now.') == true) { return true; } else { return false; }"
<?php
			echo "><img src='icons/delete.gif' border='0' alt='Delete!'></a></td>";
        		} else { echo "<td> </td>"; }
		echo "</tr></table>";
		}	
	}
if ($action == 'edittasks')
	{
        echo "<blockquote>
	<font size='4' face='Verdana'><b>Tasks</b></font><p>
	<table border='0' cellpadding='1' cellspacing='1'";
		if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }              	
	echo "><tr><td>
<form method='post' action='", $PHP_SELF, "'>Name: <input type='text' name='name' size='20' maxlength='20'></td></tr><tr><td><font size='2'>Description:<br></font><textarea name='description' cols='50' rows='2' wrap='virtual' noscroll></textarea></td></tr><input type='hidden' name='action' value='addtask'><tr><td align='right'><input type='submit' value='Add'></td></tr></table></form>";
	dbconnect($dbusername,$dbuserpasswd);
	$result = mysql_query( "select id, name, description from tasks order by name");
	echo "<table border='0' cellpadding='0' cellspacing='5'>";
	while ($row=mysql_fetch_row($result))
		{
		echo "<tr><td";
		if($setting[headinghighlight] != 'xxxxxx') { echo " bgcolor='", $setting[headinghighlight], "'"; }
		echo "><b>", $row[1], "</b><br><font size='-1'>&nbsp; &nbsp;<i>", $row[2], "</font></td><TD><a href='timesheetcp.php?action=deletetask&target=", $row[0], "'";
?>
onClick="if (confirm('<?php echo "You are about to delete ", $row[1]; ?>.') == true) { return true; } else { return false; }"
<?php
echo "><img src='icons/delete.gif' border='0' alt='Delete!'></a></td></tr>";
		}
	echo "</table>";
	echo "</blockquote>";
	}

echo "</td></tr></table></td></tr></table>";




}
?>
