<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################
include("check.php");

if (!$month) $month = date("n",$CFG->TIME_OFFSET);
if (!$year) $year = date("Y",$CFG->TIME_OFFSET);

if(!$CFG->WEEK_START) {
	$monday=0;
	$weekend=0;
}else{
	$monday=1;
	$weekend=5;
}

$prevmonth = $month-1;
$nextmonth = $month+1;
$prevyear = $year;
$nextyear = $year;
if ($prevmonth < 1) {$prevmonth = 12; $prevyear--;}
if ($nextmonth > 12) {$nextmonth = 1; $nextyear++;}

$dayof1st = date("w",mktime(0,0,0,$month,1,$year));
$daycount = date("t",mktime(0,0,0,$month,1,$year));

if ($monday) $dayof1st--;
if ($dayof1st < 0) $dayof1st = 6;

$calendarTable  = "<table border=0 cellpadding=2 cellspacing=2 width=\"100%\">\n";
$calendarTable .= " <tr>\n";
	for ($i=0; $i<=6; $i++) {
		$bgcolor = ($i==$weekend)||($i==6) ? "#FF0000" : "#C6DDFF";
		$calendarTable .= "	<td width=\"14%\" align=\"center\" bgcolor=\"$bgcolor\"><b>".$LANGUAGE['strWeekdays'][$i+$monday]."</b></td>\n"; 
	}
$calendarTable .= "</tr>";

$k=-$dayof1st;
	for ($j=0; $k<=$daycount; $j++) {
		$calendarTable .= " <tr>\n";
		for ($i=0; $i<=6; $i++) {
			$k++;
			$thisday = ($k>0) ? mktime(0,0,0,$month,$k,$year) : "";
			$today = mktime(0,0,0,date("m",$CFG->TIME_OFFSET),date("d",$CFG->TIME_OFFSET),date("Y",$CFG->TIME_OFFSET));
			$bgcolor= $thisday == $today  ? "#aeaeae" : "#f3f3f3";
			$fontcolor = ($i==$weekend)||($i==6) ? "#FF0000" : "#000000";
			if( $thisday >= $today ) {
				$note = ($k>0) ?  get_notes($HTTP_COOKIE_VARS[auID], $month, $k, $year, 2) : "" ;
				$s = ( ($k>0)&&($k<=$daycount) ? "<a href=\"#\" onclick=\"addReminder($k,$month,$year)\"><font color=\"$fontcolor\">$k</font></a> $note" : "-" );
			}else{
				$s = ( ($k>0)&&($k<=$daycount) ? "<b><font color=\"$fontcolor\">$k</font></b>" : "-" );
			}
			$calendarTable .= "	<td align=\"center\" bgcolor=\"$bgcolor\">$s</td>\n";
		}
		$calendarTable .= "\n</tr>";
	}
$calendarTable .= "</table>\n";


$strMonthSelect = "<Select name=\"month\">\n";
			for ($i=1; $i<=12; $i++) {
				$strMonthSelect .= "<Option value=\"".$i."\" ".($i==$month ? "Selected" : "").">".$LANGUAGE['strMonthnames'][$i-1]."\n";
			}
$strMonthSelect .= "</Select>\n";

$strYearSelect = "<Select name=\"year\">\n";
			for ($i=date("Y",$CFG->TIME_OFFSET); $i<=date("Y",$CFG->TIME_OFFSET)+3; $i++) {
				$strYearSelect .= "<Option ".($i==$year ? "Selected" : "").">".$i."\n";
			}
$strYearSelect .= "</Select>\n";



		$con = get_file_content("templates/index.tpl");
		$con = makeUserTemplates($con);
		$trans = array(
						"{strDate}" => $LANGUAGE['strMonthnames'][$month-1]." ".$year, 
						"{calendarTable}" => $calendarTable,
						"{strMonthSelect}" => $strMonthSelect,
						"{strYearSelect}" => $strYearSelect,
						"{strHaveNotes}" => $LANGUAGE["strHaveNotes"],
						"{strGo}" => $LANGUAGE["strGo"],
						"{SELF}" => $ME
						);
		echo strtr($con, $trans);
?>
