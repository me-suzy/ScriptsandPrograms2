<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$page="nlmaint";
include_once('./includes/head.inc');
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td align="center" colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_subscriptionmaint?></font></td></tr>
<?php
$sql="select * from ".$tableprefix."_texts where textid='nlminfo' and lang='$act_lang'";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
if($myrow=mysql_fetch_array($result))
{
	echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
	echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo undo_htmlspecialchars(stripslashes($myrow["text"]));
	echo "</font></td></tr>";
}
?>
<form name="subscriptionform" onsubmit="return checkform()" action="<?php echo $act_script_url?>" method="post">
<?php
if(is_konqueror())
	echo "<tr><td></td></tr>";
?>
<input type="hidden" name="layout" value="<?php echo $layout?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>"><?php echo $l_email?>:</font></td>
<td align="left"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>"><input class="snewsinput" type="text" name="email" maxlength="240" size="40"></font></td></tr>
<input type="hidden" name="mode" value="listsubs">
<?php
if(isset($backurl))
	echo "<input type=\"hidden\" name=\"backurl\" value=\"$backurl\">";
?>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<td align="center" colspan="2"><input class="snewsbutton" type="submit" value="<?php echo $l_listsubs?>"></td></tr></form>
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