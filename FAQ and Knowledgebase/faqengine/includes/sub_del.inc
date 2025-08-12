<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
echo "<tr bgcolor=\"$irow_bgcolor\"><td align=\"center\" colspan=\"2\">";
echo "<span style=\"font-face: $FontFace; font-size: $irow_fontsize; color: $irow_fontcolor; font-weight: bold;\">";
echo $l_unsubscribe;
echo "</span></td></tr>\n";
$errmsg="";
$errors=0;
if(!isset($email) || !$email)
{
	$errmsg.="<tr bgcolor=\"#c0c0c0\" align=\"center\"><td class=\"error\">";
	$errmsg.="<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
	$errmsg.=$l_missingemail."</span></td></tr>";
	$errors=1;
}
if(!isset($id) || !$id)
{
	$errmsg.="<tr bgcolor=\"#c0c0c0\" align=\"center\"><td class=\"error\">";
	$errmsg.="<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
	$errmsg.=$l_missingid."</span></td></tr>";
	$errors=1;
}
if($errors==1)
{
	echo $errmsg;
	echo "</table></td></tr></table></div>";
	include_once("./includes/bottom.inc");
	exit;
}
$sql = "delete from ".$tableprefix."_subscriptions where email='$email' and unsubscribeid='$id'";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.");
echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
echo "<td align=\"center\" colspan=\"2\">";
echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
echo str_replace("{progname}",$progname,$l_unsubscribed)."</span></td></tr>";
echo "<tr bgcolor=\"$actionbgcolor\" align=\"center\">";
echo "<td align=\"center\" colspan=\"2\">";
echo "<span style=\"font-face: $FontFace; font-size: $actionlinefontsize; color: $FontColor;\">";
echo "<a class=\"actionlink\" href=\"$backurl\">$l_back2faq</a></span></td></tr>";
echo "</table></td></tr></table></div>";
include_once("./includes/bottom.inc");
exit;
?>