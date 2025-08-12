<?php
include "header.php";
$todaysdate = date('Y-m-d');

$position = '1';

$league = 'league';

$query="SELECT * FROM seasons ";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
										{
$season_start = $row["season_start"];
$season_end = $row["season_end"];

if ($todaysdate > "$season_start" AND $todaysdate < "$season_end") {
		$seasonstart = "$season_start";
		$seasonend = "$season_end";
							}							

							}


$query="SELECT * FROM seasons WHERE season_start = '$seasonstart' AND season_end = '$seasonend' ";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))		{
$season_name = $row["season_name"];

							}


$query="SELECT * FROM matchtypes WHERE match_cat = 'league'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$matchtype = $row["matchtype"];
$division = $row["division"];
}

?>

<br>

<html>

<head>

<title>Team List</title>
</head>

<center>
<table border="0" width="95%" height="30" cellpadding="0" cellspacing="0">
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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>League Table<BR>Season: <?php echo " $season_name" ?><BR>League: <?php echo " $matchtype" ?><br>Division: <?php echo " $division" ?><BR><br></b>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>
<div align="center">
  <center>


<table border="1" cellspacing="1" bgcolor="#<?php echo "$col_table_header_2" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="95%" id="AutoNumber1">
   <tr>
      <td width="4%" rowspan="2" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Pos</font></td>
      <td width="35%" rowspan="2" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Team Name</font></td>
      <td width="4%" rowspan="2" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Pld</font></td>
	
      <td width="4%" rowspan="2" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">F</font></td>
      <td width="4%" rowspan="2" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">A</font></td>
     
	<td width="4%" rowspan="2" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">W</font></td>
      <td width="4%" rowspan="2" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">D</font></td>
      <td width="4%" rowspan="2" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">L</font></td>
      <td width="22%" colspan="3" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Home</font></td>
      <td width="22%" colspan="3" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Away</font></td>
 <td width="4%" rowspan="2" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">GD</font></td>
<td width="4%" rowspan="2" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Points</font></td>
    </tr>
    <tr>
      <td width="4%" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">W</font></td>
      <td width="4%" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">D</font></td>
      <td width="4%" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">L</font></td>
      <td width="4%" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">W</font></td>
      <td width="4%" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">D</font></td>
      <td width="4%" align="center"bgcolor="#<?php echo "$col_table_header_2" ?>"><font color="#<?php echo "$col_table_header_text" ?>">L</font></td>
    </tr>

<?php
//---------------------------------
// Select all teams from database (this will be used to set the teams DB with total points for sorting)
//---------------------------------
$dbQuery = "SELECT team_name "; 
$dbQuery .= "FROM teams ORDER BY points_total DESC"; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result)) {
$teamname = $row["team_name"];
//echo "$teamname";

//-------------------------------
// select total points for team
//-------------------------------
$getpoints = "SELECT sum(points) as points FROM league_table WHERE team_name = '$teamname' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend' "; 
$pointsresult = mysql_query($getpoints) or die(mysql_erroe());

$points= mysql_result($pointsresult, 0, "points");

	if ($points == '') {
		$points = '0';
	}

//-------------------------------
// select total goals for team
//-------------------------------
$dbQuery = "SELECT goals_for "; 
$dbQuery .= "FROM league_table WHERE team_name = '$teamname' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend'  "; 
$goals_for = mysql_query($dbQuery) or die("Couldn't get file list");


$goalsfor = "SELECT sum(goals_for) as goalsf FROM league_table WHERE team_name = '$teamname' AND match_type='$league'"; 
$goalsfresult = mysql_query($goalsfor) or die(mysql_erroe());

$gfor= mysql_result($goalsfresult, 0, "goalsf");


//-------------------------------
// select total goals against for team - to insert into teams for sorting leaguetable
//-------------------------------
$dbQuery = "SELECT goals_against "; 
$dbQuery .= "FROM league_table WHERE team_name = '$teamname' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend' "; 
$goals_against = mysql_query($dbQuery) or die("Couldn't get file list");


$goalsagainst = "SELECT sum(goals_against) as goalsa FROM league_table WHERE team_name = '$teamname' AND match_type='$league'"; 
$goalsaresult = mysql_query($goalsagainst) or die(mysql_erroe());

$gagainst= mysql_result($goalsaresult, 0, "goalsa");

if ($gfor == '') {
		$gfor = '0';
	}

if ($gagainst == '') {
		$gagainst = '0';
	}

$gd = ("$gfor" - "$gagainst");





//-----------------------------------------------------
// insert total points into team table for sorting purpose
//-----------------------------------------------------

$query="UPDATE teams SET points_total='$points', goaldiff='$gd' WHERE team_name='$teamname'"; 
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 

					}


