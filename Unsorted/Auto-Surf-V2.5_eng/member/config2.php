<?php
require('../prepend.inc.php');
include("header.inc.php");

global $userid, $sid, $emailneu, $passneu, $vorneu, $nameneu;

$ask= "UPDATE `demo_a_accounts` SET `name` = '$nameneu' WHERE id='$userid'";
$result = mysql_query($ask) or die(mysql_error());

$ask2= "UPDATE `demo_a_accounts` SET `email` = '$emailneu' WHERE id='$userid'";
$result2 = mysql_query($ask2) or die(mysql_error());

$ask3= "UPDATE `demo_a_accounts` SET `password` = '$passneu' WHERE id='$userid'";
$result3 = mysql_query($ask3) or die(mysql_error());

$ask3= "UPDATE `demo_a_accounts` SET `prename` = '$vorneu' WHERE id='$userid'";
$result3 = mysql_query($ask3) or die(mysql_error());
?>
<?
include("../templates/member-header.txt");
?>
<br><font size="3"><table border="0" cellspacing="0" cellpadding="0" width="95%">
  <tr>
    <td align="center" width="20%"><a href="./?sid=<?php echo $sid; ?>">Stats</a></td>
    <td align="center" width="20%"><a href="../frame.php?userid=<?php echo $userid; ?>" target="blank">Surfbar</a></td>
    <td align="center" width="20%"> <a href="./banner.php<? echo "?userid=".$userid."&sid=".$sid; ?>">Bannerviews</a></td>
    <td align="center" width="20%"><a href="./config.php<? echo "?userid=".$userid."&sid=".$sid; ?>">Edit your account</a></td>
  </tr>
</table>
<br><br><br><center><b> Updated!</b></center>
<?
include("../templates/member-footer.txt");
?>