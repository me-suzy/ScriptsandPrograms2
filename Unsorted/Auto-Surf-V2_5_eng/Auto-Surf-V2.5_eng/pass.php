<?php
require('./prepend.inc.php');

include("./header.inc.php");

$result = mysql_query("SELECT password FROM `demo_a_accounts` WHERE `email` = '$email'");
$row = mysql_fetch_row($result);
$num = $row[0];

mail("$email", "Your password at $seitenname", "Dear user\n \nHere's the data you have asked for\n \nyour password = $num\n \nYours $seitenname","From: $seitenname <$emailadresse>");
?>

<?
include("./templates/main-header.txt");
?>


<br><font size="3"><br><br><br><center><b>Your password has been sent to your e-mail.<br><br>Thank you for using our service at<br><? echo "$seitenname"; ?></center></font>

<?
include("./templates/main-footer.txt");
?>