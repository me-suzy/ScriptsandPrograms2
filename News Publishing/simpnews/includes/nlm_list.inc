<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$page="nlmaint";
include_once('./includes/head.inc');
$errmsg="";
$errors=0;
if(!isset($email) || !$email)
{
	$errmsg.= "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
	$errmsg.= "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	$errmsg.= "$l_noemail</td></tr>";
	$errors=1;
}
else if(!validate_email($email))
{
	$errmsg.= "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
	$errmsg.= "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	$errmsg.= "$l_novalidemail</td></tr>";
	$errors=1;
}
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td align="center" colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_subscriptionmaint?></font></td></tr>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td align="center" colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>"><b><?php echo "$l_subscriptionsfor $email"?></b></font></td></tr>
<form name="subscriptionform" onsubmit="return checkform2()" action="<?php echo $act_script_url?>" method="post">
<?php
if(is_konqueror())
	echo "<tr><td></td></tr>";
?>
<input type="hidden" name="layout" value="<?php echo $layout?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%" valign="top"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>"><?php echo $l_category?>:</td>
<td align="left"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php
$sql="select sub.subscriptionnr, cat.catname from ".$tableprefix."_subscriptions sub, ".$tableprefix."_categories cat where sub.email='$email' and sub.confirmed=1 and sub.category!=0 and sub.category=cat.catnr order by cat.catname asc";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
while($myrow=mysql_fetch_array($result))
{
	$cattext=display_encoded(stripslashes($myrow["catname"]));
	$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$myrow["catnr"]." and lang='".$act_lang."'";
	if(!$tmpresult2=mysql_query($tmpsql2,$db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if($tmprow2=mysql_fetch_array($tmpresult2))
	{
		if(strlen($tmprow2["catname"])>0)
			$cattext=display_encoded(stripslashes($tmprow2["catname"]));
	}
	echo "<input type=\"checkbox\" name=\"subnr[]\" value=\"".$myrow["subscriptionnr"]."\"> ".$cattext."<br>";
}
?>
</font></td></tr>
<input type="hidden" name="mode" value="unsubscribe">
<?php
if(isset($backurl))
	echo "<input type=\"hidden\" name=\"backurl\" value=\"$backurl\">";
?>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<td align="center" colspan="2"><input class="snewsbutton" type="submit" value="<?php echo $l_unsubscribe?>"></td></tr></form>
<?php
if(isset($backurl) && $backurl)
{
	echo "<tr bgcolor=\"$headingbgcolor\">";
	echo "<td align=\"center\" colspan=\"2\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo "<a class=\"actionlink\" href=\"$backurl\">$l_back</a>";
	echo "</font></td></tr>";
}
?>
</table></td></tr></table>
<?php
include_once("./includes/footer.inc");
?>