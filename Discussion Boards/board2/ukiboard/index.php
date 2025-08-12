<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
include("connect.php");
include("function/selecty.php");
include("languages/$language");
?>
<HTML>
<HEAD>
<TITLE><?php echo $board_title; ?></TITLE>
<META http-equiv=Content-Type content="text/html; charset=<?php echo $lang[Encoding]; ?>">
<LINK HREF="themes/<?php echo $themes; ?>" REL="stylesheet" TYPE="text/css">
</HEAD>
<?php include ("themes/$style"); ?>
<BODY>
<CENTER><BR>
<TABLE border="0" cellspacing="0" cellpadding="0" width="600">
<TR><TD class="nadpis" align="center" valign="top">
<?php echo $board_title; ?>
<TR><TD class="spodek" align="center" valign="top">
<?php echo $board_char; ?>
<BR><BR>
<?php include("function/menuroot.php"); ?>
<TR><TD align="center" valign="top">
<TABLE cellspacing="5" cellpadding="0">
<?php
$celkemtopics=0;
echo "<TR><TD class=\"oknoy\" align=\"center\" width=\"540\">$lang[Category]<TD class=\"oknoy\" align=\"center\" width=\"60\">$lang[Posts]\n";
include("connect.php");
if ($Con2!=false) {
  $result6 = MySQL_Query("SELECT * FROM $tblname_head ORDER BY head_order");
    $numRows = mysql_num_rows($result6);
    $RowCount = 0;
      while($RowCount < $numRows)
      {
      $headid = mysql_result($result6,$RowCount,"head_id");
      $headname = mysql_result($result6,$RowCount,"head_name");
      $headnumber = mysql_result($result6,$RowCount,"head_number");
      $headchar = mysql_result($result6,$RowCount,"head_char");
      echo "<TR><TD class=\"okno\" valign=\"top\"><A HREF=\"pages/show.php?headid=$headid\">$headname</A><BR>&nbsp;&nbsp;$headchar<TD class=\"okno\" align=\"center\">$headnumber\n";
      $celkemtopics=$celkemtopics+$headnumber;
      $RowCount++;
      }

  $result7 = MySQL_Query("SELECT config_value FROM $tblname_config WHERE config_name='board_admin'");
    $board_admin = mysql_result($result7,0,"config_value");
}
?>
<TR><TD class="oknox" align="center" colspan="2">
<TABLE BORDER="0" width="100%">
<TR class="news"><TD>

<?php
echo "$lang[Posts] : $celkemtopics";
if ($board_admin=="yes") {
  echo "<TD align=\"center\">\n";
  } else {
    echo "<TD align=\"right\">\n";
}
echo "$lang[Administrator] : ";
echo "<A HREF=\"mailto:$board_email\" class=\"fouk\">$board_email</A>\n";
if ($board_admin=="yes") {
  echo "<TD align=\"right\">\n";
  echo "<A HREF=\"admin/index.php\" class=\"menu\">$lang[Admin]</A>\n";
  } else {
    echo "<BR>";
}
?>

</TABLE>

</TABLE>
<TR><TD align="center" class="spodek"><BR>
<?php include("function/copy.php"); ?>
</TABLE>
<BR></CENTER>
</BODY>
</HTML>