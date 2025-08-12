<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
include("../connect.php");
include("../function/selecty.php");
include("../languages/$language");
?>
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
<TR><TD class="spodek" align="center" valign="top">
<?php echo $board_char; ?>
<BR><BR>
<?php include("../function/menushow.php"); ?>
<TR><TD align="center" valign="top">
<TABLE border="0" cellspacing="5" cellpadding="0" width="100%">

<?php
$result6 = MySQL_Query("SELECT config_value FROM $tblname_config WHERE config_name='board_page'");
  $board_page = mysql_result($result6,0,"config_value");
$result7 = MySQL_Query("SELECT * FROM $tblname_head WHERE head_id='$headid'");
  $headname = mysql_result($result7,$RowCount,"head_name");
  $headnumber = mysql_result($result7,$RowCount,"head_number");
  echo "<TR><TD class=\"oknoy\" align=\"center\">$lang[Category] : $headname\n";

  $resultlimit = mysql_query("SELECT COUNT(*) AS pocet FROM $tblname_topic WHERE topic_head='$headid'");
  if ($resultlimit!="") {
    if (!isset($strana)) $strana=0;
    $stranisko = $strana*$board_page;
    $memberlimitvyber=$stranisko.",".$board_page;
    $rowslimit = mysql_result($resultlimit, 0, "pocet");

  $result8 = MySQL_Query("SELECT * FROM $tblname_topic WHERE topic_head='$headid' ORDER BY topic_time DESC LIMIT $memberlimitvyber");
    $numRows = mysql_num_rows($result8);
    $RowCount = 0;
    while($RowCount < $numRows)
    {
      $topicid = mysql_result($result8,$RowCount,"topic_id");
      $topicuser = mysql_result($result8,$RowCount,"topic_user");
      $topicemail = mysql_result($result8,$RowCount,"topic_email");
      $topictime = mysql_result($result8,$RowCount,"topic_time");
      $topictitle = mysql_result($result8,$RowCount,"topic_title");
      $topictext = mysql_result($result8,$RowCount,"topic_text");
      $cast1 = SubStr($topictime,8,2);
      $cast2 = SubStr($topictime,5,2);
      $cast3 = SubStr($topictime,0,4);
      $cast4 = SubStr($topictime,11);
      echo "<TR><TD class=\"okno\" valign=\"top\" width=\"600\"><I>$cast1.$cast2.$cast3&nbsp;$cast4</I>&nbsp;<B>- <VAR class=\"meno\">$topicuser</VAR>";
      if ($topicemail!="") echo " - <A HREF=\"mailto:$topicemail\" class=\"menu\">$topicemail</A>";
      echo "<BR>&nbsp;&nbsp;$topictitle</B><BR>$topictext\n";
    $RowCount++;
    }

    $stranecka=$strana+1;
    $trakar = bcdiv($rowslimit,$board_page,0);
    $trakarik=$trakar+1;

    if ($rowslimit>$board_page) {
      echo "<TR><TD class=\"oknoy\" align=\"center\">";

      if ($strana!=0) {
        $stranavlevo = $strana-1;
        echo"<A HREF=\"show.php?headid=$headid&strana=$stranavlevo\"><--</A>&nbsp;&nbsp;";
      }

      echo "$lang[Page] : $stranecka / $trakarik\n";

      if ($strana<$trakar) {
        $stranavpravo = $strana+1;
        echo"&nbsp;&nbsp;<A HREF=\"show.php?headid=$headid&strana=$stranavpravo\">--></A>";
      }

    } else {
      echo "<TR><TD class=\"oknoy\" align=\"center\">";
      echo "$lang[Page] : $stranecka / $trakarik\n";
    }
}
?>
</TABLE>

<A NAME="pridejpost"></A>
<TR><TD align="center" class="news">
<BR><FORM ACTION="add.php" METHOD="post">
<INPUT TYPE="hidden" NAME="headid" VALUE="<?php echo $headid; ?>">
<TABLE cellspacing="5" cellpadding="0">
<TR><TD class="teen"><B><?php echo $lang[Name]; ?> *</B>
<TD class="teen"><INPUT TYPE="text" NAME="inputposter" SIZE="47" MAXLENGTH"255">
<TR><TD class="teen"><?php echo $lang[Email]; ?>
<TD class="teen"><INPUT TYPE="text" NAME="inputemail" SIZE="47" MAXLENGTH"255">
<TR><TD class="teen"><?php echo $lang[Post_subject]; ?>
<TD class="teen"><INPUT TYPE="text" NAME="inputtitle" SIZE="47" MAXLENGTH"255">
<TR><TD COLSPAN="2" valign="top" class="teen"><?php echo $lang[Post]; ?> *<BR>
<TEXTAREA NAME="inputbody" COLS="44" ROWS="7"></TEXTAREA>
<TR><TD COLSPAN="2" align="center" class="teen"><INPUT TYPE="submit" class="submmit" VALUE=" <?php echo $lang[Send]; ?> ">
<TR><TD COLSPAN="2" valign="top" class="teen">* - <?php echo $lang[Fill]; ?><BR>
</TABLE>
</FORM>

</TABLE>
<BR></CENTER>
</BODY>
</HTML>