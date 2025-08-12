<?php
require('../prepend.inc.php');
include("header.inc.php");

$result3 = mysql_query("SELECT tausch FROM `demo_a_admin`");
$row3 = mysql_fetch_row($result3);
$tau = $row3[0];

global $userid, $views, $sid;

?>
<?
include("../templates/member-header.txt");
?>
<br><font size="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center" width="20%"><a href="./?sid=<?php echo $sid; ?>">Stats</a></td>
<td align="center" width="20%"> <a href="../frame.php?userid=<?php echo $userid; ?>" "target=_TOP">Surfbar</a></td>
<td align="center" width="20%"> <a href="./banner.php<? echo "?userid=".$userid."&sid=".$sid; ?>">Bannerviews</a></td>
 <td align="center" width="20%"><a href="./config.php<? echo "?userid=".$userid."&sid=".$sid; ?>">Edit your account</a></td>
</tr>
</table><br><br><br><center><b><?

        $resulta = mysql_query("SELECT name, email, points FROM `demo_a_accounts` WHERE id='$userid'");
        $myrowa = mysql_fetch_row($resulta);
        $name = $myrowa[0];
        $email = $myrowa[1];
        $punkt = $myrowa[2];
        $punkteb = bcmul($punkt,$tau,0);
        if ($views > $punkteb){

echo"<center><font size=3 color=#000000> Hallo $name <br>You do not have enough points!<br> You only have $punkt points, so you can get $punkteb bannerviews ";
} else {
        if ($views < 0){
echo"<center><font size=3 color=#000000> Hallo $name <br>You have to input a number higher than 0";
} else {

        $resultb = mysql_query("SELECT name, email, points FROM `demo_a_accounts` WHERE id='$userid'");
        $myrowb = mysql_fetch_row($resultb);
        $name = $myrowb[0];
        $email = $myrowb[1];
        $punkt = $myrowb[2];
        $viewsc = bcdiv($views,$tau,0);
        $guthaben = $punkt - $viewsc;

        global $source, $target, $views ,$anzahl, $email;
        $query="INSERT INTO demo_a_banners (name, email, source, target, views, anzahl) VALUES ('$name', '$email', '$source', '$target', '$views', '$views');";
        mysql_query($query);
        $viewsb = bcdiv($views,$tau,0);

$ask= "UPDATE `demo_a_accounts` SET `points` = '$guthaben' WHERE id='$userid'";
$result = mysql_query($ask) or die(mysql_error());
echo"<center><font size=3 color=#000000>Hallo $name<br>You have successfully changed $viewsb points into $views bannerviews";
}
}
 ?></b>
</center>
<?
include("../templates/member-footer.txt");
?>