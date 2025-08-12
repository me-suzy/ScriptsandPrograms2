<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}
$date=date("Y-m-d");

$year = substr("$date", 0, 4);
$nextyear = $year - 1;


?><font color="#<?php echo $col_text ?>"><?php


// if ($_POST['submit'] == 'submit') {
if (isset($_POST['submit'])) { 



$matchmonth = $_POST['matchmonth'];
$matchyear = $_POST['matchyear'];
$matchday = $_POST['matchday'];
$team1 = $_POST['team1'];
$score1 = $_POST['score1'];
$team2 = $_POST['team2'];
$score2 = $_POST['score2'];
$match_type = $_POST['match_type'];

$matchdate = ("$matchyear-$matchmonth-$matchday");

if ($team1 == $team2) {
	die ('You must select 2 different teams!');
		}

	if ($score1 > $score2) {
	$homepoints = '3'; $awaypoints = '0'; $homewin = 'W'; $awaywin = 'L';
	}
	
	if ($score1 < $score2) {
	$homepoints = '0'; $awaypoints = '3'; $homewin = 'L'; $awaywin = 'W';
	}
		if ($score1 == $score2) {
	$homepoints = '1'; $awaypoints = '1'; $homewin = 'D'; $awaywin = 'D';
	}





$query="INSERT INTO league_table (team_name, opp_team, w_d_l, points, home_away, match_type, goals_for, goals_against, match_tag, match_date) 
VALUES ('$team1', '$team2', '$homewin', '$homepoints', 'home', '$match_type', '$score1', '$score2', 'yes', '$matchdate') ";
mysql_query($query); 

$query="INSERT INTO league_table (team_name, opp_team, w_d_l, points, home_away, match_type, goals_for, goals_against, match_date) 
VALUES ('$team2', '$team1', '$awaywin', '$awaypoints', 'away', '$match_type', '$score2', '$score1', '$matchdate') ";
mysql_query($query); 



?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=admin.php"> <?php

}
?>

<br>

<html>

<head>

<title>Update Results</title>
</head>

<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">



<center>
<table border="0" width="50%" height="30" cellpadding="0" cellspacing="0">
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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Enter Match Result</b><br><br>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>
<div align="center">
  <center>


<?php
$query="SELECT * FROM teams WHERE own_team != 'yes'";
$result=mysql_query($query);

?>
<br>
Enter Match results<br>
Home team then Away team.
<BR><BR>

<SELECT NAME="team1">
<?php
while($row = mysql_fetch_array($result)) 
print "<OPTION VALUE=\"$row[1]\">$row[1]</OPTION>\n";
?>
</select>
Score:
<input id="score1" size="1" name="score1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


<?php
$query="SELECT * FROM teams WHERE own_team != 'yes'";
$result=mysql_query($query);
?>

VS &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

 <SELECT NAME="team2">
<?php
while($row = mysql_fetch_array($result)) 
print "<OPTION VALUE=\"$row[1]\">$row[1]</OPTION>\n";
?>
</select>
Score:
<input id="score2" size="1" name="score2"><br>

<BR>

<?php
$query="SELECT matchtype, match_cat FROM matchtypes ";
$result=mysql_query($query);

?>
Enter Match Type<br>

<SELECT NAME="match_type">
<?php
while($row = mysql_fetch_array($result)) 
print "<OPTION VALUE=\"$row[1]\">$row[0]</OPTION>\n";
?>
</select>

<br><br>
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



<BR>
<center><br><input type="Submit" name="submit" value="Submit Final Result">

  </center>
</body>

</html>