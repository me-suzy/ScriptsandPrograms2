<?php
include "header.php";
session_start();
if (($_SESSION['perm'] < "5"))  {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}
$fileId = $_GET['fileId'];
?><font color="#<?php echo $col_text ?>"> <?php

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
	die ('You currently have members selected under this role. Change these to a new role before deleting role');
}





mysql_query("DELETE FROM roles WHERE role_id='$fileId'")
or die(mysql_error());


?> 

<meta HTTP-EQUIV="Refresh" CONTENT="0; URL=memberrole.php"> 


