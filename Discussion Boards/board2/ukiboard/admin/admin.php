<?php
include("../connect.php");
include("one.php");
logincontrol($tblname_admin);
sessioncontrol();
include("../function/selecty.php");
include("../languages/$language");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?php echo $board_title; ?></TITLE>
<META http-equiv=Content-Type content="text/html; charset=<?php echo $lang[Encoding]; ?>">
<LINK HREF="../themes/<?php echo $themes; ?>" REL="stylesheet" TYPE="text/css">
</HEAD>
<?php include("../themes/$style"); ?>
<BODY>
<CENTER><BR>
<TABLE border="0" cellspacing="0" cellpadding="0" width="600">
<TR><TD class="nadpis" align="center" valign="top">
<?php echo $board_title; ?>
<TR><TD class="news" align="center" valign="top">
<?php echo $board_char; ?>
<BR><BR>
<?php include("menu.php"); ?>
<TR><TD align="center" valign="top">
<TABLE border="0" cellspacing="5" cellpadding="0" width="100%">
<TR><TD class="oknox" align="center">
<?php
echo $lang[Admin];
$result6 = MySQL_Query("SELECT COUNT(*) AS pocethead FROM $tblname_head");
  $numrows6 = mysql_result($result6, 0, "pocethead");
$result7 = MySQL_Query("SELECT COUNT(*) AS pocettopic FROM $tblname_topic");
  $numrows7 = mysql_result($result7, 0, "pocettopic");
echo "<TR><TD class=\"okno\" valign=\"top\">$lang[Category] : $numrows6<BR>$lang[Posts] : $numrows7\n";
?>
</TABLE>
</TABLE>
<BR></CENTER>
</BODY>
</HTML>