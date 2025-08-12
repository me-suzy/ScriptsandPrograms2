<?php

/*
********************************************

BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com

********************************************
*/

include_once "config.php";


function calendar() {

$monthNo=$_GET[monthNo];
$year=$_GET[year];

if($year < 1970) { $year=date("Y"); }
if($year > 2037) { $year=date("Y"); }

// get the year if one not provided
if (!isset($year)) {
	$year = date(Y);
}

// get the month number (1-12) if one not provided
if (!isset($monthNo)) {
	$monthNo = date(n);
}

// get current month name
$monthName = date(F);
$monthSml = date(M);

// get the number of days in this month
$daysInMonth = date(t);

// print the HTML document header
echo <<<EOF
<table  class="parent_table" align="center">
<tr><td><center><span class="cal_title">$monthSml $year</span></center></td></tr>
<tr><td>

<table align="center" cellpadding="2" cellspacing="0" border="0">
<tr class="day_names">
<th><span class="day_names_text"><font color="#FF6600">S</font></span></th>
<th><span class="day_names_text">M</span></th>
<th><span class="day_names_text">T</span></th>
<th><span class="day_names_text">W</span></th>
<th><span class="day_names_text">T</span></th>
<th><span class="day_names_text">F</span></th>
<th><span class="day_names_text">S</span></th>
</tr>
EOF;


$date_ar=getDateList();

// for each day of the month
for ($dayNo = 1; $dayNo <= $daysInMonth; $dayNo++) {
	// get the day name
	$dayName = date(D, mktime(0, 0, 0, $monthNo, $dayNo, $year));

	// if the first day of the month is not Sunday
	if ($dayNo == 1 && $dayName != "Sun") {
		// start a new row
		echo "<tr>\n";

		// get the day of the week number (0-6)
		$dayOfWeek = date(w, mktime(0, 0, 0, $monthNo, $dayNo, $year));

		// print empty table cells until we reach the first day of the month
		for ($i = 0; $i < $dayOfWeek; $i++) {
			echo "\t<td class=\"dates\"></td>\n";
		}
	}

	// if Sunday, start a new row
	if ($dayName == "Sun") {
		echo "<tr>\n";
	}

$hrf="";
$hrf_c="";

$tmp_date=$dayNo."_".$monthSml."_".$year;

// Check whether a post was made on the date.
// If yes, affign a link to it.

	if($date_ar[$tmp_date]) {
	global $c_urls;

	$hrf="<a href=\"$c_urls/index.php?show=byDate&d=$dayNo&m=$monthSml&y=$year\">";
	$hrf_c="</a>";
	$cls="date_active";
	}
	else { $cls="date_num"; }

		echo "\t<td class=\"dates\">$hrf<span class=\"$cls\">$dayNo</class>$hrf_c</td>\n";


	// if Saturday, close this row
	if ($dayName == "Sat") {
		echo "</tr>\n";
	}

	// if the last day of the month is not Saturday
	if ($dayNo == $daysInMonth && $dayName != "Sat") {
		// get the day of the week number (0-6)
		$dayOfWeek = date(w, mktime(0, 0, 0, $monthNo, $dayNo, $year));

		// print empty table cells until we reach Saturday
		for ($i = 6; $i > $dayOfWeek; $i--) {
			echo "\t<td class=\"dates\"></td>\n";
		}

		// close this row
		echo "</tr>\n";
	}
}

// close the table
echo "</table></td></tr></table>\n";
echo "<br>\n";

}
?>