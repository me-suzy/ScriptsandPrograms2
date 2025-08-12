<?php
global $userid, $sid;

include("header.inc.php");
require('../prepend.inc.php');

$result3 = mysql_query("SELECT tausch FROM `demo_a_admin`");
$row3 = mysql_fetch_row($result3);
$tau = $row3[0];

$result = mysql_query("SELECT email, points FROM `demo_a_accounts` WHERE `id` = '$userid'");
$row = mysql_fetch_row($result);
$num = $row[0];
$num1 = $row[1];

$num3=bcmul($num1,$tau,0);

include("header.inc.php");

$einblendung = 0;
$click = 0;
$holen = mysql_query("SELECT views, clicks FROM `demo_a_banners` WHERE email='$num'");

require("header.inc.php");

while ($myrow = mysql_fetch_row($holen)) {

$einblendung = $myrow[0] + $einblendung;
$click = $myrow[1] + $click;
$clickss = $click - $click - $click;
};
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
</table><br><br><br><center><b>For 1 point you can get <? echo "$tau"; ?> bannerviews<br>You have <? echo "$num1"; ?> points, so you can get <? echo "$num3"; ?> bannerviews</b></center><br><form method="post" action="./banner2.php?userid=<?php echo $userid; ?> &?sid=<?php echo $sid; ?>">
<input type="hidden" name="sid" value="<?php echo $sid; ?>">
<TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="0" width="95%" align="center">
   <tr>
      <td>Your e-mail</td>
      <td><? echo "$num"; ?></td>
    </tr>
    <tr>
      <td>Banner code</td>
      <td>
        <input type="text" name="source" value="<?php echo stripslashes($source); ?>">
      </td>
    </tr>
    <tr>
      <td>Banner target</td>
      <td>
        <input type="text" name="target" value="<?php echo stripslashes($target); ?>">
      </td>
    </tr>
    <tr>
      <td>Views</td>
      <td>
        <input type="text" name="views" value="<?php echo stripslashes($views); ?>">
      </td>
    </tr>
    <TR><TD>&nbsp;</TD></TR>
    <tr>
      <td colspan="2" align="center">
        <input type="submit" value="Submit">
      </td>
    </tr>
</TABLE></form><br><center><b>Banner stats</b><br>
<TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="0" align="center" width="">
<TR>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Bannerviews remaining</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$einblendung"; ?></TD>
</TR>
<TR>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Bannerclicks</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$clickss"; ?></TD>
</TR>
</TABLE></center>
<?
include("../templates/member-footer.txt");
?>