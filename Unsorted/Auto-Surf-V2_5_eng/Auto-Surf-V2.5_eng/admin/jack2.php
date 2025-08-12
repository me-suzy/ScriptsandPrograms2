<?
include("header.inc.php");

$ask= "UPDATE `demo_a_gambleadmin` SET `gampoints` = '$punktneu'";
$result = mysql_query($ask) or die(mysql_error());

$ask1= "UPDATE `demo_a_gambleadmin` SET `gamchance` = '$chanceneu'";
$result1 = mysql_query($ask1) or die(mysql_error());

$ask2= "UPDATE `demo_a_gambleadmin` SET `gampointszu` = '$punktzuneu'";
$result2 = mysql_query($ask2) or die(mysql_error());

?>
<?
include("../templates/admin-header.txt");
?>
<center><b>Updated!</b></center>
<?
include("../templates/admin-footer.txt");
?>