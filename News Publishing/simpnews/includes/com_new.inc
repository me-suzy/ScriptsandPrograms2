<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_writecomment?></font>
</td></tr>
<form name="inputform" onsubmit="return checkform()" action="<?php echo $act_script_url?>" method="post">
<?php
if(is_konqueror())
	echo "<tr><td></td></tr>";
?>
<input type="hidden" name="layout" value="<?php echo $layout?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="entryref" value="<?php echo $entryref?>">
<input type="hidden" name="mode" value="add">
<input type="hidden" name="postingid" value="<?php echo microtime()?>">
<?php
if(isset($backurl))
	echo "<input type=\"hidden\" name=\"backurl\" value=\"$backurl\">";
?>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<b>*<?php echo $l_name?>:</b></font></td>
<td align="left">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<input class="snewsinput" type="text" name="poster" size="40" maxlength="80"></font></td></tr>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo $l_email?>:</font></td>
<td align="left"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<input class="snewsinput" type="text" name="email" size="40" maxlength="80"></font></td></tr>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td align="right" width="30%" valign="top">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<b>*<?php echo $l_comment?>:</b></font></td>
<td align="left">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<textarea class="snewsinput" name="comment" cols="40" rows="5"></textarea></font></td></tr>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<td align="center" colspan="2"><input class="snewsbutton" type="submit" value="<?php echo $l_ok?>"></td></tr></form>
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
