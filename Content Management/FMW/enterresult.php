<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}
$fileId = $_GET['fileId'];

?><font color="#<?php echo $col_text ?>"><?php

$query="SELECT * FROM fixtures WHERE fix_id= '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$opp_team = $row["opp_team"];
$home_away = $row["home_away"];
$match_type = $row["match_type"];

}

$query="SELECT * FROM teams WHERE own_team = 'yes'";
$result2=mysql_query($query);
while($row2 = mysql_fetch_array($result2))
{
$ownteam = $row2["team_name"];

}

if (isset($_POST['player'])) { 

$player = $_POST['played'];
$position =$_POST['position'];
$goals =$_POST['goals'];

if ($player == '') {
	die ('You have allocated all your players') ;
			} 
$query="UPDATE users SET selected='yes' WHERE displayname='$player'"; 
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 

$query="INSERT INTO results (match_id, position, playername, goals) 
VALUES ('$fileId', '$position', '$player', '$goals') ";
mysql_query($query); 


}

if (isset($_POST['reset'])) { 

$query="UPDATE users SET selected='no' "; 
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 

mysql_query("DELETE FROM results WHERE match_id='$fileId'")
or die(mysql_error());


?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=enterresult.php<?php echo "?fileId="; echo "$fileId";?>">
<?php

					}

// if ($_POST['submit'] == 'submit') {
if (isset($_POST['submit'])) { 

$query="SELECT fix_date FROM fixtures WHERE fix_id= '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$date = $row["fix_date"];
}
$home_score = $_POST['home_score'];
$away_score = $_POST['away_score'];
$hometeam = $_POST['hometeam'];
$oppteam = $_POST['oppteam'];
$home_away = $_POST['home_away'];
$match_type = $_POST['match_type'];
$match_report = $_POST['match_report'];





	if ($home_score > $away_score) {
	$homepoints = '3'; $awaypoints = '0'; $homewin = 'W'; $awaywin = 'L';
	}
	
	if ($home_score < $away_score) {
	$homepoints = '0'; $awaypoints = '3'; $homewin = 'L'; $awaywin = 'W';
	}
		if ($home_score == $away_score) {
	$homepoints = '1'; $awaypoints = '1'; $homewin = 'D'; $awaywin = 'D';
	}

		if ($home_away == 'home') {
 	$opp_home_away = 'away';
	}
	ELSE {
	$opp_home_away = 'home';
	} 



$query="INSERT INTO league_table (team_name, opp_team, w_d_l, points, home_away, match_type, goals_for, goals_against, match_report, match_tag, match_date, match_id) 
VALUES ('$hometeam', '$oppteam', '$homewin', '$homepoints', '$home_away', '$match_type', '$home_score', '$away_score', '$match_report', 'yes', '$date', '$fileId') ";
mysql_query($query); 

$query="INSERT INTO league_table (team_name, opp_team, w_d_l, points, home_away, match_type, goals_for, goals_against, match_date, match_id) 
VALUES ('$oppteam', '$hometeam', '$awaywin', '$awaypoints', '$opp_home_away', '$match_type', '$away_score', '$home_score', '$date', '$fileId') ";
mysql_query($query); 

$query="UPDATE fixtures SET closed = 'yes' WHERE fix_id='$fileId'"; 
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 



?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=updateresults.php"> <?php

}
?>

<br>

<html>

<head>

<title>Update Result</title>
</head>

<body>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];echo "?fileId="; echo "$fileId";?>">



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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Enter Match Results For <br>
 <?php echo "$ownteam"; ?> VS <?php echo "$opp_team"; ?></b><br><br>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>
<div align="center">
  <center>
<br><br>

Select players that played in this match.<BR>
(This is not required, but this info is used to track results. Without it, only the league table will be updated)
<BR><BR>




<?php
$dbQuery = "SELECT  displayname "; 

$dbQuery .= "FROM users WHERE player = 'yes' AND selected = 'no'  "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
$num=mysql_numrows($result);

