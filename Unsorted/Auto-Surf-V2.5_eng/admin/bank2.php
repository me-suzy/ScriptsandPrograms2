<?
include("header.inc.php");

$ask= "UPDATE `demo_a_bank` SET `name` = '$inhaber'";
$result = mysql_query($ask) or die(mysql_error());

$ask1= "UPDATE `demo_a_bank` SET `konummer` = '$knummer'";
$result1 = mysql_query($ask1) or die(mysql_error());

$ask2= "UPDATE `demo_a_bank` SET `banklz` = '$blzahl'";
$result2 = mysql_query($ask2) or die(mysql_error());

$ask3= "UPDATE `demo_a_bank` SET `bname` = '$neu'";
$result3 = mysql_query($ask3) or die(mysql_error());

?>
<?
include("../templates/admin-header.txt");
?>
<center><b>Updated!</b></center>
<?
include("../templates/admin-footer.txt");
?>