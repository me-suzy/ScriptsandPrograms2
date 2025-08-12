<?php
#############################################################################
# myAgenda v1.1																#
# =============																#
# Copyright (C) 2002  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#																			#
# This program is free software. You can redistribute it and/or modify		#
# it under the terms of the GNU General Public License as published by 		#
# the Free Software Foundation; either version 2 of the License.       		#
#############################################################################
include("check.php");
?>
<html>
<head>
<META http-equiv="content-type" content="text/html; charset=windows-1254">
<META http-equiv="content-type" content="text/html; charset=<?=$CharSet;?>">
<?include("files/style.php");?>
<title><?=$myAgenda_name;?></title>
</head>
<body bgcolor="<?=$bg_color;?>">
<?
if (!$month) $month = date("n");
if (!$year) $year = date("Y");

//if ($monday) $monday=1; else $monday=0;



$prevmonth = $month-1;
$nextmonth = $month+1;
$prevyear = $year;
$nextyear = $year;
if ($prevmonth < 1) {$prevmonth = 12; $prevyear--;}
if ($nextmonth > 12) {$nextmonth = 1; $nextyear++;}
?>
<table border=0 cellspacing=0 cellpadding=1 width="320" bgcolor="#333333" align="center">
 <tr><form method="post" action="<?=$PHP_SELF;?>">
	<td>
	<table border=0 cellspacing=0 cellpadding=0 width="100%" bgcolor="#FFFFFF">
 	 <tr>
		<td>
		 <table border=0 cellpadding=2 cellspacing=2 width="100%">
		 <tr>
			<td bgcolor="#f3f3f3" align="center"><?="<font class=\"text\">".$GLOBALS['strMonthnames'][$month-1]." ".$year."</font>";?></td>
	 </tr>
	</table>

	</td>
 </tr>
</table>

	</td>
 </tr>
</table>
<img src="images/bos" width="1" height="2" border="0" alt=""><br>
<?

$dayof1st = date("w",mktime(0,0,0,$month,1,$year));
$daycount = date("t",mktime(0,0,0,$month,1,$year));


if ($monday) $dayof1st--;
if ($dayof1st < 0) $dayof1st = 6;

?>


<table border=0 cellspacing=0 cellpadding=1 width="320" bgcolor="#333333" align="center">
 <tr>
	<td>
	<table border=0 cellspacing=0 cellpadding=0 width="100%" bgcolor="#FFFFFF">
 	 <tr>
		<td>
		 <table border=0 cellpadding=2 cellspacing=2 width="100%">
<?
echo " <tr>\n";
	for ($i=0; $i<=6; $i++)
	{
		$bgcolor = ($i==5)||($i==6) ? "#FF0000" : "#C6DDFF";
		echo "	<td width=\"45\" align=\"center\" bgcolor=\"$bgcolor\"><b>".$GLOBALS['strWeekdays'][$i+$monday]."</b></td>\n"; 
	}
echo "</tr>";

$k=-$dayof1st;
	for ($j=0; $k<=$daycount; $j++)
	{
		echo " <tr>\n";
		for ($i=0; $i<=6; $i++)
		{
			$k++;
			$thisday = ($k>0) ? mktime(0,0,0,$month,$k,$year) : "";
			$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$bgcolor= $thisday == $today  ? "#aeaeae" : "#f3f3f3";
			$fontcolor = ($i==5)||($i==6) ? "#FF0000" : "#000000";
			if( $thisday >= $today )
			{
				$note = ($k>0) ?  get_notes($HTTP_COOKIE_VARS[auID], $month, $k, $year, 2) : "" ;
				$s = ( ($k>0)&&($k<=$daycount) ? "<a href=\"agenda_add.php?day=$k&month=$month&year=$year\"><font color=\"$fontcolor\">$k</font></a> $note" : "-" );
			}else{
				$s = ( ($k>0)&&($k<=$daycount) ? "<b><font color=\"$fontcolor\">$k</font></b>" : "-" );
			}
			echo "	<td align=\"center\" bgcolor=\"$bgcolor\">$s</td>\n";
		}
		echo "\n</tr>";
	}

?>
	</table>


	</td>
 </tr>
</table>

	</td>
 </tr>
</table>

<img src="images/bos" width="1" height="2" border="0" alt=""><br>
<table border=0 cellspacing=0 cellpadding=1 width="320" bgcolor="#333333" align="center">
 <tr>
	<td>
	<table border=0 cellspacing=0 cellpadding=0 width="100%" bgcolor="#FFFFFF">
 	 <tr>
		<td>
		 <table border=0 cellpadding=2 cellspacing=2 width="100%">
		 <tr>
			<td bgcolor="#f3f3f3"><font class="small"><?=$GLOBALS['strHaveNotes'];?></font></td>
	 </tr>
	</table>

	</td>
 </tr>
</table>

	</td>
 </tr>
</table>

<img src="images/bos" width="1" height="2" border="0" alt=""><br>
<table border=0 cellspacing=0 cellpadding=1 width="320" bgcolor="#333333" align="center">
 <tr><form method="post" action="<?=$PHP_SELF;?>">
	<td>
	<table border=0 cellspacing=0 cellpadding=0 width="100%" bgcolor="#FFFFFF">
 	 <tr>
		<td>
		 <table border=0 cellpadding=2 cellspacing=2 width="100%">
		 <tr>
			<td bgcolor="#f3f3f3" align="center"><Select name="month">
			<?for ($i=1; $i<=12; $i++) echo "<Option value=\"".$i."\" ".($i==$month ? "Selected" : "").">".$GLOBALS['strMonthnames'][$i-1]."\n";?>
			</SELECT> / 
			<Select name="year">
			<?for ($i=2002; $i<=2005; $i++) echo "<Option ".($i==$year ? "Selected" : "").">".$i."\n";?>
			</SELECT>&nbsp;
			<input type="submit" name="go" value="  <?=$GLOBALS['strGo'];?>  "></td>
	 </tr>
	</table>

	</td>
 </tr>
</table>

	</td>
 </tr>
</table>
</form>
<?include("files/bottom.php");?>