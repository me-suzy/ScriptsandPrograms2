<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}

$date=date("Y-m-d");

$year = substr("$date", 0, 4);
$month = substr("$date", 5,-3);
$day = substr("$date", 8);

$nextyear = $year + 1;



	if ($_POST['submit'] == 'submit') {
		$opp_team = $_POST['opposition'];
		$ground = $_POST['ground'];
		$home_away = $_POST['home_away'];
		$matchmonth = $_POST['matchmonth'];
		$matchyear = $_POST['matchyear'];
		$matchday = $_POST['matchday'];
		$kickoffmin = $_POST['kickoffmin'];
		$kickoffhour = $_POST['kickoffhour'];
		$matchtype = $_POST['matchtype'];
		$strip = $_POST['strip'];
		$notes = $_POST['notes'];


$matchdate = ("$matchyear-$matchmonth-$matchday");
$matchtime = ("$kickoffhour:$kickoffmin");

$query="INSERT INTO fixtures (opp_team, ground, fix_date, fix_time, home_away, match_type, notes) 
VALUES ('$opp_team', '$ground', '$matchdate', '$matchtime', '$home_away', '$matchtype', '$notes') ";
mysql_query($query); 

?><meta HTTP-EQUIV="Refresh" CONTENT="0; URL=admin.php"><?php


	} 



?>

<HTML>
<HEAD>
<TITLE>Add Team</TITLE>
</HEAD>
<BODY>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

<center>
<table border="0" width="60%" height="30" cellpadding="0" cellspacing="0">
<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornertopleft.png" width="5" height="25" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbacktop.png" width="100%" height="25" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornertopright.png" width="5" height="25" alt=""></td>
</tr>

<tr bgcolor="#<?php echo "$col_table_header"; ?>">
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxleft.png" width="5" height="100%" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="25" alt=""></td>
</tr>

<tr bgcolor="#<?php echo "$col_table_header"; ?>">
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxleft.png" width="5" height="100%" alt=""></td>
<td width="100%" bgcolor="#<?php echo "$col_table_header"; ?>" align="center">
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Enter Match Fixture Details</b><br><br>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>






<table border="0" width="60%" height="30" cellpadding="0" cellspacing="0">
<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornertopleft.png" width="5" height="25" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbacktop.png" width="100%" height="25" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornertopright.png" width="5" height="25" alt=""></td>
</tr>



<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxleft.png" width="5" height="100%" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="25" alt=""></td>
</tr>



<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxleft.png" width="5" height="100%" alt=""></td>
<td width="100%" bgcolor="#<?php echo "$col_back"; ?>" align="center">
<?php echo "<font color='#$col_text'>"; ?>

<?php
$dbQuery = " SELECT * ";
$dbQuery .= "FROM teams WHERE own_team != 'yes' "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
?>

Select Team Opposition<br>

<SELECT NAME="opposition">
<?php
while($row = mysql_fetch_array($result))
print "<OPTION VALUE=\"$row[1]\">$row[1]</OPTION>\n";
?>
</select>


<br><BR>
Home Or Away ?
<BR>
<?php
echo '<select name="home_away">'; 
echo '<option value="home">Home</option>'; 
echo '<option value="away">Away</option>';  
?>
</select>

<br><BR>
Ground Playing At<br>
<input id="ground" size="50" name="ground"><br>
<br>


Date Of Match
<br>

<?php
echo "<select name=\"matchday\">"; 
echo "<option value=\"01\">1</option> "; 
echo "<option value=\"02\">2</option>";
echo "<option value=\"03\">3</option>"; 
echo "<option value=\"04\">4</option>"; 
echo "<option value=\"05\">5</option>"; 
echo "<option value=\"06\">6</option>"; 
echo "<option value=\"07\">7</option>"; 
echo "<option value=\"08\">8</option>"; 
echo "<option value=\"09\">9</option>"; 
echo "<option value=\"10\">10</option>"; 
echo "<option value=\"11\">11</option>"; 
echo "<option value=\"12\">12</option>"; 
echo "<option value=\"13\">13</option>"; 
echo "<option value=\"14\">14</option>"; 
echo "<option value=\"15\">15</option>"; 
echo "<option value=\"16\">16</option>"; 
echo "<option value=\"17\">17</option>"; 
echo "<option value=\"18\">18</option>"; 
echo "<option value=\"19\">19</option>"; 
echo "<option value=\"20\">20</option>"; 
echo "<option value=\"21\">21</option>"; 
echo "<option value=\"22\">22</option>"; 
echo "<option value=\"23\">23</option>"; 
echo "<option value=\"24\">24</option>"; 
echo "<option value=\"25\">25</option>"; 
echo "<option value=\"26\">26</option>"; 
echo "<option value=\"27\">27</option>"; 
echo "<option value=\"28\">28</option>"; 
echo "<option value=\"29\">29</option>"; 
echo "<option value=\"30\">30</option>"; 
echo "<option value=\"31\">31</option>"; 
 
