<?
include("../includes/header.php");
include("../includes/formcheck.inc.php"); 
include("../includes/config.inc.php");
include("../includes/language/lang-".$deflang.".php");

/*
if($newlang)
{
	include("../includes/language/lang-".$newlang.".php");
}
else
{
	include("../includes/language/lang-english.php");
}
*/
?>
<TABLE ALIGN="CENTER" VALIGN="TOP" WIDTH="750" CELLSPACING="0" CELLPADDING="0" BORDER="0">
<TR><TD>&nbsp;</TD></TR>
<TR>
<TD ALIGN="CENTER">

<TABLE ALIGN="CENTER" VALIGN="TOP" WIDTH="100%" CELLPADDING="2" BORDER="1" BORDERCOLOR="#000000" BORDERCOLORLIGHT="#000000" BORDERCOLORDARK="#000000">
<?
if($posting == 'No')
{
	echo "<TR><TD ALIGN=\"CENTER\" COLSPAN=\"2\"><BR>".GTGP_WM_POSTING."<BR><BR></TD></TR></TABLE></TD></TR></TABLE></body></html>";
	die();
}
?>
<?
if($makelngbox == 'Yes')
{
?>
<TR>
	<TD ALIGN="RIGHT" COLSPAN="2">
	<? /*
	echo GTGP_WM_LANG . "&nbsp;:&nbsp;";
	$currentlang = $lang;
	$content .= "<select name=\"language\" onChange=\"top.location.href=this.options[this.selectedIndex].value\">";
	$handle=opendir("../includes/language");
	while ($file = readdir($handle))
	{
		if (preg_match("/^lang\-(.+)\.php/", $file, $matches))
		{
			$langFound = $matches[1];
			$languageslist .= "$langFound ";
		}
	}
	closedir($handle);
	$languageslist = explode(" ", $languageslist);
	sort($languageslist);
	for ($i=0; $i < sizeof($languageslist); $i++)
	{
		if($languageslist[$i]!="")
		{
			$content .= "<option value=\"".$PHP_SELF."?newlang=$languageslist[$i]\"";
			if($languageslist[$i]==$currentlang) $content .= " selected";
			$content .= ">".ucfirst($languageslist[$i])."</option>\n";
		}
	}
	$content .= "</select></form></center>";
	echo $content;
	*/?>
	</TD>
</TR>
<?
}
?>
<TR>
	<TD ALIGN="CENTER" COLSPAN="2">
	&nbsp;
	<? @include ($DOCUMENT_ROOT . "/templates/html_rules.txt"); ?>
	</TD>
</TR>
<form action="webmaster/post.php" method=POST onSubmit="return(checkit())" name="info">
<INPUT TYPE="HIDDEN" NAME="newlang" VALUE="<? if($newlang){echo $newlang;}else{echo "polish";} ?>">
<TR>
	<TD WIDTH="40%" ALIGN="RIGHT"><INPUT TYPE="TEXT" NAME="nickname" SIZE="20" value="<?if($gtgp_nick){echo $gtgp_nick;}?>"></TD>
	<TD WIDTH="60%" ALIGN="LEFT"><? echo GTGP_WM_NAME; ?></TD>
</TR>
<TR>
	<TD WIDTH="40%" ALIGN="RIGHT"><INPUT TYPE="TEXT" NAME="email" SIZE="20" value="<?if($gtgp_email){echo $gtgp_email;}?>"></TD>
	<TD WIDTH="60%" ALIGN="LEFT"><? echo GTGP_WM_EMAIL; ?></TD>
</TR>
<TR>
	<TD WIDTH="40%" ALIGN="RIGHT"><INPUT TYPE="TEXT" NAME="url" VALUE="http://" SIZE="20"></TD>
	<TD WIDTH="60%" ALIGN="LEFT"><? echo GTGP_WM_URL; ?></TD>
</TR>
<TR>
	<TD WIDTH="40%" ALIGN="RIGHT"><input type="text" SIZE="20" name="description" maxlength=<? $descleng; ?>></TD>
	<TD WIDTH="60%" ALIGN="LEFT"><? echo GTGP_WM_DES; ?></TD>
</TR>
<TR>
	<TD WIDTH="40%" ALIGN="RIGHT"><INPUT TYPE="TEXT" NAME="numpic" SIZE="10" value="<?if($gtgp_numpic){echo $gtgp_numpic;}?>"></TD>
	<TD WIDTH="60%" ALIGN="LEFT"><? echo GTGP_WM_PIC; ?></TD>
</TR>
<?  if($usepreferred == 'Yes')
{
?>
<TR>
	<TD WIDTH="40%" ALIGN="RIGHT"><INPUT TYPE="TEXT" NAME="pass" SIZE="10" value="<?if($gtgp_pass){echo $gtgp_pass;}?>"></TD>
	<TD WIDTH="60%" ALIGN="LEFT"><? echo GTGP_WM_PAS; ?></TD>
</TR>
<?}?>
<?
if($useemail == 'Yes')
{
?>
<TR>
	<TD WIDTH="40%" ALIGN="RIGHT"><input class=inputek type="checkbox" name="mailme" value="yes" <?if($gtgp_mailme == 'yes'){echo "checked";}?>></TD>
	<TD WIDTH="60%" ALIGN="LEFT"><? echo GTGP_WM_MAILME; ?></TD>
</TR>
<?
}
?>
<TR>
	<TD WIDTH="40%" ALIGN="RIGHT">
    <select name=category>
    <?
    $query = "SELECT * FROM tblCategories ORDER BY Category";
    $result = mysql_query ($query) or die (GTGP_WM_ERROR_CAT);
	if ($result)
	{
		while ($r = mysql_fetch_array($result))
		{ 
		$Category = $r["Category"];
		echo"<option>$Category";
		}
    }
?>
    </select>	
	</TD>
	<TD WIDTH="60%" ALIGN="LEFT"><? echo GTGP_WM_CAT; ?></TD>
</TR>

<TR>
	<TD WIDTH="40%" ALIGN="RIGHT"><input class=inputek type="checkbox" name="storeuser" value="yes" <?if($gtgp_store == 'yes'){echo "checked";}?>></TD>
	<TD WIDTH="60%" ALIGN="LEFT"><? echo GTGP_WM_STOREUSER; ?></TD>
</TR>
<TR>
	<TD WIDTH="40%" ALIGN="RIGHT">&nbsp;</TD>
	<TD WIDTH="60%" ALIGN="LEFT"><input type=submit value="<? echo GTGP_WM_SUBMIT; ?>" name="submit"></TD>
</TR>
<TR><TD ALIGN="CENTER" COLSPAN="2">&nbsp;</TD></TR>
<TR>
	<TD ALIGN="LEFT" COLSPAN="2">
	<? echo GTGP_WM_CONTACT . ": " . "<A HREF=\"mailto:$tgpemail\">$siteowner</A>" ?>
	</TD>
</TR>
<TR>
	<TD ALIGN="LEFT" COLSPAN="2">
		&nbsp;
		<? @include ($DOCUMENT_ROOT . "/templates/html_recip.txt"); ?>
	</TD>
</TR>
<TR><TD ALIGN="CENTER" COLSPAN="2">
		<BR><BR>
		<? echo GTGP_WM_POWERED . " <A HREF=\"http://www.nibbi.net/scripts/comus/\" target=\"_blank\"><B>" . GTGP_NAME . "</B></A>&nbsp;" . GTGP_VERS; ?>
</TD></TR>
</TABLE>
</form>

</TD>
</TR>
</TABLE>
</body>
</html>
