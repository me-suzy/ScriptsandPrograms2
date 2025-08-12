<?php
require('./prepend.inc.php');

include("header.inc.php");

$result = mysql_query("SELECT name FROM `demo_a_banners` WHERE `email` = '$email'");
$row = mysql_fetch_row($result);
$num = $row[0];

mail("$email", "Your user name at $seitenname", "Dear user\n \nHere is the requested user data.\n \nYour username = $num\n \nYours $seitenname","From: $seitenname <$emailadresse>");
?>

<?
include("./templates/main-header.txt");
?>


<br><font size="3"><br><br><br><center><b>Your username was sent to your e-mail.<br><br>Thank you for using our service at<br><? echo "$seitenname"; ?></center></font></TD>
</TR>
<TR>
  <TD height="100%">&nbsp;</TD>
</TR>
</TABLE>

<?
include("./templates/main-footer.txt");
?>