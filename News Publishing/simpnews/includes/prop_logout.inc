<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$errors=0;
$errmsg="";
$loggedin=false;
include_once('./includes/sesshandling.inc');
if(is_loggedin())
{
	end_session($userdata["entrynr"], $db);
}
include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo $l_logged_out?></font>
</td></tr>
<?php
if(isset($backurl) && $backurl)
{
	echo "<tr bgcolor=\"$headingbgcolor\">";
	echo "<td align=\"center\" colspan=\"2\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo "<a class=\"actionlink\" href=\"$backurl\">$backmsg</a>";
	echo "</font></td></tr>";
}
?>