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
<?php include("../function/menuout.php"); ?>
<TR><TD align="center" valign="top">
<TABLE cellspacing="5" cellpadding="0" width="100%">

<?php
$result6 = MySQL_Query("SELECT * FROM $tblname_head WHERE head_id='$headid'");
  $headname = mysql_result($result6,$RowCount,"head_name");
  $headnumber = mysql_result($result6,$RowCount,"head_number");
  echo "<TR><TD class=\"oknoy\" align=\"center\">$lang[Category] : $headname\n";

  $result7 = MySQL_Query("SELECT * FROM $tblname_topic WHERE topic_id='$topicid'");
    $topicuser = mysql_result($result7,0,"topic_user");
    $topicemail = mysql_result($result7,0,"topic_email");
    $topictime = mysql_result($result7,0,"topic_time");
    $topictitle = mysql_result($result7,0,"topic_title");
    $topictext = mysql_result($result7,0,"topic_text");
    $cast1 = SubStr($topictime,8,2);
    $cast2 = SubStr($topictime,5,2);
    $cast3 = SubStr($topictime,0,4);
    $cast4 = SubStr($topictime,11);
    echo "<TR><TD class=\"okno\" valign=\"top\" width=\"600\"><I>$cast1.$cast2.$cast3&nbsp;$cast4</I>&nbsp;<B>- <VAR class=\"meno\">$topicuser</VAR>";
    if ($topicemail!="") echo " - <A HREF=\"mailto:$topicemail\" class=\"menu\">$topicemail</A>";
    echo "<BR>&nbsp;&nbsp;$topictitle</B><BR>$topictext\n";
    echo "<TR><TD class=\"oknoy\"><a href=\"javascript:history.go(-1);\">..:: $lang[Back] ::..</a>";
?>

</TABLE>
</TABLE>
<BR></CENTER>
</BODY>
</HTML>