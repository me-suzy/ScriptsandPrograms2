<?php
include "header.php";

$todaysdate = date('Y-m-d');


if (isset($_POST['submit'])) { 
$team = $_POST['team'];
$match_type = $_POST['match_type'];
$season = $_POST['season'];

$dbQuery = "SELECT *"; 
$dbQuery .= "FROM seasons WHERE season_name = '$season' "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))
	{
$season_start = $row["season_start"];
$season_end = $row["season_end"];

					}
						}


?>
<font color="#<?php echo $col_text ?>">

<center>


<br>

<html>

<head>

<title>Results</title>
</head>

<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">

Season:
<?php
$query="SELECT season_name FROM seasons ";
$result=mysql_query($query);
?>
<SELECT NAME="season">
<?php
while($row = mysql_fetch_array($result)) 
print "<OPTION VALUE=\"$row[0]\">$row[0]</OPTION>\n";
?>
</select>

&nbsp;&nbsp;&nbsp;&nbsp;
Team:
<?php
$query="SELECT team_name FROM teams ";
$result=mysql_query($query);

?>
<SELECT NAME="team">
<?php
while($row = mysql_fetch_array($result)) 
print "<OPTION VALUE=\"$row[0]\">$row[0]</OPTION>\n";
?>
</select>

&nbsp;&nbsp;&nbsp;&nbsp;
Match Type:
<?php
$query="SELECT matchtype, match_cat FROM matchtypes ";
$result=mysql_query($query);
?>
<SELECT NAME="match_type">
<?php
while($row = mysql_fetch_array($result)) 
print "<OPTION VALUE=\"$row[1]\">$row[0]</OPTION>\n";
?>
</select>
<center><br><input type="Submit" name="submit" value="Show Results Table">



<center>
<table border="0" width="90%" height="30" cellpadding="0" cellspacing="0">
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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Results Table <?php echo "$season"; ?></b><br><br>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>
<div align="center">
  <center>





  <table border="1" cellspacing="1" bgcolor="#<?php echo "$col_table_header" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="90%" id="AutoNumber1">
    <tr>
      

    <tr>
      <td width="11%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">View Details</td>

      <td width="20%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Team</td>
      <td width="20%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Opposition</td>
<td width="11%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Date Of Match</td>
<td width="5%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Score</td>
      <td width="8%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Match Type</td>
      <td width="8%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Home/Away</td>
	</tr>
<?php



$dbQuery = "SELECT *"; 

$dbQuery .= "FROM league_table WHERE team_name = '$team' AND match_type = '$match_type' AND match_date >= '$season_start' AND match_date <= '$season_end' "; 
$dbQuery .= "ORDER BY match_date DESC";
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))
	{


$query="SELECT team_name, own_team FROM teams WHERE team_name = '$team' ";
$result2=mysql_query($query);
while($row2 = mysql_fetch_array($result2)) 
$own_team = $row2["own_team"];

if ($bgcolor === "$col_table_row")
{
   $bgcolor = "$col_table_row2";
} else {
   $bgcolor = "$col_table_row";
} 

$score = $row["goals_for"]."-".$row["goals_against"];

$date = $row["match_date"];


$year = substr("$date", 0, 4);
$month = substr("$date", 5,-3);
$day = substr("$date", 8);




	if ($own_team == 'yes')  { ?>
    <tr>
	<td width="11%" bgcolor="#<?php echo "$bgcolor" ?>"><a href="result.php?fileId=<?php echo $row["match_id"]; ?>"><font color="#<?php echo "$col_link" ?>">View Details</td>
					<?php } 
ELSE				{ ?>

<tr>
	<td width="11%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_link" ?>">Not Available</td>

				<?php } ?>

<td width="20%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$team"; ?></td>

      <td width="20%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["opp_team"]; ?></td>
      <td width="11%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$day-$month-$year"; ?></td>
     <td width="5%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$score"; ?></td>
     <td width="8%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["match_type"]; ?></td>
     <td width="8%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["home_away"]; ?></td>







    </tr>
  

<?php
}

echo "</table>";
?>
<br>


  </center>

</body>

</html>