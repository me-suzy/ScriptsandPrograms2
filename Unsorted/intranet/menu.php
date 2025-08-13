<html>
<head><title>Menu</title></head>
<?php
include("config.php");
include("identity.php");
if ($refok == "yes")
	{
	dbconnect($dbusername,$dbuserpasswd);
	$result = mysql_query("select *	from userinfo where ipaddress ='$ipaddy'");
	$row = mysql_fetch_array($result);
if ($row[menu_bgcolor] == "")   { $row[menu_bgcolor] = "660000";   }
if ($row[menu_fontcolor] == "") { $row[menu_fontcolor] = "FFFFFF"; }
if ($row[menu_fontface] == "")  { $row[menu_fontface] = "Arial";   }
if ($row[menu_fontsize] == "")  { $row[menu_fontsize] = "2";       }
if ($row[menu_columns] == "")   { $row[menu_columns] = "1";        }
echo "<body ";
if ($row[menuwallpaper] != '' and $row[menuwallpaper] != 'none')
	{ echo "background='wallpaper/", $row[menuwallpaper], "' "; }
echo "bgcolor='#", $row[menu_bgcolor], "' text='#", $row[menu_fontcolor], "' link='#", $row[menu_fontcolor], "' vlink='#", $row[menu_fontcolor], "'>";
if ($menumode == 'list' or ($menumode == 'user' and $row[menumode] == 'list')) { echo ""; } else { echo "<center>"; }
if ($menumode == 'list' or ($menumode == 'user' and $row[menumode]=='list')) { $row[menu_columns] = '1'; }
echo "<font face='", $row[menu_fontface], "' size='", $row[menu_fontsize], "'>";
if($BHCIntranet != getenv(REMOTE_ADDR))
	{
        echo "<a href='index.php?logout=y' target='_top'><font size='1'>LOGOUT</font></a>";
	}
if ($menumode == 'list' or ($menumode == 'user' and $row[menumode] == 'list'))
	{
	echo "<ul>";
	} else { echo "<table width='100%' border='0' cellpadding='2' cellspacing='2'><tr><td align='center'>"; }
$nummenuitems = 0;
if ($shownews == 'yes')      { $nummenuitems++; $showapp[1] = 'y'; }
if ($showcalendar == 'yes')  { $nummenuitems++; $showapp[2] = 'y'; }
if ($showrolodex == 'yes')   { $nummenuitems++; $showapp[3] = 'y'; }
if ($showcontact == 'yes')   { $nummenuitems++; $showapp[4] = 'y'; }
if ($shownetwork == 'yes')   { $nummenuitems++; $showapp[5] = 'y'; }
if ($showtasklist == 'yes')  { $nummenuitems++; $showapp[6] = 'y'; }
if ($showtimesheet == 'yes') { $nummenuitems++; $showapp[7] = 'y'; }
if ($showtsadmin == 'yes')   { $nummenuitems++; $showapp[8] = 'y'; }
if ($showsurvey == 'yes')    { $nummenuitems++; $showapp[9] = 'y'; }
if ($showadmin == 'yes')     { $showsetup = 'yes'; } //  THIS APP HAS BECOME PART OF THE SETUP APP
if ($showsetup == 'yes')     { $nummenuitems++; $showapp[10] = 'y'; }
if ($shownews == 'user' and $row[want_news] == 'y')           { $nummenuitems++; $showapp[1] = 'y'; }
if ($showcalendar == 'user' and $row[want_calendar] == 'y')   { $nummenuitems++; $showapp[2] = 'y'; }
if ($showrolodex == 'user' and $row[want_rolodex] == 'y')     { $nummenuitems++; $showapp[3] = 'y'; }
if ($showcontact == 'user' and $row[want_contact] == 'y')     { $nummenuitems++; $showapp[4] = 'y'; }
if ($shownetwork == 'user' and $row[want_network] == 'y')     { $nummenuitems++; $showapp[5] = 'y'; }
if ($showtasklist == 'user' and $row[want_tasklist] == 'y')   { $nummenuitems++; $showapp[6] = 'y'; }
if ($showtimesheet == 'user' and $row[want_timesheet] == 'y') { $nummenuitems++; $showapp[7] = 'y'; }
if ($showtsadmin == 'user' and $row[want_timesheetcp] == 'y') { $nummenuitems++; $showapp[8] = 'y'; }
if ($showsurvey == 'user' and $row[want_survey] == 'y')       { $nummenuitems++; $showapp[9] = 'y'; }
if ($showadmin == 'user' and $row[want_admin] == 'y')         { $showsetup = 'user'; $row[want_setup] = 'y'; } //  THIS APP HAS BECOME PART OF THE SETUP APP
if ($showsetup == 'user' and $row[want_setup] == 'y')         { $nummenuitems++; $showapp[10] = 'y'; }
if ($shownews == 'restrict' and $row[perm_news] == 'y' and $row[want_news] == 'y')           { $nummenuitems++; $showapp[1] = 'y'; }
if ($showcalendar == 'restrict' and $row[perm_calendar] == 'y' and $row[want_calendar] == 'y')   { $nummenuitems++; $showapp[2] = 'y'; }
if ($showrolodex == 'restrict' and $row[perm_rolodex] == 'y' and $row[want_rolodex] == 'y')     { $nummenuitems++; $showapp[3] = 'y'; }
if ($showcontact == 'restrict' and $row[perm_contact] == 'y' and $row[want_contact] == 'y')     { $nummenuitems++; $showapp[4] = 'y'; }
if ($shownetwork == 'restrict' and $row[perm_network] == 'y' and $row[want_network] == 'y')     { $nummenuitems++; $showapp[5] = 'y'; }
if ($showtasklist == 'restrict' and $row[perm_tasklist] == 'y' and $row[want_tasklist] == 'y')   { $nummenuitems++; $showapp[6] = 'y'; }
if ($showtimesheet == 'restrict' and $row[perm_timesheet] == 'y' and $row[want_timesheet] == 'y') { $nummenuitems++; $showapp[7] = 'y'; }
if ($showtsadmin == 'restrict' and $row[perm_timesheetcp] == 'y' and $row[want_timesheetcp] == 'y') { $nummenuitems++; $showapp[8] = 'y'; }
if ($showsurvey == 'restrict' and $row[perm_survey] == 'y' and $row[want_survey] == 'y') { $nummenuitems++; $showapp[9] = 'y'; }
if ($showadmin == 'restrict' and $row[perm_admin] == 'y' and $row[want_admin] == 'y')         { $showsetup = 'restrict'; $row[perm_setup] = 'y'; $row[want_setup] = 'y'; } //  THIS APP HAS BECOME PART OF THE SETUP APP
if ($showsetup == 'restrict' and $row[perm_setup] == 'y' and $row[want_setup] == 'y')         { $nummenuitems++; $showapp[10] = 'y'; }
$x = $nummenuitems % 2; if ($x != 0) { $oddnumber = 'yes'; }
$column = $nummenuitems / 2; if (strpos($column, ".")) { $column = $column - 0.5; }

$app[1] = "<a href='news.php' target='content'>";
if ($menumode == 'norm' or $menumode == 'icon' or ($menumode == 'user' and ($row[menumode] == 'icon' or $row[menumode] == 'norm'))) { $app[1] = $app[1] . "<img src='" . $newsicon . "' border='0'>"; }
if ($menumode == 'norm' or ($menumode == 'user' and $row[menumode]=='norm')) { $app[1] = $app[1] . "<br>"; }
if ($menumode == "icon" or ($menumode == "user" and $row[menumode] =='icon')) { $app[1] = $app[1] . "<p>"; } else { $app[1] = $app[1] . "News"; }
$app[1] = $app[1] . "</a>";

$app[2] = "<a href='calendar/' target='content'>";
if ($menumode == 'norm' or $menumode == 'icon' or ($menumode == 'user' and ($row[menumode] == 'icon' or $row[menumode] == 'norm'))) { $app[2] = $app[2] . "<img src='" . $calendaricon . "' border='0'>"; }
if ($menumode == 'norm' or ($menumode == 'user' and $row[menumode]=='norm')) { $app[2] = $app[2] . "<br>"; }
if ($menumode == "icon" or ($menumode == "user" and $row[menumode] =='icon')) { $app[2] = $app[2] . "<p>"; } else { $app[2] = $app[2] . "Calendar"; }
$app[2] = $app[2] . "</a>";

$app[3] = "<a href='rolodex.php' target='content'>";
if ($menumode == 'norm' or $menumode == 'icon' or ($menumode == 'user' and ($row[menumode] == 'icon' or $row[menumode] == 'norm'))) { $app[3] = $app[3] . "<img src='" . $rolodexicon . "' border='0'>"; }
if ($menumode == 'norm' or ($menumode == 'user' and $row[menumode]=='norm')) { $app[3] = $app[3] . "<br>"; }
if ($menumode == "icon" or ($menumode == "user" and $row[menumode] =='icon')) { $app[3] = $app[3] . "<p>"; } else { $app[3] = $app[3] . "Rolodex"; }
$app[3] = $app[3] . "</a>";

$app[4] = "<a href='contact.php' target='content'>";
if ($menumode == 'norm' or $menumode == 'icon' or ($menumode == 'user' and ($row[menumode] == 'icon' or $row[menumode] == 'norm'))) { $app[4] = $app[4] . "<img src='" . $contacticon . "' border='0'>"; }
if ($menumode == 'norm' or ($menumode == 'user' and $row[menumode]=='norm')) { $app[4] = $app[4] . "<br>"; }
if ($menumode == "icon" or ($menumode == "user" and $row[menumode] =='icon')) { $app[4] = $app[4] . "<p>"; } else { $app[4] = $app[4] . "Contact Log"; }
$app[4] = $app[4] . "</a>";

$app[5] = "<a href='network.php' target='content'>";
if ($menumode == 'norm' or $menumode == 'icon' or ($menumode == 'user' and ($row[menumode] == 'icon' or $row[menumode] == 'norm'))) { $app[5] = $app[5] . "<img src='" . $networkicon . "' border='0'>"; }
if ($menumode == 'norm' or ($menumode == 'user' and $row[menumode]=='norm')) { $app[5] = $app[5] . "<br>"; }
if ($menumode == "icon" or ($menumode == "user" and $row[menumode] =='icon')) { $app[5] = $app[5] . "<p>"; } else { $app[5] = $app[5] . "Network"; }
$app[5] = $app[5] . "</a>";

$app[6] = "<a href='tasklist.php' target='content'>";
if ($menumode == 'norm' or $menumode == 'icon' or ($menumode == 'user' and ($row[menumode] == 'icon' or $row[menumode] == 'norm'))) { $app[6] = $app[6] . "<img src='" . $tasklisticon . "' border='0'>"; }
if ($menumode == 'norm' or ($menumode == 'user' and $row[menumode]=='norm')) { $app[6] = $app[6] . "<br>"; }
if ($menumode == "icon" or ($menumode == "user" and $row[menumode] =='icon')) { $app[6] = $app[6] . "<p>"; } else { $app[6] = $app[6] . "Task List"; }
$app[6] = $app[6] . "</a>";

$app[7] = "<a href='timesheet.php' target='content'>";
if ($menumode == 'norm' or $menumode == 'icon' or ($menumode == 'user' and ($row[menumode] == 'icon' or $row[menumode] == 'norm'))) { $app[7] = $app[7] . "<img src='" . $timesheeticon . "' border='0'>"; }
if ($menumode == 'norm' or ($menumode == 'user' and $row[menumode]=='norm')) { $app[7] = $app[7] . "<br>"; }
if ($menumode == "icon" or ($menumode == "user" and $row[menumode] =='icon'))
	{
	$app[7] = $app[7] . "<p>";
	} else
		{
		if ($showapp[8] == 'y')
			{
			$app[7] = $app[7] . "My ";
			}
			$app[7] = $app[7] . "Time Sheet</a>";
		}
$app[7] = $app[7] . "</a>";

$app[8] = "<a href='timesheetcp.php' target='content'>";
if ($menumode == 'norm' or $menumode == 'icon' or ($menumode == 'user' and ($row[menumode] == 'icon' or $row[menumode] == 'norm'))) { $app[8] = $app[8] . "<img src='" . $timesheetcpicon . "' border='0'>"; }
if ($menumode == 'norm' or ($menumode == 'user' and $row[menumode]=='norm')) { $app[8] = $app[8] . "<br>"; }
if ($menumode == "icon" or ($menumode == "user" and $row[menumode] =='icon')) { $app[8] = $app[8] . "<p>"; } else { $app[8] = $app[8] . "Time Sheets"; }
$app[8] = $app[8] . "</a>";

$app[9] = "<a href='survey.php' target='content'>";
if ($menumode == 'norm' or $menumode == 'icon' or ($menumode == 'user' and ($row[menumode] == 'icon' or $row[menumode] == 'norm'))) { $app[9] = $app[9] . "<img src='" . $surveyicon . "' border='0'>"; }
if ($menumode == 'norm' or ($menumode == 'user' and $row[menumode]=='norm')) { $app[9] = $app[9] . "<br>"; }
if ($menumode == "icon" or ($menumode == "user" and $row[menumode] =='icon')) { $app[9] = $app[9] . "<p>"; } else { $app[9] = $app[9] . "Survey"; }
$app[9] = $app[9] . "</a>";

// App 10 was "admin" which is now part of setup.php

if ($oddnumber == 'yes' and $row[menu_columns] != 1) { $setupicon = $setupiconalt; }

$app[10] = "<a href='setup.php' target='content'>";
if ($menumode == 'norm' or $menumode == 'icon' or ($menumode == 'user' and ($row[menumode] == 'icon' or $row[menumode] == 'norm'))) { $app[10] = $app[10] . "<img src='" . $setupicon . "' border='0'>"; }
if ($menumode == 'norm' or ($menumode == 'user' and $row[menumode]=='norm')) { $app[10] = $app[10] . "<br>"; }
if ($menumode == "icon" or ($menumode == "user" and $row[menumode] =='icon'))
	{
	$app[10] = $app[10] . "<p>";
	} else
		{
		if ($oddnumber != 'yes' or $row[menu_columns] == 1)
			{
			$app[10] = $app[10] . "Setup";
			} else
				{
				$app[10] = "&nbsp;<br>" . $app[10];
				}
		}
$app[11] = $app[11] . "</a>";

//echo "-", $menumode, "o", $row[menumode];
if ($menumode == 'list' or ($menumode=='user' and $row[menumode] == 'list'))
	{
	$r=1;
	//echo $r;
	while($r <= 10)
		{
		//echo $r;
       	 	$app[$r] = "<li>" . $app[$r];
		$r++;
		}
	}
echo "<font face='", $row[menu_fontface], "' size='", $row[menu_fontsize], "'>";

$j = 1; $q = 1; if ($oddnumber == 'yes' and $row[menu_columns] != 1) { $tableditems = $column * 2; } else {$tableditems = $nummenuitems; }
while ($j <= 10)
	{
	if ($j == 10 and $oddnumber == 'yes' and $row[menu_columns] != 1) { $skipme = 'yes'; } else { $skipme = 'no'; }
        if ($showapp[$j] == 'y' and $skipme != 'yes')
		{
                echo $app[$j];
		if ($menumode == 'list' or ($menumode=='user' and $row[menumode] == 'list'))
			{ echo ""; } else { echo "<p>"; }
		if ($q == $column and $q != $tableditems and $row[menu_columns] != 1)
			{
                	echo "</td><td align='center'><font face='", $row[menu_fontface], "' size='", $row[menu_fontsize], "'>";
 			}
		$q++;
		}
	$j++;
	}
if ($menumode == 'list' or ($menumode == 'user' and $row[menumode] == 'list'))
	{
	echo "</ul>";
	} else { echo "</td></tr></table>"; }
if ($row[menu_columns] != 1 and $tableditems < $nummenuitems)
	{
        echo $app[10];
	}
echo "</font></center>";

echo "</body></html>";
/*
echo "j:", $j, ",q:", $q, ",ti:",  $tableditems;
echo "<br>nmi:", $nummenuitems, ",on:", $oddnumber, ",mc:", $row[menu_columns], ",c:", $column;
echo "<br>ss:", $showsetup, ",s9:", $showapp[9];
*/
}
?>
