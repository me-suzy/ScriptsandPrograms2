<? 
include($DOCUMENT_ROOT . "/includes/config.inc.php"); 
include($DOCUMENT_ROOT . "/includes/header.php");
if($deflang)
{
	include("../includes/language/lang-".$deflang.".php");
}
else
{
	include("../includes/language/lang-english.php");
}
?>

<TABLE ALIGN="CENTER" VALIGN="TOP" WIDTH="750" CELLSPACING="0" CELLPADDING="0" BORDER="0">
<TR><TD>&nbsp;</TD></TR>
<TR>
<TD ALIGN="CENTER">

<TABLE ALIGN="CENTER" VALIGN="TOP" WIDTH="100%" CELLPADDING="2" BORDER="1" BORDERCOLOR="#000000" BORDERCOLORLIGHT="#000000" BORDERCOLORDARK="#000000">
<? printabout(2); ?>
<TR>
	<TD ALIGN="RIGHT" COLSPAN="2"><FONT SIZE="1">&nbsp;</FONT></TD>
</TR>
<TR>
	<TD ALIGN="RIGHT"><A HREF="admin/admin.php"><? echo GTGP_ADMIN_POST; ?></A></TD>
	<TD ALIGN="LEFT"><? echo GTGP_ADMIN_POST_OPIS; ?></TD>
</TR>
<TR>
	<TD ALIGN="RIGHT"><A HREF="admin/preferred.php"><? echo GTGP_ADMIN_PREF; ?></A></TD>
	<TD ALIGN="LEFT"><? echo GTGP_ADMIN_PREF_OPIS; ?></TD>
</TR>
<TR>
	<TD ALIGN="RIGHT"><A HREF="admin/blacklist.php"><? echo GTGP_ADMIN_BL; ?></A></TD>
	<TD ALIGN="LEFT"><? echo GTGP_ADMIN_BL_OPIS; ?></TD>
</TR>
<TR>
	<TD ALIGN="RIGHT"><A HREF="admin/categories.php"><? echo GTGP_ADMIN_CAT; ?></A></TD>
	<TD ALIGN="LEFT"><? echo GTGP_ADMIN_CAT_OPIS; ?></TD>
</TR>
<TR>
	<TD ALIGN="RIGHT"><A HREF="admin/config.setup.php"><? echo GTGP_ADMIN_CFG; ?></A></TD>
	<TD ALIGN="LEFT"><? echo GTGP_ADMIN_CFG_OPIS; ?></TD>
</TR>
<TR>
	<TD ALIGN="RIGHT"><A HREF="admin/config.templates.php"><? echo GTGP_ADMIN_TMPL; ?></A></TD>
	<TD ALIGN="LEFT"><? echo GTGP_ADMIN_TMPL_OPIS; ?></TD>
</TR>
<TR>
	<TD ALIGN="RIGHT"><A HREF="admin/check.list.php"><? echo GTGP_ADMIN_CU; ?></A></TD>
	<TD ALIGN="LEFT"><? echo GTGP_ADMIN_CU_OPIS; ?></TD>
</TR>
<TR>
	<TD ALIGN="RIGHT" COLSPAN="2"><FONT SIZE="1">&nbsp;</FONT></TD>
</TR>
<TR>
	<TD ALIGN="RIGHT">
	<?
		$query = "SELECT * FROM tblTgp WHERE newpost='yes' order by date";
		$result = mysql_query ($query) or die ("Query failed");
		$numerek = 0;
		if ($result)
		{
			while ($r = mysql_fetch_array($result))
			{
				$numerek++;
			}
		}
		echo "<B>". $numerek . "</B>";
	?>
	</TD>
	<TD ALIGN="LEFT"><? echo GTGP_ADMIN_POST_WAIT; ?></TD>
</TR>
<TR>
	<TD ALIGN="RIGHT" COLSPAN="2"><FONT SIZE="1">&nbsp;</FONT></TD>
</TR>
<TR>
	<TD ALIGN="RIGHT"><? echo GTGP_VERS; ?></TD>
	<TD ALIGN="LEFT"><? echo GTGP_ADMIN_VERS; ?></TD>
</TR>
<TR>
	      <TD ALIGN="RIGHT">&nbsp;</TD>
	      <TD ALIGN="LEFT">&nbsp;</TD>
</TR>
<TR>
	      <TD ALIGN="LEFT" COLSPAN="2">&nbsp;</TD>
</TR>
</TABLE>


</TD>
</TR>
</TABLE>
</body>
</html>
