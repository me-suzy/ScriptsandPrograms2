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
if ($vyraz!="") {

  $result = mysql_query("SELECT *, MATCH (topic_title, topic_text, topic_user) AGAINST ('$vyraz') AS score FROM $tblname_topic WHERE (MATCH (topic_title, topic_text, topic_user) AGAINST ('$vyraz')) ORDER BY score DESC, topic_time DESC");
  $numRows = mysql_num_rows($result);

  echo "<TR><TD class=\"oknoy\" align=\"center\">$lang[Topics_found] : $numRows\n";
  if ($numRows>0) echo "<TR><TD class=\"okno\" align=\"top\">";

  $RowCount = 0;
    while($RowCount < $numRows)
    { 
      $topicid = mysql_result($result,$RowCount,"topic_id"); 
      $topictitle = mysql_result($result,$RowCount,"topic_title"); 
      $topicuser = mysql_result($result,$RowCount,"topic_user"); 
      $topictime = mysql_result($result,$RowCount,"topic_time");
      $headid = mysql_result($result,$RowCount,"topic_head");
      $cast1 = SubStr($topictime,8,2);
      $cast2 = SubStr($topictime,5,2);
      $cast3 = SubStr($topictime,0,4);
      $cast4 = SubStr($topictime,11); 
      echo "&nbsp;&nbsp;<A HREF=\"showtopic.php?topicid=$topicid&headid=$headid\" class=\"fouk\">[$cast1.$cast2.$cast3&nbsp;$cast4]</A>&nbsp;&nbsp;";
      echo "<SPAN class=\"meno\">$topicuser</SPAN>\n";
      if ($topictitle!="") echo" - $topictitle\n";
      echo "<BR>\n";
      $RowCount++;
    } 
  } else {
    echo "<TR><TD class=\"oknoy\" align=\"center\">$lang[Searchenter]\n";
}
?>

</TABLE>
</TABLE>
<BR></CENTER>
</BODY>
</HTML>