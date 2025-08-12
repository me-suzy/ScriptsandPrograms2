<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
include_once('./includes/sesshandling.inc');
$loggedin=is_loggedin();
include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_edituserentry?></font>
</td></tr>
<?php
if(!$loggedin)
{
		$link="$act_script_url?$langvar=$lang&layout=$layout&mode=edlst";
		if(isset($backurl))
			$link.="&backurl=".urlencode($backurl);
		$redirurl="propose.php?$langvar=$act_lang&layout=$layout&mode=eduser";
		if(isset($backurl) && $backurl)
			$redirurl.="&backurl=".urlencode($backurl);
		$link.="&redirurl=".urlencode($redirurl);
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
	$link="$act_script_url?$langvar=$act_lang&layout=$layout&mode=logout";
	if(isset($backurl))
		$link.="&backurl=".urlencode($backurl);
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo "$l_loggedinas ".$userdata["email"]?>&nbsp; <a class="actionlink" href="<?php echo $link?>"><?php echo $l_logout?></a></font>
</td></tr>
<?php
	echo "<form name=\"inputform\" method=\"post\" action=\"$act_script_url\" onsubmit=\"return checkuserform()\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if(isset($backurl) && $backurl)
		echo "<input type=\"hidden\" name=\"backurl\" value=\"$backurl\">";
	echo "<input type=\"hidden\" name=\"usernr\" value=\"".$userdata["entrynr"]."\">";
?>
<input type="hidden" name="layout" value="<?php echo $layout?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="upduser">
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo $l_email?>:</font></td>
<td align="left"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<input class="snewsinput" type="text" name="email" size="40" maxlength="80" value="<?php echo $userdata["email"]?>"></font></td></tr>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php
echo $l_newpassword.":";
?>
</font></td>
<td align="left"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<input class="snewsinput" type="password" name="password" size="40" maxlength="20"></font></td></tr>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php
echo $l_passwordconfirm.":";
?>
</font></td>
<td align="left"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<input class="snewsinput" type="password" name="password2" size="40" maxlength="20"></font></td></tr>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<td align="center" colspan="2"><input class="snewsbutton" type="submit" value="<?php echo $l_ok?>"></td></tr></form>
<?php
	$actionurl="$act_script_url?$langvar=$act_lang&mode=edlst&layout=$layout";
	if(isset($backurl))
		$actionurl.="&backurl=".urlencode($backurl);
	echo "<tr bgcolor=\"$headingbgcolor\">";
	echo "<td align=\"center\" colspan=\"2\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo "<a class=\"actionlink\" href=\"$actionurl\">";
	echo "$l_editownproposals</a>";
	echo "</font></td></tr>";
}
?>
