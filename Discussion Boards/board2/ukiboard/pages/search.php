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

<FORM ACTION="searching.php" METHOD="post">
<TABLE cellspacing="5" cellpadding="0">
<TR><TD class="teen" align="center"><B><?php echo $lang[Searchenter]; ?></B>
<TD class="teen"><INPUT TYPE="text" NAME="vyraz" SIZE="47" MAXLENGTH="255">
<TR><TD COLSPAN="2" align="center" class="teen"><INPUT TYPE="submit" class="submmit" VALUE=" <?php echo $lang[Searching]; ?> ">
</TABLE>
</FORM>

</TABLE>
</TABLE>
<BR></CENTER>
</BODY>
</HTML>