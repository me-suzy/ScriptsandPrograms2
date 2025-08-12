<?
include("header.inc.php");

$askk= "UPDATE `demo_a_werbpreis` SET `besuchera` = '$besaneu'";
$resultsk = mysql_query($askk) or die(mysql_error());

$askk1= "UPDATE `demo_a_werbpreis` SET `besucherb` = '$besbneu'";
$resultsk1 = mysql_query($askk1) or die(mysql_error());

$askk2= "UPDATE `demo_a_werbpreis` SET `besucherc` = '$bescneu'";
$resultsk2 = mysql_query($askk2) or die(mysql_error());

$askk3= "UPDATE `demo_a_werbpreis` SET `besucherd` = '$besdneu'";
$resultsk3 = mysql_query($askk3) or die(mysql_error());

$askk4= "UPDATE `demo_a_werbpreis` SET `besuchere` = '$beseneu'";
$resultsk4 = mysql_query($askk4) or die(mysql_error());

$askk5= "UPDATE `demo_a_werbpreis` SET `besucherf` = '$besfneu'";
$resultsk5 = mysql_query($askk5) or die(mysql_error());

$askk6= "UPDATE `demo_a_werbpreis` SET `bannera` = '$baaneu'";
$resultsk6 = mysql_query($askk6) or die(mysql_error());

$askk7= "UPDATE `demo_a_werbpreis` SET `bannerb` = '$babneu'";
$resultsk7 = mysql_query($askk7) or die(mysql_error());

$askk8= "UPDATE `demo_a_werbpreis` SET `bannerc` = '$bacneu'";
$resultsk8 = mysql_query($askk8) or die(mysql_error());

?>
<?
include("../templates/admin-header.txt");
?>
<center><b>Updated!</b></center>
<?
include("../templates/admin-footer.txt");
?>