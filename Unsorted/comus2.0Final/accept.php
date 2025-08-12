<?
include($DOCUMENT_ROOT . "/includes/config.inc.php");
include($DOCUMENT_ROOT . "/includes/header.php");
if($deflang)
{
	include("includes/language/lang-".$deflang.".php");
}
else
{
	include("includes/language/lang-english.php");
}
?>
<TABLE BORDER="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="750" CELLSPACING="0" CELLPADDING="0" BORDER="0">
<TR><TD>&nbsp;</TD></TR>
<TR>
<TD ALIGN="CENTER">

<TABLE ALIGN="CENTER" VALIGN="TOP" WIDTH="100%" CELLPADDING="2" BORDER="1" BORDERCOLOR="#000000" BORDERCOLORLIGHT="#000000" BORDERCOLORDARK="#000000">
<TR>
	<TD align="center">
<?
If (isset($accept)){
   $Query = "UPDATE tblTgp SET newpost= '$accept', date= '$dnow' WHERE sessionid = '$seid'";                   
      $result = mysql_query($Query);
   echo "<BR><BR>". GTGP_ACC_TU .": <A HREF=\"http://$sitename\">$sitename</A>
		<br><BR>
		". GTGP_ACC_AC ."
        <br><BR>
        <b>
		". GTGP_ACC_RE ."
		</b><BR><BR>";
}
?>
	</TD>
</TR>
</TABLE>
<BR CLEAR="ALL"><BR>
</TD>
</TR>
</TABLE>
</body>
</html>
