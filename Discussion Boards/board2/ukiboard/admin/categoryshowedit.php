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
<TR><TD class="oknox" align="center">
<?php
echo $lang[Admin];
$result6 = MySQL_Query("SELECT * FROM $tblname_topic WHERE topic_id='$id'");
  $topichead = mysql_result($result6,0,"topic_head");
  $topicuser = mysql_result($result6,0,"topic_user");
  $topicemail = mysql_result($result6,0,"topic_email");
  $topictime = mysql_result($result6,0,"topic_time");
  $topictitle = mysql_result($result6,0,"topic_title");
  $topictext = mysql_result($result6,0,"topic_text");
  $topictext = strip_tags($topictext);
?>

<TR><TD align="center">
<FORM ACTION="categoryshoweditdo.php" METHOD="post">
<TABLE border="0" cellspacing="5" cellpadding="0">
<TR><TD class="teen" align="center"><B><?php echo $lang[Author]; ?></B>
<TD class="teen"><INPUT TYPE="text" NAME="autor" SIZE="50" MAXLENGTH="255" VALUE="<?php echo $topicuser; ?>">
<TR><TD class="teen" align="center"><B><?php echo $lang[Email]; ?></B>
<TD class="teen"><INPUT TYPE="text" NAME="email" SIZE="50" MAXLENGTH="255" VALUE="<?php echo $topicemail; ?>">
<TR><TD class="teen" align="center"><B><?php echo $lang[Post_subject]; ?></B>
<TD class="teen"><INPUT TYPE="text" NAME="predmet" SIZE="50" MAXLENGTH="255" VALUE="<?php echo $topictitle; ?>">
<TR><TD class="teen" align="center"><B><?php echo $lang[Date]; ?></B>
<TD class="teen"><INPUT TYPE="text" NAME="datum" SIZE="25" VALUE="<?php echo $topictime; ?>">
<TR>
<TD class="teen" colspan="2" align="center"><TEXTAREA NAME="prispevek" COLS="46" ROWS="6"><?php echo $topictext; ?></TEXTAREA>
<TR><TD class="teen" align="center"><B><?php echo $lang[Category]; ?></B>
<TD class="teen">
<?php
echo "<SELECT NAME=\"hlava\" SIZE=\"1\">\n";
$result7 = MySQL_Query("SELECT * FROM $tblname_head ORDER BY head_order");
  $numRows = mysql_num_rows($result7);
  $RowCount = 0;
  while($RowCount < $numRows)
    {
    $sheadid = mysql_result($result7,$RowCount,"head_id");
    $sheadname = mysql_result($result7,$RowCount,"head_name");
    echo "<OPTION VALUE=\"$sheadid\"";
    if ($sheadid==$topichead) echo " selected";
    echo ">$sheadname</OPTION>\n";
    $RowCount++;
  }
echo "</SELECT>\n";
echo "<INPUT TYPE=\"hidden\" NAME=\"starahlava\" VALUE=\"$topichead\">\n";
?>
<TR><TD COLSPAN="2" align="center" class="teen"><INPUT TYPE="hidden" NAME="id" VALUE="<?php echo $id; ?>"><INPUT TYPE="submit" class="submmit" VALUE=" <?php echo $lang[Edit]; ?> ">
</TABLE>
</FORM>

</TABLE>
</TABLE>
<BR></CENTER>
</BODY>
</HTML>