if ($num > '0') {


echo '<select name="position">'; 
echo '<option value="1GoalKeeper">Goalkeeper</option>'; 
echo '<option value="2Defender">Defender</option>';  
echo '<option value="3Midfield">Midfield</option>';  
echo '<option value="4Forward">Forward</option>';  
echo '<option value="5Substitute">Substitute</option>';  
echo '<option value="6Not Specified">Not Specified</option>';  
?>
</select>

<SELECT NAME="played">

<?php
while($row = mysql_fetch_array($result))



print "<OPTION VALUE=\"$row[0]\">$row[0]</OPTION>\n";
?>
</select>

<br><br>
Enter number of goals player scored <BR>
<input id="goals" size="2" name="goals"><br>


<BR><BR>
<input type="submit" name="player" value="Add Player">
<br><br>
<input type="submit" name="reset" value="Reset List">
<BR><BR>




<?php }

ELSE {

 ?>

You have allocated all your players

<?php } ?>

<table border="1" cellspacing="1" bgcolor="#<?php echo "$col_table_header" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="55%" id="AutoNumber1">
    <tr>
      
<tr>
      <td width="5%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">No</td>
      <td width="25%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Player Name</td>
      <td width="15%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Position</td>
      <td width="5%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Goals</td>
      <td width="17%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Remove</td>

    </tr>

    

<?php

$query="SELECT * FROM results WHERE match_id = '$fileId' ORDER BY position ASC";
$result=mysql_query($query);
$name = $row["playername"];

$number ='1';


while($row = mysql_fetch_array($result))
{

if ($bgcolor === "$col_table_row")
{
   $bgcolor = "$col_table_row2";
} else {
   $bgcolor = "$col_table_row";
} 


$position = $row["position"];
$goals = $row["goals"];
$shortpos = substr("$position", 1);

?>
    <tr>
</td>
      <td width="5%"  bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$number"; ?></td>
      <td width="25%"  bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["playername"]; ?></td>
      <td width="15%"  bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$shortpos"; ?></td>
      <td width="5%"  bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$goals"; ?></td>
	<td width="19%" bgcolor="#<?php echo "$bgcolor" ?>"><a href="delfromplayed.php?results_id=<?php echo $row["results_id"]; ?>"><font color="#<?php echo "$col_link" ?>"><center>Remove</td>



    </tr>
  
<?php
++$number;
}

echo "</table>";
?>



<BR><BR>
Enter Final Score:
<br><br>

  <center>
<table border="0" cellspacing="1" width="30%" id="AutoNumber1">
  <tr>
    <td width="20%"><?php  echo "<center>"; echo "<font color='#$col_text'>"; ?><?php echo "$ownteam"; ?></td>
    <td width="8%"><input id="home_score" size="2" name="home_score"></td>
  </tr>
  <tr>
    <td width="20%"><?php  echo "<center>"; echo "<font color='#$col_text'>"; ?><?php echo "$opp_team"; ?></td>
    <td width="8%"><input id="away_score" size="2" name="away_score"><br></td>
  </tr>
</table>
<input type="hidden" name="hometeam" value="<? echo "$ownteam"; ?>">
<input type="hidden" name="oppteam" value="<? echo "$opp_team"; ?>">
<input type="hidden" name="home_away" value="<? echo "$home_away"; ?>">
<input type="hidden" name="match_type" value="<? echo "$match_type"; ?>">

<BR>
Enter match report<BR>
(Complete player list first)<br>
<textarea rows=8 cols=60 name="match_report"></textarea><br>




<BR>
Email team to inform them match update has been submitted ?
<BR>
<?php
echo '<select name="email">'; 
echo '<option value="no">No</option>'; 
echo '<option value="yes">Yes</option>';  
?>
</select>
<BR>

<center><br><input type="Submit" name="submit" value="Submit Final Result">

</form>
  </center>



</body>

</html>