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
   $query = "SELECT * FROM tblTgp GROUP BY category";
   $result = mysql_query ($query)
        or die ("Query failed");
?>
<TABLE BORDER="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="750" CELLSPACING="0" CELLPADDING="0" BORDER="0">
<TR><TD>&nbsp;</TD></TR>
<TR>
<TD ALIGN="CENTER">

<TABLE ALIGN="CENTER" VALIGN="TOP" WIDTH="100%" CELLPADDING="2" BORDER="1" BORDERCOLOR="#000000" BORDERCOLORLIGHT="#000000" BORDERCOLORDARK="#000000">
<? printabout(2); ?>
<TR>
	<TD ALIGN="CENTER" COLSPAN="2">
	<A HREF="admin/index.php"><? echo GTGP_SET_RETURN; ?></A>
	<? echo "<BR><font color=red>$message</center><BR>"; ?>	
	</TD>
</TR>
<TR>
	<TD ALIGN="LEFT" COLSPAN="2">
	<? echo GTGP_ADMIN_CL; ?>
	</TD>
</TR>
<TR>
	<TD ALIGN="LEFT" COLSPAN="2">
	<B><? echo GTGP_ADMIN_CL_CLICK; ?></B>
	</TD>
</TR>
<tr>
	<TD ALIGN="LEFT" COLSPAN="2">
	<?
	if ($result)
	{
   	 while ($r = mysql_fetch_array($result))
	 { 
     $category = $r["category"];
     echo "<a href=\"admin/new.check.php?choice=$category\">$category</a><br>";
     }
   }?>
</td>
</tr>


</TABLE>
</TD>
</TR>
</TABLE>
</body>
</html>

