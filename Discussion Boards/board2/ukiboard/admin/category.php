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
echo "<TR><TD class=\"oknoy\" align=\"center\" width=\"420\">$lang[Category]<TD class=\"oknox\" align=\"center\" width=\"120\"><A HREF=\"categorycreate.php\" class=\"menu\">$lang[Create]</A><TD class=\"oknoy\" align=\"center\" width=\"60\">$lang[Posts]\n";
  $result6 = MySQL_Query("SELECT * FROM $tblname_head ORDER BY head_order");
    $numRows = mysql_num_rows($result6);
    $RowCount = 0;
      while($RowCount < $numRows)
      {
      $headid = mysql_result($result6,$RowCount,"head_id");
      $headname = mysql_result($result6,$RowCount,"head_name");
      $headnumber = mysql_result($result6,$RowCount,"head_number");
      $headchar = mysql_result($result6,$RowCount,"head_char");
      echo "<TR><TD class=\"okno\" valign=\"top\"><A HREF=\"categoryshow.php?headid=$headid\">$headname</A><BR>&nbsp;&nbsp;$headchar<TD class=\"okno\" align=\"center\" width=\"130\"><A HREF=\"categoryedit.php?id=$headid\" class=\"menu\">$lang[Edit]</A> - <A HREF=\"categorydelete.php?id=$headid\" class=\"menu\">$lang[Delete]</A><TD class=\"okno\" align=\"center\">$headnumber\n";
      $celkemtopics=$celkemtopics+$headnumber;
      $RowCount++;
      }
echo "<TR><TD><BR><TD class=\"oknox\" align=\"center\"><A HREF=\"categoryorder.php\" class=\"menu\">$lang[Order]</A><TD><BR>\n";
?>
</TABLE>
</TABLE>
<BR></CENTER>
</BODY>
</HTML>