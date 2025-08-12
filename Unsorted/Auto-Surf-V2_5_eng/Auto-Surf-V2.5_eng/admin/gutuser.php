<?
include("header.inc.php");
?>
<?
$rs = mysql_query("SELECT points FROM `demo_a_accounts` WHERE `email` = '$email'");
$num = mysql_fetch_row($rs);
$guthaben = $num[0] + $punkt;

$ask= "UPDATE `demo_a_accounts` SET `points` = '$guthaben' WHERE `email` = '$email'";
$result = mysql_query($ask) or die(mysql_error());
$gut = $guthaben;
$punkt2 = $punkt;
?>
<?
include("../templates/admin-header.txt");
?>
<center><font size=3>User with e-mail <? echo "$email"; ?><br> was credited <? echo "$punkt2"; ?> points.<br>He has now got <? echo "$gut"; ?> points.
<?
include("../templates/admin-footer.txt");
?>