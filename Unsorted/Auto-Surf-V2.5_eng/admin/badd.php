<?
include("header.inc.php");

$ask= "UPDATE `demo_a_seitenbanner` SET `code` = '$bannerneu'";
$result = mysql_query($ask) or die(mysql_error());

?>
<?
include("../templates/admin-header.txt");
?>
<center>Banner added.</center>
<?
include("../templates/admin-footer.txt");
?>