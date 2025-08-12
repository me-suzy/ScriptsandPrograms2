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
<TR><TD class="oknox" align="center" colspan="2">
<?php
echo $lang[Admin];
$celkemtopics=0;
echo "<TR><TD class=\"oknoy\" align=\"center\" width=\"60\">$lang[Order]<TD class=\"oknoy\" align=\"center\">$lang[Category]\n";
echo "<FORM ACTION=\"categoryorderdo.php\" METHOD=\"post\">\n";
$result6 = MySQL_Query("SELECT * FROM $tblname_head ORDER BY head_order");
  $numRows = mysql_num_rows($result6);
  $RowCount = 0;
  $poradnik = 1;
  while($RowCount < $numRows)
  {
    $headid = mysql_result($result6,$RowCount,"head_id");
    echo "<TR><TD class=\"okno\" align=\"center\">$poradnik.<TD class=\"okno\" align=\"center\">\n";
    echo "<SELECT NAME=\"volba[$RowCount]\" SIZE=\"1\">\n";
    $result7 = MySQL_Query("SELECT * FROM $tblname_head ORDER BY head_name");
    $sloupec = mysql_num_rows($result7);
    $radek = 0;
    while($radek < $sloupec)
    {
      $sheadid = mysql_result($result7,$radek,"head_id");
      $sheadname = mysql_result($result7,$radek,"head_name");
      echo "<OPTION VALUE=\"$sheadid\"";
      if ($sheadid==$headid) echo " selected";
      echo ">$sheadname</OPTION>\n";
      $radek++;
    }
    echo "</SELECT>\n";
    $poradnik++;
    $RowCount++;
  }
  echo "<TR><TD COLSPAN=\"2\" align=\"center\" class=\"oknoy\"><INPUT TYPE=\"submit\" class=\"submmit\" VALUE=\"$lang[Edit]\">\n";
  echo "</FORM>\n";
?>
</TABLE>
</TABLE>
<BR></CENTER>
</BODY>
</HTML>