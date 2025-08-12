<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$usermail="";
$username="";
include_once('./includes/sesshandling.inc');
if(is_loggedin())
{
	$redirurl="evpropose.php?$langvar=$act_lang&layout=$layout&mode=proplst";
	if(isset($backurl))
		$redirurl.="&backurl=".urlencode($backurl);
	echo "<META HTTP-EQUIV=\"refresh\" content=\"0.01; URL=$redirurl\">";
	exit;
}
if($new_global_handling)
{
	if(isset($_COOKIE[$cookiename]))
		$cookiedata=$_COOKIE[$cookiename];
}
else
{
	if(isset($_COOKIE[$cookiename]))
		$cookiedata=$_COOKIE[$cookiename];
}
if(isset($cookiedata))
{
	if(sn_array_key_exists($cookiedata,"usermail"))
		$usermail=$cookiedata["usermail"];
	if(sn_array_key_exists($cookiedata,"username"))
		$username=$cookiedata["username"];
}
include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_proposed_events?></font>
</td></tr>
<tr bgcolor="<?php echo $contentbgcolor?>">
<td align="center" colspan="2">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo $l_proposelistprelude?></font></td></tr>
<form name="inputform" onsubmit="return checkform()" action="<?php echo $act_script_url?>" method="post">
<?php
if(is_konqueror())
	echo "<tr><td></td></tr>";
if(isset($backurl))
	echo "<input type=\"hidden\" name=\"backurl\" value=\"$backurl\">";
if(isset($redirurl))
	echo "<input type=\"hidden\" name=\"redirect\" value=\"$redirurl\">";
?>
<input type="hidden" name="layout" value="<?php echo $layout?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="mode" value="proplst">
<input type="hidden" name="category" value="<?php echo $category?>">
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo $l_email?>:</font></td><td align="left">
<input class="snewsinput" type="text" name="email" size="40" maxlength="80" value="<?php echo $usermail?>"></font></td></tr>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo $l_password?>:</font></td><td align="left">
<input class="snewsinput" type="password" name="password" size="40" maxlength="40"></font></td></tr>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<td align="center" colspan="2"><input class="snewsbutton" type="submit" name="dologin" value="<?php echo $l_login?>">
&nbsp;&nbsp;<input class="snewsbutton" type="submit" name="requestpw" value="<?php echo $l_requestpw?>">
</td></tr></form>
<?php
if(isset($backurl) && $backurl)
{
	echo "<tr bgcolor=\"$headingbgcolor\">";
	echo "<td align=\"center\" colspan=\"2\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo "<a class=\"actionlink\" href=\"$backurl\">$l_back2events</a>";
	echo "</font></td></tr>";
}
?>