<?php
include "header.php";
// require('db_connect.php');	// database connect script.
@mysql_select_db($db_name) or die( "Unable to select database");
if ($logged_in == 0) {
	die('You are not logged in so you can not view giftlists');
}


$id = $_SESSION['username'];


$query="SELECT * FROM users WHERE username = '$id'";
$result=mysql_query($query);
$num=mysql_numrows($result); 
mysql_close();

$i=0;
while ($i < $num) {


$password=mysql_result($result,$i,"password");




?>

<form action="updatepass.php" method="post">
<input type="hidden" name="ud_id" value="<? echo $id; ?>">



Profile for: <? echo "$username"; ?>
<br><br>
<tr>



New Password</td><br>
<input type="password" name="ud_password" maxlength="50">
<br><br>
Confirm New Password<br>
<input type="password" name="passwd_again" maxlength="50">



<br><br>
<input type="Submit" value="Update">
</form>



<?
++$i;
} 
?>