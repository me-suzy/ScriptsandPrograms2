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
$result6 = MySQL_Query("SELECT * FROM $tblname_head WHERE head_id='$id'");
  $headname = mysql_result($result6,0,"head_name");
  $headchar = mysql_result($result6,0,"head_char");
?>

<TR><TD align="center">
<FORM ACTION="categoryeditdo.php" METHOD="post">
<TABLE border="0" cellspacing="5" cellpadding="0">
<TR><TD class="teen" align="center"><B><?php echo $lang[Title]; ?></B>
<TD class="teen"><INPUT TYPE="text" NAME="nazev" SIZE="20" MAXLENGTH="255" VALUE="<?php echo $headname; ?>">
<TR><TD class="teen" align="center"><B><?php echo $lang[Description]; ?></B>
<TD class="teen"><INPUT TYPE="text" NAME="popis" SIZE="50" MAXLENGTH="255" VALUE="<?php echo $headchar; ?>">
<TR><TD COLSPAN="2" align="center" class="teen"><INPUT TYPE="hidden" NAME="id" VALUE="<?php echo $id; ?>"><INPUT TYPE="submit" class="submmit" VALUE=" <?php echo $lang[Edit]; ?> ">
</TABLE>
</FORM>

</TABLE>
</TABLE>
<BR></CENTER>
</BODY>
</HTML>