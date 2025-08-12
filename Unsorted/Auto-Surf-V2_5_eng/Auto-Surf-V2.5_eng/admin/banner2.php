<?
include("header.inc.php");

$asks= "UPDATE `demo_a_werbebanner` SET `meinebannera` = '$bannaneu'";
$results = mysql_query($asks) or die(mysql_error());
$asks1= "UPDATE `demo_a_werbebanner` SET `meinebannerurla` = '$bannaurlneu'";
$results1 = mysql_query($asks1) or die(mysql_error());

$asks2= "UPDATE `demo_a_werbebanner` SET `meinebannerb` = '$bannbneu'";
$results2 = mysql_query($asks2) or die(mysql_error());
$asks3= "UPDATE `demo_a_werbebanner` SET `meinebannerurlb` = '$bannburlneu'";
$results3 = mysql_query($asks3) or die(mysql_error());

$asks4= "UPDATE `demo_a_werbebanner` SET `meinebannerc` = '$banncneu'";
$results4 = mysql_query($asks4) or die(mysql_error());
$asks5= "UPDATE `demo_a_werbebanner` SET `meinebannerurlc` = '$banncurlneu'";
$results5 = mysql_query($asks5) or die(mysql_error());

$asks6= "UPDATE `demo_a_werbebanner` SET `meinebannerd` = '$banndneu'";
$results6 = mysql_query($asks6) or die(mysql_error());
$asks7= "UPDATE `demo_a_werbebanner` SET `meinebannerurld` = '$banndurlneu'";
$results7 = mysql_query($asks7) or die(mysql_error());

$asks8= "UPDATE `demo_a_grosse` SET `aa` = '$einsaneu'";
$results6 = mysql_query($asks8) or die(mysql_error());
$asks9= "UPDATE `demo_a_grosse` SET `ab` = '$einsbneu'";
$results9 = mysql_query($asks9) or die(mysql_error());

$asks10= "UPDATE `demo_a_grosse` SET `ba` = '$zweianeu'";
$results10 = mysql_query($asks10) or die(mysql_error());
$asks11= "UPDATE `demo_a_grosse` SET `bb` = '$zweibneu'";
$results11 = mysql_query($asks11) or die(mysql_error());

$asks12= "UPDATE `demo_a_grosse` SET `ca` = '$dreianeu'";
$results12 = mysql_query($asks12) or die(mysql_error());
$asks13= "UPDATE `demo_a_grosse` SET `cb` = '$dreibneu'";
$results13 = mysql_query($asks13) or die(mysql_error());

$asks14= "UPDATE `demo_a_grosse` SET `da` = '$vieraneu'";
$results14 = mysql_query($asks14) or die(mysql_error());
$asks15= "UPDATE `demo_a_grosse` SET `db` = '$vierbneu'";
$results15 = mysql_query($asks15) or die(mysql_error());


?>
<?
include("../templates/admin-header.txt");
?>
<center><b>Updated!</b></center>
<?
include("../templates/admin-footer.txt");
?>