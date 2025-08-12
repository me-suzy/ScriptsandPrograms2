<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
include_once('./includes/sesshandling.inc');
$loggedin=is_loggedin();
if(!$loggedin)
{
	include_once("./includes/head.inc");
	$link="$act_script_url?$langvar=$lang&layout=$layout&mode=edlst";
	if(isset($backurl))
		$link.="&backurl=".urlencode($backurl);
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_edit_proposed_news?></font>
</td></tr>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo "$l_loginfirst"?></font>
</td></tr>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<a href="<?php echo $link?>" class="actionlink"><?php echo $l_login?></a></font>
</td></tr>
<?php
}
else
{
	$errors=0;
	$errmsg="";
	if(!isset($input_heading))
		$input_heading="";
	if(!isset($entrytxt))
		$entrytxt="";
	$entrytxt=trim($entrytxt);
	$heading=trim($heading);
	if(bittst($proposereq,BIT_3) && !$input_heading)
	{
		$errors=1;
		$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		$errmsg.="$l_noheading</td></tr>";
	}
	if(!isset($entrytext) || !$entrytext)
	{
		$errors=1;
		$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		$errmsg.="$l_notext</td></tr>";
	}
	if($errors==1)
	{
		include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_edit_proposed_news?></font>
</td></tr>
<?php
		echo $errmsg;
		echo "<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "<a class=\"actionlink\" href=\"javascript:history.back()\">$l_back</a></td></tr>";
		echo "</table></td></tr></table>";
		include_once("./includes/footer.inc");
		exit;
	}
	$actdate = date("Y-m-d H:i:s");
	$newstext = $entrytext;
	$newstext = do_htmlentities($newstext);
	$newstext = str_replace("\n", "<BR>", $newstext);
	$newstext = str_replace("\r", "", $newstext);
	$newstext=addslashes($newstext);
	$sql = "update ".$tableprefix."_tmpdata set heading='$input_heading', date='$actdate', text='$newstext' where entrynr=$propnr";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_propose_news?></font></td></tr>
<?php
	echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
	echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo "$l_entryupdated</td></tr>";
	$linkurl="$act_script_url?$langvar=$act_lang&mode=edlst&layout=$layout";
	if(isset($backurl))
		$linkurl.="&backurl=".urlencode($backurl);
	echo "<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
	echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
	echo "<a class=\"actionlink\" href=\"$linkurl\">";
	echo "$l_editownproposals</a></font></td></tr>";
}
?>