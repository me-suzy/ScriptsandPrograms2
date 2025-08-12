<?php
include "header.php";

?><font color="#<?php echo $col_text ?>"><?php
$todaysdate = date('Y-m-d');


$fileId = $_GET['fileId'];

$dbQuery = "SELECT *"; 
$dbQuery .= "FROM admin "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))  {
$pom_vote = $row["pom_vote"];
							}


$dbQuery = "SELECT *"; 
$dbQuery .= "FROM fixtures WHERE fix_id = '$fileId' "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))
							{
$ground = $row["ground"];
							}


$dbQuery = "SELECT *"; 
$dbQuery .= "FROM league_table WHERE match_id = '$fileId' AND match_tag = 'yes' "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))
							{


$score = $row["goals_for"]."-".$row["goals_against"];
$date = $row["match_date"];
$team_name = $row["team_name"];
$opp_team = $row["opp_team"];
$home_away = $row["home_away"];
$match_type = $row["match_type"];
$match_report = $row["match_report"];

					}

$year = substr("$date", 0, 4);
$month = substr("$date", 5,-3);
$day = substr("$date", 8);

$todaysyear = substr("$todaysdate", 0, 4);
$todaysmonth = substr("$todaysdate", 5,-3);
$todaysday = substr("$todaysdate", 8);




if (isset($_POST['submit'])) { 
$playername = $_POST['playername'];


$dbQuery = "SELECT  * "; 
$dbQuery .= "FROM player_of_match WHERE match_id = '$fileId' AND username = '$_SESSION[username]'  "; 
$result2 = mysql_query($dbQuery) or die("Couldn't get file list");
$num2=mysql_numrows($result2);
while($row2 = mysql_fetch_array($result2))	
$playername = $row2["playername"];
							
	
	if ($num2 > '0') {   ?> <BR> <?php
	die ('You have already voted for Player Of The Match');							}

ELSE {

$query="SELECT pom FROM results WHERE match_id= '$fileId' AND playername = '$playername' ";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$pom = $row["pom"];
}

$pom = $pom +1;

$query="UPDATE results SET pom = '$pom' WHERE match_id='$fileId' AND playername = '$playername' "; 
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 

$query="INSERT INTO player_of_match (username, playername, match_id) 
VALUES ('$_SESSION[username]', '$playername', '$fileId') ";
mysql_query($query); 

		}
		}
?>

<br>

<html>

<head>

<title>Result</title>
</head>
<BODY>
<font color="#<?php echo $col_text ?>">


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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Match Result<br>
<?php echo "$score"; ?><br></b>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>


<div align="center">
  <center>
  <table border="1" bgcolor="#<?php echo "$col_back" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>"< width="60%" >
    <tr>

 <td width="39%"  bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >Opposition Team </td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;

<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$opp_team"; ?></font>
    </tr>
</td>    
</tr>

<td width="39%"  bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >Ground Played At</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($ground == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$ground"; ?></font>
    </tr>
<?php
} ?>
</td>    
</tr>


<td width="39%"  bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >Match Type</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row2" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;

<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$match_type"; ?></font>
    </tr>
</td>    
</tr>


<td width="39%"  bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >Date</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$day"."-"."$month"."-"."$year"; ?></font>
    </tr>

</td>    
</tr>


<td width="39%"  bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>" size="2" >Home or Away</td>      
      <td width="61%" bgcolor="#<?php echo "$col_table_row" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;
<?php
	if ($home_away == '') {
?><font color="#<?php echo "$col_table_row_text" ?>"  size="2" >Not Available</font>
</tr>
<?php
}
else{
?>
<font color="#<?php echo "$col_table_row_text" ?>"  size="2" ><?php echo "$home_away"; ?></font>
    </tr>
<?php
} ?>
</td>    
</tr>
     

<div align="center">
  <center>
  <table border="1" bgcolor="#<?php echo "$col_back" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="60%" height="111">
    <tr>

      <td width="60%" height="5"  align="center" bgcolor="#<?php echo "$col_table_header" ?>">
