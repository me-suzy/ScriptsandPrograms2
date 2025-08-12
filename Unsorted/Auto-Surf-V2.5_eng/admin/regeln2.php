<?
include("header.inc.php");

$asl = "UPDATE `demo_a_regeln` SET `regeln` = '$reaneu'";
$results = mysql_query($asl) or die(mysql_error());

?>
<?
include("../templates/admin-header.txt");
?>
<center><br><br><br><b>Updated</b></center>
<?
include("../templates/admin-footer.txt");
?>