<?
/*
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
   Copyright (C) 2005 SunFrogServices.com. All rights reserved.

   PHP-TimeConversion version 1.0
   Released 2005-06-23

   This file is part of PHP-TimeConversion.

   PHP-TimeConversion is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

    PHP-TimeConversion is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with PHP-TimeConversion; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
	
	This header must remain intact
	
	Contact SunFrogServices.com at:
	http://www.SunFrogServices.com
	
++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/
?>
<?
	$connection = @mysql_connect($db_host, $db_user, $db_pass) or die("Couldn't connect.");
	$db = @mysql_select_db($db_name, $connection) or die("Couldn't select database.");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
}
.title {
	font-weight: bold;
}
-->
</style>
</head>
<body>
<?
$table_name = "your table";
$start = "your time start field";
$end = "your time end field";

if (isset($_REQUEST['submit']))
{

if ($start_timeofday == "PM")
	{$timestarttemp = ($start_hour + 12);
	$timestart2 = "$timestarttemp:$start_minute";}
	else {$timestart2 = "$start_hour:$start_minute";}
if ($end_timeofday == "PM")
	{$timeendtemp = ($end_hour + 12);
	$timeend2 = "$timeendtemp:$end_minute";}
	else {$timeend2 = "$end_hour:$end_minute";}

$result0 = mysql_query("UPDATE $table_name
		SET 
		$start = '$timestart2',
		$end = '$timeend2'		
		WHERE recordid = '$recordid'");
echo("<p><em>Data updated successfully</em></p>");
}
elseif (isset($_REQUEST['submitnew']))
{

if ($start_timeofday == "PM")
	{$timestarttemp = ($start_hour + 12);
	$timestart2 = "$timestarttemp:$start_minute";}
	else {$timestart2 = "$start_hour:$start_minute";}
if ($end_timeofday == "PM")
	{$timeendtemp = ($end_hour + 12);
	$timeend2 = "$timeendtemp:$end_minute";}
	else {$timeend2 = "$end_hour:$end_minute";}

$result2 = mysql_query("INSERT INTO $table_name (recordid,$start,$end)
					VALUES ('','$timestart2','$timeend2')");
}

elseif (isset($_REQUEST['delete']))
{
$result3 = mysql_query("delete from $table_name WHERE recordid = '$recordid'");
}
else {}


// manage existing entries

print("<h1>Update Existing Entries</h1>");
print("<table width=100% cellpadding=5 cellspacing=0>");

$result1 = mysql_query("SELECT *,
						time_format($start, '%H') as start_hour,
						time_format($start, '%i') as start_minute,
						time_format($end, '%H') as end_hour,
						time_format($end, '%i') as end_minute
						from $table_name
						");
					
while ($row1 = mysql_fetch_array($result1)) {
// add commands to format time to am/pm
if ($row1[start_hour] > 12)
	{	$start_hour2 = ($row1[start_hour] - 12);
		$start_timeofday = "PM";
	}
	else {	$start_hour2 = $row1[start_hour];
			$start_timeofday = "AM";}
			
if ($row1[end_hour] > 12)
	{	$end_hour2 = ($row1[end_hour] - 12);
		$end_timeofday = "PM";
	}
	else {	$end_hour2 = $row1[end_hour];
			$end_timeofday = "AM";}

   print("<form action=$PHP_SELF method=\"post\"><input type=\"hidden\" name=\"recordid\" value=\"$row1[recordid]\">");
   print("<table width=100% border=0>");
	print("<tr><td><p class=title>Start Time:</p></td>");
   print("<td><p><select name=\"start_hour\">
                      <option selected>$start_hour2</option>   
                      <option value=\"01\" >1</option>
                      <option value=\"02\" >2</option>
                      <option value=\"03\" >3</option>
                      <option value=\"04\" >4</option>
                      <option value=\"05\" >5</option>
                      <option value=\"06\" >6</option>
                      <option value=\"07\" >7</option>
                      <option value=\"08\" >8</option>
                      <option value=\"09\" >9</option>
                      <option value=\"10\" >10</option>
                      <option value=\"11\" >11</option>
                      <option value=\"12\" >12</option>
                    </select>
					<select name=\"start_minute\">
                      <option selected>$row1[start_minute]</option>
					  <option value=\"00\" >00</option>
                      <option value=\"05\" >05</option>
                      <option value=\"10\" >10</option>
                      <option value=\"15\" >15</option>
                      <option value=\"20\" >20</option>
                      <option value=\"25\" >25</option>
                      <option value=\"30\" >30</option>
                      <option value=\"35\" >35</option>
                      <option value=\"40\" >40</option>
                      <option value=\"45\" >45</option>
                      <option value=\"50\" >50</option>
                      <option value=\"55\" >55</option>
                    </select>
					<select name=\"start_timeofday\">
					<option selected>$start_timeofday</option>
					<option>AM</option>
					<option>PM</option>
					</select> </p>");
   print("</td></tr>");
   print("<tr><td><p class=title>End Time:</p></td>");
   print("<td><p><select name=\"end_hour\">
                      <option selected>$end_hour2</option>
					  <option value=\"01\" >1</option>
                      <option value=\"02\" >2</option>
                      <option value=\"03\" >3</option>
                      <option value=\"04\" >4</option>
                      <option value=\"05\" >5</option>
                      <option value=\"06\" >6</option>
                      <option value=\"07\" >7</option>
                      <option value=\"08\" >8</option>
                      <option value=\"09\" >9</option>
                      <option value=\"10\" >10</option>
                      <option value=\"11\" >11</option>
                      <option value=\"12\" >12</option>
                    </select>
					<select name=\"end_minute\">
                      <option selected>$row1[end_minute]</option>
					  <option value=\"00\" >00</option>
                      <option value=\"05\" >05</option>
                      <option value=\"10\" >10</option>
                      <option value=\"15\" >15</option>
                      <option value=\"20\" >20</option>
                      <option value=\"25\" >25</option>
                      <option value=\"30\" >30</option>
                      <option value=\"35\" >35</option>
                      <option value=\"40\" >40</option>
                      <option value=\"45\" >45</option>
                      <option value=\"50\" >50</option>
                      <option value=\"55\" >55</option>
                    </select>
					<select name=\"end_timeofday\">
					<option selected>$end_timeofday</option>
					<option>AM</option>
					<option>PM</option>
					</select> </p>");
   print("</td></tr>");
   print("<tr><td colspan=2 align=center><input type=submit name=submit value=\"Update\">&nbsp;<input type=reset name=reset value=\"Reset\">&nbsp;<input type=submit name=delete value=\"Delete\"><hr></td></tr>");
   print("</form>");   
}

print("</table>");


// add new entry

print("<h1>Add New Entry</h1>");
print("<form action=$PHP_SELF method=\"post\">");
print("<table width=100% cellpadding=5 cellspacing=0>");
print("	<tr><td><p class=title align=right>Start Time:</p></td>
		<td><p><select name=\"start_hour\">
                      <option value=\"01\" >1</option>
                      <option value=\"02\" >2</option>
                      <option value=\"03\" >3</option>
                      <option value=\"04\" >4</option>
                      <option value=\"05\" >5</option>
                      <option value=\"06\" >6</option>
                      <option value=\"07\" >7</option>
                      <option value=\"08\" >8</option>
                      <option value=\"09\" >9</option>
                      <option value=\"10\" selected>10</option>
                      <option value=\"11\" >11</option>
                      <option value=\"12\" >12</option>
                    </select>
					<select name=\"start_minute\">
                      <option value=\"00\" selected>00</option>
                      <option value=\"05\" >05</option>
                      <option value=\"10\" >10</option>
                      <option value=\"15\" >15</option>
                      <option value=\"20\" >20</option>
                      <option value=\"25\" >25</option>
                      <option value=\"30\" >30</option>
                      <option value=\"35\" >35</option>
                      <option value=\"40\" >40</option>
                      <option value=\"45\" >45</option>
                      <option value=\"50\" >50</option>
                      <option value=\"55\" >55</option>
                    </select>
					<select name=\"start_timeofday\">
					<option selected>AM</option>
					<option>PM</option>
					</select> </p></td></tr>
		<tr><td><p class=title align=right>End Time:</p></td>
		<td><p><select name=\"end_hour\">
                      <option value=\"01\" >1</option>
                      <option value=\"02\" >2</option>
                      <option value=\"03\" >3</option>
                      <option value=\"04\" >4</option>
                      <option value=\"05\" >5</option>
                      <option value=\"06\" >6</option>
                      <option value=\"07\" >7</option>
                      <option value=\"08\" >8</option>
                      <option value=\"09\" >9</option>
                      <option value=\"10\" >10</option>
                      <option value=\"11\" selected>11</option>
                      <option value=\"12\" >12</option>
                    </select>
					<select name=\"end_minute\">
                      <option value=\"00\" >00</option>
                      <option value=\"05\" >05</option>
                      <option value=\"10\" >10</option>
                      <option value=\"15\" >15</option>
                      <option value=\"20\" >20</option>
                      <option value=\"25\" >25</option>
                      <option value=\"30\" selected>30</option>
                      <option value=\"35\" >35</option>
                      <option value=\"40\" >40</option>
                      <option value=\"45\" >45</option>
                      <option value=\"50\" >50</option>
                      <option value=\"55\" >55</option>
                    </select>
					<select name=\"end_timeofday\">
					<option selected>AM</option>
					<option>PM</option>
					</select> </p>
					</td></tr>
		<tr><td>&nbsp;</td><td><input type=submit name=submitnew value=\"Add\"><input type=\"reset\" name=\"reset\" value=\"Clear\"></td></tr>");
print("</table></form>");

?>
</body>
</html>