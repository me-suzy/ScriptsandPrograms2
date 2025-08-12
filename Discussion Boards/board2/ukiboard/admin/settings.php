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
<?php include ("../themes/$style"); ?>
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
<TR><TD class="oknox" align="center" colspan="3">
<?php
echo $lang[Admin];
$celkemtopics=0;
echo "<TR><TD align=\"center\">\n";
$result6 = MySQL_Query("SELECT config_value FROM $tblname_config WHERE config_name='board_email'");
  $board_email = mysql_result($result6,0,"config_value");
$result7 = MySQL_Query("SELECT config_value FROM $tblname_config WHERE config_name='board_admin'");
  $board_admin = mysql_result($result7,0,"config_value");
$result8 = MySQL_Query("SELECT config_value FROM $tblname_config WHERE config_name='board_page'");
  $board_page = mysql_result($result8,0,"config_value");
echo "<FORM ACTION=\"settingsdo.php\" METHOD=\"post\">\n";
echo "<TABLE border=\"0\" cellspacing=\"5\" cellpadding=\"0\">\n";
echo "<TR><TD class=\"teen\" align=\"center\"><B>$lang[Title]</B>\n";
echo "<TD class=\"teen\"><INPUT TYPE=\"text\" NAME=\"nazev\" SIZE=\"50\" MAXLENGTH=\"255\" VALUE=\"$board_title\">\n";
echo "<TR><TD class=\"teen\" align=\"center\"><B>$lang[Description]</B>\n";
echo "<TD class=\"teen\"><INPUT TYPE=\"text\" NAME=\"popis\" SIZE=\"50\" MAXLENGTH=\"255\" VALUE=\"$board_char\">\n";
echo "<TR><TD class=\"teen\" align=\"center\"><B>$lang[Adminemail]</B>\n";
echo "<TD class=\"teen\"><INPUT TYPE=\"text\" NAME=\"email\" SIZE=\"50\" MAXLENGTH=\"255\" VALUE=\"$board_email\">\n";
echo "<TR><TD class=\"teen\" align=\"center\"><B>$lang[Language]</B>\n";
echo "<TD class=\"teen\"><SELECT NAME=\"jazyk\">\n";
$handle=opendir('../languages');
while (false!==($file = readdir($handle))) {
  $filem = strrchr($file,'.');
  if ($filem == ".php") {
    $filen = strrpos($file,'.');
    $filek = substr($file,0,$filen);
    echo "<OPTION VALUE=\"$filek\"";
    if ($language==$file) echo " SELECTED";
    echo ">$filek</OPTION>\n";
  }
}
closedir($handle);
echo "</SELECT>\n";
echo "<TR><TD class=\"teen\" align=\"center\"><B>$lang[Theme]</B>\n";
echo "<TD class=\"teen\"><SELECT NAME=\"vzhled\">\n";
$handle=opendir('../themes');
while (false!==($file = readdir($handle))) {
  $filem = strrchr($file,'.');
  if ($filem == ".css") {
    $filen = strrpos($file,'.');
    $filek = substr($file,0,$filen);
    echo "<OPTION VALUE=\"$filek\"";
    if ($themes==$file) echo " SELECTED";
    echo ">$filek</OPTION>\n";
  }
}
closedir($handle);
echo "</SELECT>\n";
echo "<TR><TD class=\"teen\" align=\"center\"><B>$lang[Perpage]</B>\n";
echo "<TD class=\"teen\"><INPUT TYPE=\"text\" NAME=\"stranka\" SIZE=\"4\" MAXLENGTH=\"4\" VALUE=\"$board_page\">\n";
echo "<TR><TD class=\"teen\" align=\"center\"><B>$lang[Admin]</B>\n";
echo "<TD class=\"teen\"><INPUT TYPE=\"checkbox\" NAME=\"adminzobraz\" VALUE=\"yes\"";
if ($board_admin=="yes") echo " CHECKED";
echo ">\n";
echo "<TR><TD COLSPAN=\"2\" align=\"center\" class=\"teen\"><INPUT TYPE=\"submit\" class=\"submmit\" VALUE=\"$lang[Confirm]\">\n";
echo "</TABLE>\n";
echo "</FORM>\n";
?>
</TABLE>
</TABLE>
<BR></CENTER>
</BODY>
</HTML>