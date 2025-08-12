<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
include_once('./includes/sesshandling.inc');
$loggedin=is_loggedin();
$usebbcode=true;
if(!$loggedin)
{
	if(bittst($proposepermissions,BIT_5))
		$usebbcode=false;
}
else
{
	if(bittst($proposepermissions,BIT_4) || ($userdata["disablebbcode"]==1))
		$usebbcode=false;
}
include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_edit_proposed_news?></font>
</td></tr>
<?php
if(!$loggedin)
{
		$link="$act_script_url?$langvar=$lang&layout=$layout&mode=edlst";
		if(isset($backurl))
			$link.="&backurl=".urlencode($backurl);
?>
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
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo "$l_loggedinas ".$userdata["email"]?>&nbsp; <a class="actionlink" href="<?php echo $link?>"><?php echo $l_logout?></a></font>
</td></tr>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<b><?php echo $l_transferrednews?></b></font>
</td></tr>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<b>
<?php
	if($usebbcode)
		include_once("./includes/bbcode_buttons.inc");
	$sql = "select * from ".$tableprefix."_data where newsnr='$newsnr'";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(!$myrow=mysql_fetch_array($result))
		die("<tr class=\"errorrow\"><td>No such entry");
	$displayheading=do_htmlentities(stripslashes($myrow["heading"]));
	$newstext = stripslashes($myrow["text"]);
	$newstext = str_replace("<BR>", "\n", $newstext);
	$newstext = undo_htmlspecialchars($newstext);
	$newstext = decode_emoticons($newstext, $url_emoticons, $db);
	$newstext = bbdecode($newstext);
	$newstext = undo_make_clickable($newstext);
	if($myrow["category"]==0)
		echo $l_general;
	else
	{
		$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		$tmprow=mysql_fetch_array($tmpresult);
		$cattext=display_encoded(stripslashes($tmprow["catname"]));
		$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$tmprow["catnr"]." and lang='".$act_lang."'";
		if(!$tmpresult2=mysql_query($tmpsql2,$db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if($tmprow2=mysql_fetch_array($tmpresult2))
		{
			if(strlen($tmprow2["catname"])>0)
				$cattext=display_encoded(stripslashes($tmprow2["catname"]));
		}
		echo $cattext;
	}
?>
</b></font>
</td></tr>
<form name="inputform" onsubmit="return checkform_edit()" action="<?php echo $act_script_url?>" method="post">
<?php
if(is_konqueror())
	echo "<tr><td></td></tr>";
if(isset($backurl))
	echo "<input type=\"hidden\" name=\"backurl\" value=\"$backurl\">";
?>
<input type="hidden" name="layout" value="<?php echo $layout?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="directpost">
<input type="hidden" name="submode" value="news">
<input type="hidden" name="newsnr" value="<?php echo $newsnr?>">
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php
	if(bittst($proposereq,BIT_3))
		echo "<b>*";
	echo $l_heading.":";
	if(bittst($proposereq,BIT_3))
		echo "</b>";
?>
</font></td>
<td align="left"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<input class="snewsinput" type="text" name="input_heading" size="40" maxlength="80" value="<?php echo $displayheading?>"></font></td></tr>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%" valign="top">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<b>*<?php echo $l_text?>:</b></font></td>
<td align="left">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<textarea class="snewsinput" name="entrytext" cols="40" rows="5"><?php echo $newstext?></textarea></font><br>
<?php
if($usebbcode)
{
	display_smiliebox("entrytext");
	display_bbcode_buttons($l_bbbuttons, "entrytext", true, true);
?>
</td></tr>
<tr class="optionrow"><td align="right" valign="top"><?php echo $l_options?>:</td><td align="left">
<input type="checkbox" name="urlautoencode" value="1" checked> <?php echo $l_urlautoencode?><br>
<input type="checkbox" name="enablespcode" value="1" checked> <?php echo $l_enablespcode?><br>
<input type="checkbox" name="disableemoticons" value="1"> <?php echo $l_disableemoticons?><br>
<?php
}
else
	echo "<input type=\"hidden\" name=\"disableemoticons\" value=\"1\">";
?>
</td></tr>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<td align="center" colspan="2"><input class="snewsbutton" type="submit" value="<?php echo $l_ok?>"></td></tr></form>
<?php
	if(isset($actionurl) && $actionurl)
	{
		echo "<tr bgcolor=\"$headingbgcolor\">";
		echo "<td align=\"center\" colspan=\"2\">";
		echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "<a class=\"actionlink\" href=\"$actionurl";
		if(isset($backurl) && ($backurl))
			echo "&backurl=".urlencode($backurl);
		echo "\">$l_editownproposals</a>";
		echo "</font></td></tr>";
	}
}
?>
