<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}
$fileId = $_GET['fileId'];

?><font color="#<?php echo $col_text ?>"><?php

$query="SELECT * FROM roles WHERE role_id= '$fileId'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result))
{
$role_title = $row["role_title"];

}

$dbQuery = "SELECT username "; 
$dbQuery .= "FROM users WHERE role = '$role_title' "; 
$result2 = mysql_query($dbQuery) or die("Couldn't get file list");
$num=mysql_numrows($result2);


if ($num > '0') {
	die ('You currently have members selected under this role. Change these to a new role before editing role');
}



if ($_POST['submit'] == 'submit') {

if(!$_POST['role_title']) {
	?><font color="#<?php echo "$col_text" ?>"><?php die('You must enter a name');
	}


$role_title = $_POST['role_title'];
$query="UPDATE roles SET role_title = '$role_title' WHERE role_id = $fileId ";
@mysql_select_db($db_name) or die( "Unable to select database");
mysql_query($query);
?> <meta HTTP-EQUIV="Refresh" CONTENT="0; URL=memberrole.php"> <?php

}
?>

<br>

<html>

<head>

<title>Member Role</title>
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
 <?php  echo "<center>"; echo "<font color='#$col_table_header_text'>"; ?><b>Edit Role: <?php echo $row["role_title"]; ?></b><br><br>
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

Enter new value for role:
<br><br>

<input id="role_title" size="40" name="role_title" value="<?php echo "$role_title" ?>"><br>


 

<?php


?>
  </center>



<center><br><input type="Submit" name="submit" value="submit">


</body>

</html>