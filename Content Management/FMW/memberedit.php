<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}
?>

<br>

<html>

<head>

<title>Member Role</title>
</head>

<body>


<center>
<table border="0" width="70%" height="30" cellpadding="0" cellspacing="0">
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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Member Role Management</b>
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
$dbQuery = "SELECT *"; 

$dbQuery .= "FROM users "; 
$dbQuery .= "ORDER BY username ASC";
$result = mysql_query($dbQuery) or die("Couldn't get file list");


?>


  <table border="1" cellspacing="1" bgcolor="#<?php echo "$col_table_header" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="70%" id="AutoNumber1">
    <tr>
      

    <tr>
      <td width="20%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Username</td>
      <td width="25%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Display Name</td>
<td width="15%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Role</td>
<td width="7%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Player ?</td>
<td width="10%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Rights</td>
      <td width="10%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Edit</td>
      <td width="12%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Delete</td>
	</tr>

<?php



while($row = mysql_fetch_array($result)) {
if ($bgcolor === "$col_table_row")
{
   $bgcolor = "$col_table_row2";
} else {
   $bgcolor = "$col_table_row";
} 
?>
    <tr>
      <td width="20%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["username"]; ?></td>
      <td width="25%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["displayname"]; ?></td>
     <td width="15%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["role"]; ?></td>
     <td width="7%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["player"]; ?></td>
     <td width="10%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["rights"]; ?></td>
      <td width="10%" bgcolor="#<?php echo "$bgcolor" ?>"><a href="edituser.php?fileId=<?php echo $row["id"]; ?>"><font color="#<?php echo $col_link ?>">Edit</a></td>

	<td width="12%" bgcolor="#<?php echo "$bgcolor" ?>"><a href="delmember.php?fileId=<?php echo $row["id"]; ?>"><font color="#<?php echo "$col_link" ?>">Delete</td>






    </tr>
  

<?php
}

echo "</table>";
?>
<br>
<font color="#<?php echo $col_text ?>">
Member Rights<br>
<br>
*(0) Member - Access general pages, but can not post news<br>
*(3) Super Member - Post and edit all news<br>
*(5) Admin - Full access<br>

  </center>

</body>

</html>