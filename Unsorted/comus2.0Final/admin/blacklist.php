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
<? printabout(3); ?>
<TR>
	<TD ALIGN="CENTER" COLSPAN="3">
	<A HREF="admin/index.php"><? echo GTGP_SET_RETURN; ?></A>
	<? echo "<BR><font color=red>$message</center>"; ?>	
	</TD>
</TR>
<TR>
	<TD ALIGN="LEFT" COLSPAN="3">
	<B><? echo GTGP_ADMIN_BL; ?></B>
	</TD>
</TR>
<form method=post action="admin/blacklist.php">
<TR>
	<TD ALIGN="RIGHT" WIDTH="40%">
		<input type=text name="BlackEmail">
	</TD>
	<TD ALIGN="RIGHT" WIDTH="10%">
		<select name="BlackType">
        <option value="email"><? echo GTGP_ADMIN_BL_EMAIL; ?></option>
        <option value="domain"><? echo GTGP_ADMIN_BL_DOMAIN; ?></option>
        </select>	
	</TD>
	<TD ALIGN="LEFT"  WIDTH="50%"><input type="submit" name="BlackSubmit" value="<? echo GTGP_ADMIN_BL_ADD; ?>"></TD>
</TR>
</form>

<?
/* Delete From Blacklist */
   if (isset($zap)) {
      $Query = "DELETE FROM tblBlacklist WHERE id='$id'";
      $result = mysql_query($Query, $conn);
   }
   /* ADD SOMEONE TO THE BLACKLIST  */
	if (isset($BlackSubmit))	
	{
		mysql_query("INSERT into tblBlacklist (email,type) VALUES ('$BlackEmail','$BlackType')");
		if ($BlackType == "email")
		{
			$message2 = GTGP_ADMIN_BL_A ."<B> ". $BlackEmail ."</B> ". GTGP_ADMIN_BL_B;
		}else{
			$message2 = GTGP_ADMIN_BL_AD ."<B> ". $BlackEmail ."</B> ". GTGP_ADMIN_BL_BD;
		}
	}
/* View all of Blacklist */
   $query = "SELECT * FROM tblBlacklist order by type ASC";
   $result = mysql_query ($query)
        or die ("Query failed");
	if ($result)
	{
	while ($r = mysql_fetch_array($result))
	{
		$id		= $r["id"];
		$email1	= $r["email"];
		$type	= tslbl($r["type"]);
		echo "
			<tr>
			<TD ALIGN=\"RIGHT\" WIDTH=\"40%\">$email1</td>
			<TD ALIGN=\"RIGHT\" WIDTH=\"10%\">$type</td>
			<TD ALIGN=\"LEFT\" WIDTH=\"50%\">
			<a href=\"admin/blacklist.php?zap=yes&id=$id\">". GTGP_ADMIN_BL_DEL ."</a></div>
			</td>
			</tr>";   
      } /* end of while loop */
   }  /* end of result */
?>
<TR>
	<TD ALIGN="CENTER" COLSPAN="3">
	<? echo "<BR><font color=red>$message2</center><BR>"; ?>	
	</TD>
</TR>
</TABLE>
</TD>
</TR>
</TABLE>
</body>
</html>
