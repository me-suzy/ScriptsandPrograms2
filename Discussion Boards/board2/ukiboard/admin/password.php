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
<?php echo $lang[Admin]; ?>

<TR><TD align="center">
<FORM ACTION="passworddo.php" METHOD="post">
<TABLE border="0" cellspacing="5" cellpadding="0">
<TR><TD COLSPAN="2" align="center" class="oknoy"><?php echo $lang[Changename]; ?>
<TR><TD class="teen" align="center"><B><?php echo $lang[Name]; ?></B>
<TD class="teen"><INPUT TYPE="text" NAME="pasjmeno" SIZE="50" MAXLENGTH="255" VALUE="<?php echo $onemj; ?>">
<TR><TD COLSPAN="2" align="center" class="teen"><INPUT TYPE="hidden" NAME="codelat" VALUE="1"><INPUT TYPE="submit" class="submmit" VALUE=" <?php echo $lang[Confirm]; ?> ">
</TABLE>
</FORM>

<TR><TD align="center">
<FORM ACTION="passworddo.php" METHOD="post">
<TABLE border="0" cellspacing="5" cellpadding="0">
<TR><TD COLSPAN="2" align="center" class="oknoy"><?php echo $lang[Changepassword]; ?>
<TR><TD class="teen" align="center"><B><?php echo $lang[Passwordold]; ?></B>
<TD class="teen"><INPUT TYPE="password" NAME="pashesloold" SIZE="50" MAXLENGTH="255">
<TR><TD class="teen" align="center"><B><?php echo $lang[Passwordnew]; ?></B>
<TD class="teen"><INPUT TYPE="password" NAME="pasheslonew" SIZE="50" MAXLENGTH="255">
<TR><TD class="teen" align="center"><B><?php echo $lang[Passwordcon]; ?></B>
<TD class="teen"><INPUT TYPE="password" NAME="pasheslocon" SIZE="50" MAXLENGTH="255">
<TR><TD COLSPAN="2" align="center" class="teen"><INPUT TYPE="hidden" NAME="codelat" VALUE="2"><INPUT TYPE="submit" class="submmit" VALUE=" <?php echo $lang[Confirm]; ?> ">
</TABLE>
</FORM>

</TABLE>
</TABLE>
<BR></CENTER>
</BODY>
</HTML>