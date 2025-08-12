<?php
include("../connect.php");
include("one.php");
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
<?php include("../themes/$style"); ?>
<BODY>
<CENTER><BR>
<TABLE border="0" cellspacing="0" cellpadding="0" width="600">
<TR><TD class="nadpis" align="center" valign="top">
<?php echo $board_title; ?>
<TR><TD class="news" align="center" valign="top">
<?php echo $board_char; ?>
<BR><BR>
<TABLE cellspacing="5" cellpadding="0">
<TR>
<TD><TABLE border="2" cellspacing="0" cellpadding="5">
<TR><TD width="70" class="vivo" align="center" onMouseOver="MenuOver(this);" onMouseOut="MenuOut(this);"><A HREF="../index.php" class="seed"><?php echo $lang[Category]; ?></A>
</TABLE>
</TABLE>
<TR><TD align="center" valign="top">
<TABLE border="0" cellspacing="5" cellpadding="0" width="100%">
<TR><TD class="oknox" align="center">
<?php echo $lang[Admin]; ?>
<TR><TD class="okno" align="center">
<FORM NAME="FORM" METHOD="post" ACTION="index.php?akc=prihlas">
<TABLE border="0" cellspacing="5" cellpadding="0" width="100%">
<TR><TD class="newsy" align="right"><?php echo $lang[Name]; ?> :
<TD><INPUT TYPE="text" NAME="login" SIZE="15" MAXLENGTH="50">
<TR><TD class="newsy" align="right"><?php echo $lang[Password]; ?> :
<TD><INPUT TYPE="password" NAME="heslo" SIZE="15" MAXLENGTH="50">
<TR><TD colspan="2" align="center"><BR><INPUT TYPE="SUBMIT" NAME="prihlaska" VALUE="<?php echo $lang[Confirm]; ?>" CLASS="submmit">
</TABLE>
</FORM>
</TABLE>
</TABLE>
<BR></CENTER>
</BODY>
</HTML>