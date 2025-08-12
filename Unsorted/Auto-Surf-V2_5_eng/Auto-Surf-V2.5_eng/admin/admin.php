<?
include("header.inc.php");

$ask= "UPDATE `demo_a_admin` SET `url` = '$urlneu'";
$result = mysql_query($ask) or die(mysql_error());

$ask1= "UPDATE `demo_a_admin` SET `seitenname` = '$seitennameneu'";
$result1 = mysql_query($ask1) or die(mysql_error());

$ask2= "UPDATE `demo_a_admin` SET `email` = '$emailneu'";
$result2 = mysql_query($ask2) or die(mysql_error());

$ask3= "UPDATE `demo_a_zahl` SET `za` = '$logina'";
$result3 = mysql_query($ask3) or die(mysql_error());

$ask30= "UPDATE `demo_a_zahl` SET `zb` = '$loginb'";
$result30 = mysql_query($ask30) or die(mysql_error());

$ask4= "UPDATE `demo_a_admin` SET `reportpoints` = '$reportpointsneu'";
$result4 = mysql_query($ask4) or die(mysql_error());

$ask5= "UPDATE `demo_a_zahl` SET `zc` = '$klicka'";
$result5 = mysql_query($ask5) or die(mysql_error());

$ask50= "UPDATE `demo_a_zahl` SET `zd` = '$klickb'";
$result50 = mysql_query($ask50) or die(mysql_error());

$ask6= "UPDATE `demo_a_admin` SET `startcredits` = '$startcreditsneu'";
$result6 = mysql_query($ask6) or die(mysql_error());

$ask7= "UPDATE `demo_a_admin` SET `jackport` = '$jackportneu'";
$result7 = mysql_query($ask7) or die(mysql_error());

$ask8= "UPDATE `demo_a_admin` SET `refjackport` = '$refjackportneu'";
$result8 = mysql_query($ask8) or die(mysql_error());

$ask9= "UPDATE `demo_a_admin` SET `ratio` = '$rationeu'";
$result9 = mysql_query($ask9) or die(mysql_error());

$ask10= "UPDATE `demo_a_admin` SET `time` = '$timeneu'";
$result10 = mysql_query($ask10) or die(mysql_error());

$ask11= "UPDATE `demo_a_admin` SET `defaultbanner` = '$defaultbannerneu'";
$result11 = mysql_query($ask11) or die(mysql_error());

$ask12= "UPDATE `demo_a_admin` SET `defaultbannerurl` = '$defaultbannerurlneu'";
$result12 = mysql_query($ask12) or die(mysql_error());

$ask13= "UPDATE `demo_a_admin` SET `emailmodi` = '$emailmodineu'";
$result13 = mysql_query($ask13) or die(mysql_error());

$ask14= "UPDATE `demo_a_admin` SET `logeout` = '$logoutneu'";
$result14 = mysql_query($ask14) or die(mysql_error());

$ask15= "UPDATE `demo_a_admin` SET `registriert` = '$registriertneu'";
$result15 = mysql_query($ask15) or die(mysql_error());

$ask16= "UPDATE `demo_a_admin` SET `referview` = '$referviewneu'";
$result16 = mysql_query($ask16) or die(mysql_error());

$ask17= "UPDATE `demo_a_admin` SET `frequency` = '$frequencyneu'";
$result17 = mysql_query($ask17) or die(mysql_error());

$ask18= "UPDATE `demo_a_admin` SET `starten` = '$startenneu'";
$result18 = mysql_query($ask18) or die(mysql_error());

$ask19= "UPDATE `demo_a_admin` SET `defaulturl` = '$defaulturlneu'";
$result19 = mysql_query($ask19) or die(mysql_error());

$ask20= "UPDATE `demo_a_admin` SET `tausch` = '$tauschen'";
$result20 = mysql_query($ask20) or die(mysql_error());

?>
<?
include("../templates/admin-header.txt");
?>
<center><b>Data changed</b></center>
<?
include("../templates/admin-footer.txt");
?>