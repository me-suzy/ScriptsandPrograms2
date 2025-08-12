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
<?php echo $l_edit_proposed_events?></font>
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
	if(bittst($proposepermissions,BIT_5))
	{
		unset($urlautoencode);
		unset($enablespcode);
		$disableemoticons=1;
	}
}
else
{
	if(bittst($proposepermissions,BIT_4) || ($userdata["disablebbcode"]==1))
	{
		unset($urlautoencode);
		unset($enablespcode);
		$disableemoticons=1;
	}
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
	if(!checkdate($sel_month,$sel_day,$sel_year))
	{
		$errors=1;
		$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		$tmpmsg=str_replace("{day}",$sel_day,$l_novaliddate);
		$tmpmsg=str_replace("{month}",$sel_month,$tmpmsg);
		$tmpmsg=str_replace("{year}",$sel_year,$tmpmsg);
		$errmsg.=$tmpmsg;
		$errmsg.="</td></tr>";
	}
	else
	{
		$event_time=mktime(0,0,0,$sel_month,$sel_day,$sel_year);
		$eventdate=date("Y-m-d",$event_time);
	}
	if($errors==1)
	{
		include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_edit_proposed_events?></font>
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
	$eventtext = $entrytext;
	if(isset($urlautoencode))
		$eventtext = make_clickable($eventtext);
	if(isset($enablespcode))
		$eventtext = bbencode($eventtext);
	if(!isset($disableemoticons))
		$eventtext = encode_emoticons($eventtext, $url_emoticons, $db);
	$eventtext = do_htmlentities($eventtext);
	$eventtext = str_replace("\n", "<BR>", $eventtext);
	$eventtext = str_replace("\r", "", $eventtext);
	$eventtext=addslashes($eventtext);
	$sql = "update ".$tableprefix."_events set heading='$input_heading', date='$eventdate', added='$actdate', text='$eventtext' where eventnr='$eventnr'";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_propose_event?></font></td></tr>
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