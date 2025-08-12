<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}
?>
<font color="#<?php echo $col_text ?>">
<br>

<html>

<head>

<title>Member Role</title>
</head>

<body>


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

$dbQuery .= "FROM roles "; 
$dbQuery .= "ORDER BY role_order ASC";
$result = mysql_query($dbQuery) or die("Couldn't get file list");
$role_title = $row["role_title"];
$role_order = $row["role_order"];

if ($_POST['submit'] == 'submit') {

if(!$_POST['role_title'] | !$_POST['role_order']) {
	?><font color="#<?php echo "$col_text" ?>"><?php die('You must enter a name and order number');
	}


$role_title = $_POST['role_title'];
$role_order = $_POST['role_order'];
$query="INSERT INTO roles (role_title, role_order) VALUES ('$role_title', '$role_order')";
mysql_query($query); 

?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=memberrole.php"> <?php

}



?>


  <table border="1" cellspacing="1" bgcolor="#<?php echo "$col_table_header" ?>" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="60%" id="AutoNumber1">
    <tr>
      

    <tr>
      <td width="7%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Role Name</td>
      <td width="19%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">View Members</td>
      <td width="23%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Delete</td>
      <td width="15%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Edit</td>
<td width="13%" bgcolor="#<?php echo "$col_table_header" ?>"><font color="#<?php echo "$col_table_header_text" ?>">Display Order</td>


    </tr>

<?php

if ($bgcolor === "$col_table_row")
{
   $bgcolor = "$col_table_row2";
} else {
   $bgcolor = "$col_table_row";
} 

while($row = mysql_fetch_array($result)) {

?>
    <tr>
      <td width="19%" bgcolor="#<?php echo "$bgcolor" ?>"><font color="#<?php echo "$col_table_row_text" ?>"><?php echo $row["role_title"]; ?></td>
      <td width="17%" bgcolor="#<?php echo "$bgcolor" ?>"><a href="viewroles.php?fileId=<?php echo $row["role_id"]; ?>"><font color="#<?php echo $col_link ?>">View</a></td>
      <td width="17%" bgcolor="#<?php echo "$bgcolor" ?>"><a href="delrole.php?fileId=<?php echo $row["role_id"]; ?>"><font color="#<?php echo $col_link ?>">Delete</a></td>
      <td width="17%" bgcolor="#<?php echo "$bgcolor" ?>"><a href="editrole.php?fileId=<?php echo $row["role_id"]; ?>"><font color="#<?php echo $col_link ?>">Edit</a></td>

	<td width="19%" bgcolor="#<?php echo "$bgcolor" ?>"><a href="editorder.php?fileId=<?php echo $row["role_id"]; ?>"><font color="#<?php echo "$col_link" ?>"><center><?php echo $row["role_order"]; ?></td>






    </tr>
  

<?php
}

echo "</table>";
?>
  </center>
<form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">



To add new Role, enter name and click submit<br>
<input id="role_title" size="25" name="role_title" maxlength="20">
<BR>
Enter display order value for 'Team' screen (lower number displays at top)
<br>
<input  id="role_order" size="2" name="role_order" maxlength="3">
<BR>

<center><br><input type="Submit" name="submit" value="submit">

</body>

</html>


