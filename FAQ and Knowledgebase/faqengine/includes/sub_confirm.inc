<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$errors=0;
$errmsg="";
if(!isset($email) || !$email)
{
	$errmsg.="<tr bgcolor=\"#c0c0c0\" align=\"center\"><td class=\"error\">";
	$errmsg.="<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
	$errmsg.= $l_missingemail."</span></td></tr>";
	$errors=1;
}
if(!isset($id) || !$id)
{
	$errmsg.="<tr bgcolor=\"#c0c0c0\" align=\"center\"><td class=\"error\">";
	$errmsg.="<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
	$errmsg.= $l_missingid."</span></td></tr>";
	$errors=1;
}
if($errors==0)
{
	$actdate = date("Y-m-d H:i:s");
	$confirmtime=($maxconfirmtime*24)+1;
	$sql = "select * from ".$tableprefix."_subscriptions where email='$email' and subscribeid='$id' and enterdate>=DATE_SUB('$actdate', INTERVAL $confirmtime HOUR)";
	if(!$result = mysql_query($sql, $db))
		die("Could not connect to the database.");
	if(!$myrow=mysql_fetch_array($result))
	{
		$errmsg.="<tr bgcolor=\"#c0c0c0\" align=\"center\"><td class=\"error\">";
		$errmsg.="<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		$errmsg.= $l_noconfirmentry."</span></td></tr>";
		$errors=1;
	}
}
if($errors==1)
{
	echo $errmsg;
	echo "</table></td></tr></table></div>";
	include_once("./includes/bottom.inc");
	exit;
}
do{
	$maximum=9999999999;
	if($maximum>mt_getrandmax())
		$maximum=mt_getrandmax();
	mt_srand((double)microtime()*1000000);
	$unsubscribeid=mt_rand(10000,$maximum);
	$sql = "select * from ".$tableprefix."_subscriptions where unsubscribeid='$unsubscribeid'";
	if(!$result = mysql_query($sql, $db))
		die("Could not connect to the database.");
}while($myrow=mysql_fetch_array($result));
$sql = "update ".$tableprefix."_subscriptions set subscribeid=0, confirmed=1, unsubscribeid='$unsubscribeid' where email='$email' and subscribeid='$id'";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.");
echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
echo "<td align=\"center\" colspan=\"2\">";
echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
echo str_replace("{progname}",$progname,$l_subscriptionconfirmed)."</span></td></tr>";
echo "<tr bgcolor=\"$actionbgcolor\" align=\"center\">";
echo "<td align=\"center\" colspan=\"2\">";
echo "<span style=\"font-face: $FontFace; font-size: $actionlinefontsize; color: $FontColor;\">";
echo "<a class=\"actionlink\" href=\"$backurl\">$l_back2faq</a></span></td></tr>";
echo "</table></td></tr></table></div>";
include_once("./includes/bottom.inc");
exit;
?>