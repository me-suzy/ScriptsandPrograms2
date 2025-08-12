<?php
include("header.inc.php");
require('prepend.inc.php');

$result = mysql_query("SELECT name, email, source, views, clicks, target, anzahl FROM `demo_a_banners` WHERE `name` = '$username'");
$row = mysql_fetch_row($result);
$username = $row[0];
$email = $row[1];
$bannerquelle = $row[2];
$views = $row[3];
$cklick = $row[4];
$bannerziel = $row[5];
$zahl = $row[6];

$noch = $zahl - $views;
$bannerklick = $cklick - $cklick - $cklick;
?>

<?
include("./templates/main-header.txt");
?>


<br><font size="3"><TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="0" align="center">
<TR>
  <TD width="35%"><b>Your name:</b></TD>
  <TD align="right"><b><?php echo $username; ?></b></TD>
</TR>
<TR>
  <TD width="35%"><b>Your e-mail:</b></TD>
  <TD align="right"><b><?php echo $email; ?></TD>
</TR>
<TR>
  <TD width="35%"><br></TD>
  <TD align="right"><br></TD>
</TR>
<TR>
  <TD width="35%"><b>Banner-URL:</b></TD>
  <TD align="right"><b><?php echo $bannerquelle; ?></TD>
</TR>
<TR>
  <TD width="35%"><b>Target URL:</b></TD>
  <TD align="right"><b><?php echo $bannerziel; ?></TD>
</TR>
<TR>
  <TD width="35%"><br></TD>
  <TD align="right"><br></TD>
</TR>
<TR>
  <TD width="35%"><b>Bannerviews purchased:</b></TD>
  <TD align="right"><b><?php echo $zahl; ?></TD>
</TR>
<TR>
  <TD width="35%"><b>Bannerviews generated:</b></TD>
  <TD align="right"><b><?php echo $noch; ?></TD>
</TR>
<TR>
  <TD width="35%"><b>Bannerclicks generated:</b></TD>
  <TD align="right"><b><?php echo $bannerklick; ?></TD>
</TR>
<TR>
  <TD width="35%"><b>Bannerviews remaining:</b></TD>
  <TD align="right"><b><?php echo $views; ?></TD>
</TR>
</TABLE>

<?
include("./templates/main-footer.txt");
?>