<center><font color="#<?php echo "$col_table_header_text" ?>">Match Report<br><textarea rows=6 cols=67 name="notes"><? echo  "$match_report" ?></textarea>
<a href="matchcomment.php<?php echo "?fileId="."$fileId"; ?>"><font color="#<?php echo $col_link ?>">'Click' To Add / View Comments<font></a>
</td>
   </tr>

  </table>
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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Match Players<br><br></b>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>

<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>

<table border="1" cellspacing="1" bgcolor="#<?php echo "$col_table_header" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="60%" id="AutoNumber1">
    <tr>
      
<tr>
      <td width="5%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">No</td>
      <td width="20%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Player Name</td>
 <td width="20%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Position</td>
      <td width="4%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Goals</td>


    </tr>

<?php
$number ='1';

$query="SELECT * FROM results WHERE match_id = '$fileId' ORDER BY position ASC";
$result=mysql_query($query);
$name = $row["playername"];

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
      <td width="20%"  bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["playername"]; ?></td>
      <td width="20%"  bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$shortpos"; ?></td>
      <td width="4%"  bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$goals"; ?></td>

    </tr>

<?php
++$number;
}
?>
</table>




<?php

//-------------------------------
// select total vote for players
//-------------------------------

$pomtodaydate = "$todaysyear"."$todaysmonth"."$todaysday";
$pommatchdate = "$year"."$month"."$day";

$checkdate = ($pomtodaydate - $pommatchdate);



	if ($checkdate > "$pom_vote") {


$dbQuery = "SELECT  playername, pom "; 
$dbQuery .= "FROM results WHERE match_id = '$fileId' ORDER BY pom ASC "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
$num=mysql_numrows($result);

			while($row = mysql_fetch_array($result)) {
$playername = $row["playername"];
$pom = $row["pom"];

								
}

	if ($num > '0' AND $pom > '0') {

?>

</table>
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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Player voted 'Player Of The Match'<br>
<br><?php echo "$playername"; ?><br><BR></b>
<td width="5"><img src="images/theme/<?php echo "$theme_col" ?>/boxright.png" width="5" height="100%" alt=""></td>

</tr>




<tr bgcolor="#<?php echo "$col_back"; ?>">
<td width="5" align="left" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomleft.png" width="5" height="5" alt=""></td>
<td width="100%" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxbackbottom.png" width="100%" height="5" alt=""></td>
<td width="5" align="right" valign="top"><img src="images/theme/<?php echo "$theme_col" ?>/boxcornerbottomright.png" width="5" height="5" alt=""></td>
</table>

<?php }
	}
		ELSE {
?>

<table border="1" cellspacing="1" bgcolor="#<?php echo "$col_table_header" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="60%" id="AutoNumber1">
    <tr>
      
<tr>
      <td width="5%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">No</td>
      <td width="20%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Player Name</td>
 <td width="20%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Position</td>
      <td width="4%"  bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Goals</td>


    </tr>

</table>




<center>
<?php
if ($logged_in == 0 OR $_SESSION[username] == '') {
?> 
Remember you can vote for player of the match if you are a member of the club ! Login to Vote.
<?php }
ELSE {
?>
<p>Choose 'Player Of The Match' from the list below</p>
(You have 2 days from match date to vote)

<form method="post" action="<?php echo $_SERVER['PHP_SELF'];echo "?fileId="; echo "$fileId";?>">
<?php
$dbQuery = "SELECT  playername "; 
$dbQuery .= "FROM results WHERE match_id = '$fileId'  "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
$num=mysql_numrows($result);


		if ($num > '0') {

			?>

			<SELECT NAME="playername">

			<?php
			while($row = mysql_fetch_array($result))
			print "<OPTION VALUE=\"$row[0]\">$row[0]</OPTION>\n";
			?>

</select>
<BR><BR>
<input type="submit" name="submit" value="Vote !">


<?php }

ELSE {

 ?>
(Sorry, No players have been entered into the squad)
<?php }
	}
	}

 ?>
  </center>
</div>
</body>

</html>