//---------------------------------
// Select all teams from database - This now starts the selecting for populating the league table
//---------------------------------
$dbQuery = "SELECT team_name, own_team "; 
$dbQuery .= "FROM teams ORDER BY points_total DESC, goaldiff DESC"; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result)) {
$teamname = $row["team_name"];
$own_team = $row["own_team"];


//-------------------------------
// select total points for team
//-------------------------------
$getpoints = "SELECT sum(points) as points FROM league_table WHERE team_name = '$teamname' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend'  "; 
$pointsresult = mysql_query($getpoints) or die(mysql_erroe());

$points= mysql_result($pointsresult, 0, "points");

	if ($points == '') {
		$points = '0';
	}






//-------------------------------
// select number of wins
//-------------------------------
$dbQuery = "SELECT w_d_l "; 
$dbQuery .= "FROM league_table WHERE team_name = '$teamname' AND w_d_l = 'W' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend'  "; 
$w_d_l = mysql_query($dbQuery) or die("Couldn't get file list");
$win=mysql_numrows($w_d_l);

//-------------------------------
// select number of losses
//-------------------------------
$dbQuery = "SELECT w_d_l "; 
$dbQuery .= "FROM league_table WHERE team_name = '$teamname' AND w_d_l = 'L' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend' "; 
$w_d_l = mysql_query($dbQuery) or die("Couldn't get file list");
$lose=mysql_numrows($w_d_l);

//-------------------------------
// select number of draws
//-------------------------------
$dbQuery = "SELECT w_d_l "; 
$dbQuery .= "FROM league_table WHERE team_name = '$teamname' AND w_d_l = 'D' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend' "; 
$w_d_l = mysql_query($dbQuery) or die("Couldn't get file list");
$draw=mysql_numrows($w_d_l);

//-------------------------------
// select number of home wins
//-------------------------------
$dbQuery = "SELECT w_d_l "; 
$dbQuery .= "FROM league_table WHERE team_name = '$teamname' AND w_d_l = 'W' AND home_away='home' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend' "; 
$w_d_l = mysql_query($dbQuery) or die("Couldn't get file list");
$homewin=mysql_numrows($w_d_l);

//-------------------------------
// select number of home losses
//-------------------------------
$dbQuery = "SELECT w_d_l "; 
$dbQuery .= "FROM league_table WHERE team_name = '$teamname' AND w_d_l = 'L' AND home_away='home' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend'  "; 
$w_d_l = mysql_query($dbQuery) or die("Couldn't get file list");
$homelose=mysql_numrows($w_d_l);

//-------------------------------
// select number of home draws
//-------------------------------
$dbQuery = "SELECT w_d_l "; 
$dbQuery .= "FROM league_table WHERE team_name = '$teamname' AND w_d_l = 'D' AND home_away='home' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend' "; 
$w_d_l = mysql_query($dbQuery) or die("Couldn't get file list");
$homedraw=mysql_numrows($w_d_l);

//-------------------------------
// select number of away wins
//-------------------------------
$dbQuery = "SELECT w_d_l "; 
$dbQuery .= "FROM league_table WHERE team_name = '$teamname' AND w_d_l = 'W' AND home_away='away' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend' "; 
$w_d_l = mysql_query($dbQuery) or die("Couldn't get file list");
$awaywin=mysql_numrows($w_d_l);

//-------------------------------
// select number of away losses
//-------------------------------
$dbQuery = "SELECT w_d_l "; 
$dbQuery .= "FROM league_table WHERE team_name = '$teamname' AND w_d_l = 'L' AND home_away='away'  AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend' "; 
$w_d_l = mysql_query($dbQuery) or die("Couldn't get file list");
$awaylose=mysql_numrows($w_d_l);

//-------------------------------
// select number of wins
//-------------------------------
$dbQuery = "SELECT w_d_l "; 
$dbQuery .= "FROM league_table WHERE team_name = '$teamname' AND w_d_l = 'W' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend'  "; 
$w_d_l = mysql_query($dbQuery) or die("Couldn't get file list");
$win=mysql_numrows($w_d_l);

$dbQuery = "SELECT w_d_l "; 
$dbQuery .= "FROM league_table WHERE team_name = '$teamname' AND w_d_l = 'D' AND home_away='away' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend' "; 
$w_d_l = mysql_query($dbQuery) or die("Couldn't get file list");
$awaydraw=mysql_numrows($w_d_l);

//-------------------------------
// select games played
//-------------------------------
$dbQuery = "SELECT team_name "; 
$dbQuery .= "FROM league_table WHERE team_name = '$teamname'  AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend' "; 
$pld = mysql_query($dbQuery) or die("Couldn't get file list");
$totalpld=mysql_numrows($pld);

	if ($totalpld == '') {
		$totalpld = '0';
	}




//-------------------------------
// select total goals for team
//-------------------------------
$dbQuery = "SELECT goals_for "; 
$dbQuery .= "FROM league_table WHERE team_name = '$teamname' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend'  "; 
$goals_for = mysql_query($dbQuery) or die("Couldn't get file list");


$goalsfor = "SELECT sum(goals_for) as goalsf FROM league_table WHERE team_name = '$teamname' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend' "; 
$goalsfresult = mysql_query($goalsfor) or die(mysql_erroe());

$gfor= mysql_result($goalsfresult, 0, "goalsf");


//-------------------------------
// select total goals against for team
//-------------------------------
$dbQuery = "SELECT goals_against "; 
$dbQuery .= "FROM league_table WHERE team_name = '$teamname' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend' "; 
$goals_against = mysql_query($dbQuery) or die("Couldn't get file list");


$goalsagainst = "SELECT sum(goals_against) as goalsa FROM league_table WHERE team_name = '$teamname' AND match_type='$league' AND match_date >= '$seasonstart' AND match_date <= '$seasonend' "; 
$goalsaresult = mysql_query($goalsagainst) or die(mysql_erroe());

$gagainst= mysql_result($goalsaresult, 0, "goalsa");

if ($gfor == '') {
		$gfor = '0';
	}

if ($gagainst == '') {
		$gagainst = '0';
	}

$gd = ("$gfor" - "$gagainst");

//echo "$points";
//echo "$win_w_d_l";
//echo "$lose_w_d_l";
//echo "$draw_w_d_l";


if ($bgcolor === "$col_table_row")
{
   $bgcolor = "$col_table_row2";
} else {
   $bgcolor = "$col_table_row";
} 


?>


<tr>
      <td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$position"; ?></td>

<?php
if ($own_team == 'yes') {
?>
<td width="35%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_link" ?>"><a href="teamprofile.php?fileId=<?php echo $row["team_name"]; ?>"><font size="4" color="#<?php echo $col_link ?>"><b><?php echo "$teamname" ?></b></a></td>
<?php
}
ELSE  {
?>
<td width="35%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_link" ?>"><a href="teamprofile.php?fileId=<?php echo $row["team_name"]; ?>"><font color="#<?php echo $col_link ?>"><?php echo "$teamname" ?></a></td>
<?php 
}
?>

      <td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$totalpld"; ?></td>
      
      <td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$gfor"; ?></td>
      <td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$gagainst"; ?></td>
      
      <td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$win"; ?></td>
      <td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$draw"; ?></td>
      <td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$lose"; ?></td>
      <td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$homewin"; ?></td>
      <td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$homedraw"; ?></td>
      <td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$homelose"; ?></td>
      <td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$awaywin"; ?></td>
      <td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$awaydraw"; ?></td>
      <td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$awaylose"; ?></td>


<?php if ($gd > '0') {
?>
<td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "+$gd"; ?></td>
<?php
}
ELSE {

?><td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$gd"; ?></td>
<?php
}
?>

<td width="4%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$points"; ?></td>
    </tr>





  

<?php

++$position;
}
echo "</table>";
?>
  </center>

 

</body>

</html>


