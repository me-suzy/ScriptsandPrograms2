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

$result6 = MySQL_Query("SELECT config_value FROM $tblname_config WHERE config_name='board_page'");
  $board_page = mysql_result($result6,0,"config_value");

$result7 = MySQL_Query("SELECT * FROM $tblname_head WHERE head_id='$headid'");
  $headname = mysql_result($result7,$RowCount,"head_name");
  $headnumber = mysql_result($result7,$RowCount,"head_number");
  echo "<TR><TD class=\"oknoy\" align=\"center\" colspan=\"2\">$lang[Category] : $headname\n";

  if (!isset($strana)) $strana=1;
  $trakar = bcdiv($headnumber,$board_page,0);
  $lastpage = bcmod($headnumber,$board_page);
  if ($lastpage!=0) $trakar++;

  $result8 = MySQL_Query("SELECT * FROM $tblname_topic WHERE topic_head='$headid' ORDER BY topic_time DESC");
    $numRows = mysql_num_rows($result8);
    $RowCount = 0;
    $RowCountik = 0;

    if ($numRows==$board_page) {
      $trakarik = $trakar-1;
      } else {
        $trakarik = $trakar;
      }

    if ($numRows>$board_page) {
      $numRows=$board_page;
      if ($strana==$trakar) $numRows=$headnumber-($trakar-1)*$board_page;
      $RowCountik=($strana-1)*$board_page;
    }

    while($RowCount < $numRows)
    {
      $topicid = mysql_result($result8,$RowCount+$RowCountik,"topic_id");
      $topicuser = mysql_result($result8,$RowCount+$RowCountik,"topic_user");
      $topicemail = mysql_result($result8,$RowCount+$RowCountik,"topic_email");
      $topictime = mysql_result($result8,$RowCount+$RowCountik,"topic_time");
      $topictitle = mysql_result($result8,$RowCount+$RowCountik,"topic_title");
      $topictext = mysql_result($result8,$RowCount+$RowCountik,"topic_text");
      $cast1 = SubStr($topictime,8,2);
      $cast2 = SubStr($topictime,5,2);
      $cast3 = SubStr($topictime,0,4);
      $cast4 = SubStr($topictime,11);
      echo "<TR><TD class=\"okno\" valign=\"top\" width=\"480\"><I>$cast1.$cast2.$cast3&nbsp;$cast4</I>&nbsp;<B>- <VAR class=\"meno\">$topicuser</VAR>";
      if ($topicemail!="") echo " - <A HREF=\"mailto:$topicemail\" class=\"menu\">$topicemail</A>";
      echo "<BR>&nbsp;&nbsp;$topictitle</B><BR>$topictext<TD class=\"okno\" align=\"center\" width=\"120\"><A HREF=\"categoryshowedit.php?id=$topicid\" class=\"menu\">$lang[Edit]</A> - <A HREF=\"categoryshowdelete.php?id=$topicid\" class=\"menu\">$lang[Delete]</A>\n";
      $RowCount++;
    }

    if ($trakar!=0) {

      echo "<TR><TD class=\"oknoy\" align=\"center\" colspan=\"2\">";

      if ($strana!=1) {
        $stranavlevo = $strana-1;
        echo"<A HREF=\"categoryshow.php?headid=$headid&strana=$stranavlevo\"><--</A>&nbsp;&nbsp;";
      }

      echo "$lang[Page] : $strana / $trakar\n";

      if ($strana<$trakar) {
        $stranavpravo = $strana+1;
        echo"&nbsp;&nbsp;<A HREF=\"categoryshow.php?headid=$headid&strana=$stranavpravo\">--></A>";
      }

    }
?>
</TABLE>
</TABLE>
<BR></CENTER>
</BODY>
</HTML>