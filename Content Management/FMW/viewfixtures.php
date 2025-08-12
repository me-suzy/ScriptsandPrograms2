<?php
include "header.php";

$todaysdate = date('Y-m-d');

$query="UPDATE users SET selected='no' "; 
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query); 


?>

<br>

<html>

<head>

<title>Fixture List</title>
</head>

<body>


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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Fixtures</b><br><br>
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
      <td width="7%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">View</td>
<?php
session_start();
if (($_SESSION['perm'] > "3")){ ?>
<td width="11%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Pick Squad</td>
	<?php				} ?>
      <td width="35%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Opposition</td>
<td width="12%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Date Of Match</td>
<td width="12%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Kickoff</td>
      <td width="12%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Match Type</td>
      <td width="12%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Home/Away</td>
	</tr>
<?php

$dbQuery = "SELECT *"; 

$dbQuery .= "FROM fixtures WHERE fix_date >= '$todaysdate' "; 
$dbQuery .= "ORDER BY fix_date ASC";
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))
	{

if ($bgcolor === "$col_table_row")
{
   $bgcolor = "$col_table_row2";
} else {
   $bgcolor = "$col_table_row";
} 

$date = $row["fix_date"];


$year = substr("$date", 0, 4);
$month = substr("$date", 5,-3);
$day = substr("$date", 8);


?>
    <tr>
	<td width="7%" bgcolor="#<?php echo "$bgcolor" ?>"><a href="fixture.php?fileId=<?php echo $row["fix_id"]; ?>"><font color="#<?php echo "$col_link" ?>">View</td>
<?php
if (($_SESSION['perm'] > "3")){ ?>
<td width="11%" bgcolor="#<?php echo "$bgcolor" ?>"><a href="selectsquad.php?fileId=<?php echo $row["fix_id"]; ?>"><font color="#<?php echo "$col_link" ?>">Squad</td>
<?php					} ?>
      <td width="35%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["opp_team"]; ?></td>
      <td width="12%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo "$day-$month-$year"; ?></td>
     <td width="12%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["fix_time"]; ?></td>
     <td width="12%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["match_type"]; ?></td>
     <td width="12%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["home_away"]; ?></td>







    </tr>
  

<?php
}

echo "</table>";
?>
<br>


  </center>

</body>

</html>