<?php
include "header.php";

?>

<br>

<html>

<head>

<title>Team List</title>
</head>

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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Team</b>
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
$dbQuery = "SELECT * "; 

$dbQuery .= "FROM roles "; 
$dbQuery .= "ORDER BY role_order ASC";
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row2 = mysql_fetch_array($result)) {
$role_title = $row2["role_title"];
$num2=mysql_numrows($result);


$dbQuery = "SELECT * "; 
$dbQuery .= "FROM users WHERE role = '$role_title' "; 
$result2 = mysql_query($dbQuery) or die("Couldn't get file list");
$num=mysql_numrows($result2);


?>


  <table border="1" cellspacing="1" bgcolor="#<?php echo "$col_table_header_2" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="90%" id="AutoNumber1">
    <tr>
      <td width="100%" colspan="5"> <font size ="4" font color="#<?php echo "$col_table_header_text" ?>"><?php echo "$role_title (s)"; ?>
<br>
<font size="2" color="#<?php echo "$col_table_header_text" ?>"><?php echo "You currently have $num $role_title (s) in your team"; ?></td>
    </tr>

    <tr>
      <td width="7%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Profile</td>
      <td width="19%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Name</td>
      <td width="23%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Nick Name</td>
      <td width="15%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Custom Role</td>
      <td width="30%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Email</td>
    </tr>

<?php
while($row = mysql_fetch_array($result2)){

if ($bgcolor === "$col_table_row")
{
   $bgcolor = "$col_table_row2";
} else {
   $bgcolor = "$col_table_row";
} 

?>
    <tr>
      <td width="7%" bgcolor="#<?php echo "$bgcolor" ?>"><a href="profile.php?fileId=<?php echo $row["id"]; ?>"><img border="0" title="View Profile" src="images/theme/<?php echo "$theme_col" ?>/profile.gif"</a></td>
      <td width="19%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;<?php echo $row["displayname"]; ?></td>
      <td width="23%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;<?php echo $row["nickname"]; ?></td>
      <td width="15%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;<?php echo $row["position"]; ?></td>
      <td width="30%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>">&nbsp;<?php echo $row["email"]; ?></td>
    </tr>
  

<?php
}
}

echo "</table>";
?>
  </center>

</body>

</html>