echo "</select> "; 


echo "<select name=\"matchmonth\">"; 
echo "<option value=\"01\">Jan</option> "; 
echo "<option value=\"02\">Feb</option>"; 
echo "<option value=\"03\">Mar</option>";
echo "<option value=\"04\">Apr</option>";
echo "<option value=\"05\">May</option>";
echo "<option value=\"06\">Jun</option>";
echo "<option value=\"07\">Jul</option>";
echo "<option value=\"08\">Aug</option>";
echo "<option value=\"09\">Sep</option>";
echo "<option value=\"10\">Oct</option>";
echo "<option value=\"11\">Nov</option>";
echo "<option value=\"12\">Dec</option>";
echo "</select> "; 

echo "<select name=\"matchyear\">"; 
echo "<option value=\"$year\">$year</option>"; 
echo "<option value=\"$nextyear\">$nextyear</option>";

echo "</select> "; 

?>
<br>
<br>Kick Off Time<br>
<?php

echo "<select name=\"kickoffhour\">"; 
echo "<option value=\"TBA\">TBA</option> "; 
echo "<option value=\"01\">01</option> "; 
echo "<option value=\"02\">02</option>"; 
echo "<option value=\"03\">03</option>";
echo "<option value=\"04\">04</option>";
echo "<option value=\"05\">05</option>";
echo "<option value=\"06\">06</option>";
echo "<option value=\"07\">07</option>";
echo "<option value=\"08\">08</option>";
echo "<option value=\"09\">09</option>";
echo "<option value=\"10\">10</option>";
echo "<option value=\"11\">11</option>";
echo "<option value=\"12\">12</option>";
echo "<option value=\"13\">13</option> "; 
echo "<option value=\"14\">14</option>"; 
echo "<option value=\"15\">15</option>";
echo "<option value=\"16\">16</option>";
echo "<option value=\"17\">17</option>";
echo "<option value=\"18\">18</option>";
echo "<option value=\"19\">19</option>";
echo "<option value=\"20\">20</option>";
echo "<option value=\"21\">21</option>";
echo "<option value=\"22\">22</option>";
echo "<option value=\"23\">23</option>";
echo "<option value=\"24\">24</option>";
echo "</select> "; 

echo "<select name=\"kickoffmin\">"; 
echo "<option value=\" \"> </option> "; 
echo "<option value=\"00\">00</option> "; 
echo "<option value=\"05\">05</option>"; 
echo "<option value=\"10\">10</option>";
echo "<option value=\"15\">15</option>";
echo "<option value=\"20\">20</option>";
echo "<option value=\"25\">25</option>";
echo "<option value=\"30\">30</option>";
echo "<option value=\"35\">35</option>";
echo "<option value=\"40\">40</option>";
echo "<option value=\"45\">45</option>";
echo "<option value=\"50\">50</option>";
echo "<option value=\"55\">55</option>";
echo "</select> "; 


$dbQuery = " SELECT * ";
$dbQuery .= "FROM matchtypes "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
?>

<br><br>Select Match Type<br>

<SELECT NAME="matchtype">
<?php
while($row = mysql_fetch_array($result))
print "<OPTION VALUE=\"$row[2]\">$row[1]</OPTION>\n";
?>
</select>









<br>
<br>
Strip Playing In<br>
<input id="strip" size="50" name="strip"><br>
<br>
Additional notes<br>
<textarea rows=4 cols=50 name="notes"></textarea><br>


<br><br>

</td>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>
</font>
<br>
<input type="Submit" name="submit" value="submit">
</form>
</H4>

</BODY> 
</HTML>

