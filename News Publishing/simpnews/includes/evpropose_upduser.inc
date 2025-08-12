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
	if(!isset($usernr) || ($usernr!=$userdata["entrynr"]))
		die("<tr class=\"errorrow\"><td>Calling error");
	$errors=0;
	$errmsg="";
	if(!isset($email) || !$email)
	{
		$errors=1;
		$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		$errmsg.="$l_noemail</td></tr>";
	}
	else
	{
		if(!validate_email($email))
		{
			$errors=1;
			$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.="$l_novalidemail</td></tr>";
		}
		else
			$email=strtolower($email);
	}
	if(isset($password) && $password)
	{
		if($password!=$password2)
		{
			$errors=1;
			$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.="$l_passwordmismatch</td></tr>";
		}
	}
	if($errors==1)
	{
		include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_propose_news?></font></td></tr>
<?php
		echo $errmsg;
		echo "<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "<a class=\"actionlink\" href=\"javascript:history.back()\">$l_back</a></td></tr>";
		echo "</table></td></tr></table>";
		include_once("./includes/footer.inc");
		exit;
	}
	$sql="update ".$tableprefix."_poster set email='$email'";
	if(isset($password) && $password)
		$sql.=", password='".md5($password)."'";
	$sql.="where entrynr=".$userdata["entrynr"];
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
	$link="$act_script_url?$langvar=$act_lang&layout=$layout&mode=logout";
	if(isset($backurl))
		$link.="&backurl=".urlencode($backurl);
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo "$l_loggedinas ".$userdata["email"]?>&nbsp; <a class="actionlink" href="<?php echo $link?>"><?php echo $l_logout?></a></font>
</td></tr>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="center" colspan="2">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo $l_entryupdated?></font></td></tr>
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
