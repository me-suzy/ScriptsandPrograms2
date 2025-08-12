<?php
require('../prepend.inc.php');

if($validatemail)
$userid=validatemail();
$userid=s_verify();
$stats=getstats();

$newsid=mt_srand((double)microtime()*1000000);
$newsid=md5(str_replace('.', '', getenv('REMOTE_ADDR') + mt_rand(100000, 999999)));

$direkt = mysql_num_rows(mysql_query("SELECT id FROM `demo_a_accounts` WHERE `refererid` = '$userid'"));

$result = mysql_query("SELECT name, password, url, email, refpoints FROM `demo_a_accounts` WHERE `id` = '$userid'");
$row = mysql_fetch_row($result);
$num = $row[0];
$num1 = $row[1];
$num2 = $row[2];
$num3 = $row[3];
$num4 = $row[4];

?>
<?
include("../templates/member-header.txt");
?>
<br><font size="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td align="center" width="20%"><a href="./?sid=<?php echo $sid; ?>">Stats</a></td>
<td align="center" width="20%"> <a href="../frame.php?userid=<?php echo $userid; ?>" "target=_TOP">Surfbar</a></td>
<td align="center" width="20%"> <a href="./banner.php<? echo "?userid=".$userid."&sid=".$sid; ?>">Bannerviews</a></td>
<td align="center" width="20%"> <a href="./config.php<? echo "?userid=".$userid."&sid=".$sid; ?>">Edit your account</a></td>
</tr>
</table><br><br><br>
<TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="0" width="95%" align="center">
<TR>
  <TD width="50%" bgcolor="#E6E6E6"><b>Your URL:</b></TD>
  <TD bgcolor="#E6E6E6"><b><?php echo $num2; ?></b></TD>
</TR>
<TR>
  <TD width="35%"><br></TD>
  <TD align="right"><br></TD>
</TR>
<TR>
  <TD width="50%" bgcolor="#E6E6E6"><b>Name:</b></TD>
  <TD bgcolor="#E6E6E6"><b><?php echo $num; ?></b></TD>
</TR>
<TR>
  <TD width="35%"><br></TD>
  <TD align="right"><br></TD>
</TR>
<TR>
  <TD width="50%" bgcolor="#E6E6E6"><b>Your e-mail:</b></TD>
  <TD bgcolor="#E6E6E6"><b><?php echo $num3; ?></b></TD>
</TR>
<TR>
  <TD width="35%"><br></TD>
  <TD align="right"><br></TD>
</TR>
<TR>
  <TD width="50%" bgcolor="#E6E6E6"><b>Your password:</b></TD>
  <TD bgcolor="#E6E6E6"><b><?php echo $num1; ?></b></TD>
</TR>
<TR>
  <TD width="35%"><br></TD>
  <TD align="right"><br></TD>
</TR>
<TR>
  <TD width="50%" bgcolor="#E6E6E6"><b>Your referrals:</b></TD>
  <TD bgcolor="#E6E6E6"><b><?php echo $direkt; ?></b></TD>
</TR>
<TR>
  <TD width="35%"><br></TD>
  <TD align="right"><br></TD>
</TR>
<TR>
  <TD width="50%" bgcolor="#E6E6E6"><b>Your referral points:</b></TD>
  <TD bgcolor="#E6E6E6"><b><?php echo $num4; ?></b></TD>
</TR>
<TR>
  <TD width="35%"><br></TD>
  <TD align="right"><br></TD>
</TR>
<TR>
  <TD bgcolor="#E6E6E6"><b>Websites visited:</b></TD>
  <TD bgcolor="#E6E6E6"><b><?php echo $stats[views]; ?></b></TD>
</TR>
<TR>
  <TD width="35%"><br></TD>
  <TD align="right"><br></TD>
</TR>
<TR>
  <TD bgcolor="#E6E6E6"><b>Visits on your website:</b></TD>
  <TD bgcolor="#E6E6E6"><b><?php echo $stats[hits]; ?></b></TD>
</TR>
<TR>
  <TD width="35%"><br></TD>
  <TD align="right"><br></TD>
</TR>
<TR>
  <TD bgcolor="#E6E6E6"><b>Points:</b></TD>
  <TD bgcolor="#E6E6E6"><b><?php echo $stats[points]; ?></b></TD>
</TR>
<TR>
  <TD width="35%"><br></TD>
  <TD align="right"><br></TD>
</TR>
<TR>
  <TD width="50%" bgcolor="#E6E6E6"><b>Referral-Link:</b></TD>
  <TD bgcolor="#E6E6E6"><b><?php echo $url_index; ?>/register.php?referer=<?php echo $userid; ?></b></TD>
</TR>
</TABLE>
<?
include("../templates/member-footer.txt");
?>