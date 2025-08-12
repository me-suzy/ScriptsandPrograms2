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
  /* Delete a Category */
if (isset($zap)) {
  $Query = "DELETE FROM tblCategories WHERE id='$id'";
     $result = mysql_query($Query, $conn);
}
/* ADD a Category */
if (isset($AddCategory)) {
   mysql_query("INSERT into tblCategories (category) VALUES ('$category')");
         $message2 = GTGP_ADMIN_CAT_ADD_B . "&nbsp;<B>$category</B>&nbsp;" . GTGP_ADMIN_CAT_ADD_A;
}

/* Display Add Category Form */
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
	<? echo "<BR><font color=red>$message</center>"; ?>	
	</TD>
</TR>
<form method="post" action="admin/categories.php">
<TR><TD ALIGN="CENTER" COLSPAN="2"><FONT COLOR="RED"><? echo $message2; ?></FONT></TD></TR>
<TR>
	<TD ALIGN="RIGHT" WIDTH="30%"><input type=text name="category"></TD>
	<TD ALIGN="LEFT"  WIDTH="70%"><? echo GTGP_ADMIN_CAT_ADD; ?></TD>
</TR>
<TR>
	<TD ALIGN="RIGHT" WIDTH="30%">&nbsp;</TD>
	<TD ALIGN="LEFT"  WIDTH="70%"><input type="submit" name="AddCategory" value="<? echo GTGP_ADMIN_CAT_SUBMIT; ?>"></TD>
</TR>
</FORM>

<?
/* View all Categories */

   $query = "SELECT * FROM tblCategories order by category";
   $result = mysql_query ($query)
        or die ("Query failed");
   
   if ($result)
   {
   echo "<TR><TD ALIGN=\"LEFT\" COLSPAN=\"2\"><B>" . GTGP_ADMIN_CAT_ALL . "</B></TD></TR>";
   while ($r = mysql_fetch_array($result))
   { 
   $id = $r["id"];
   $cat = $r["Category"];
   echo "
   <tr> 
   <td width=\"30%\" align=\"right\">$cat</td>
   <td width=\"70%\" align=\"left\"><a href=\"admin/categories.php?zap=yes&id=$id\">". GTGP_ADMIN_CAT_DELETE ."</a></td>
   </tr>
   ";
     } /* end of while loop */
   }  /* end of result */
?>
</TABLE>
</TD>
</TR>
</TABLE>
</body>
</html>
