<?
include("header.inc.php");

$ask= "UPDATE `demo_a_logo` SET `logoa` = '$logoneu'";
$result = mysql_query($ask) or die(mysql_error());

?>
<?
include("../templates/admin-header.txt");
?>
<center>Logo added</center>
<?
include("../templates/admin-footer.txt");
?>