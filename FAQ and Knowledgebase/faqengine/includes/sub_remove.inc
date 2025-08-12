<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
echo "<tr bgcolor=\"$irow_bgcolor\"><td align=\"center\" colspan=\"2\">";
echo "<span style=\"font-face: $FontFace; font-size: $irow_fontsize; color: $irow_fontcolor; font-weight: bold;\">";
echo $l_unsubscribe;
echo "</span></td></tr>\n";
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
	$sql = "select * from ".$tableprefix."_subscriptions where email='$email' and unsubscribeid='$id'";
	if(!$result = mysql_query($sql, $db))
		die("Could not connect to the database.");
	if(!$myrow=mysql_fetch_array($result))
	{
		$errmsg.="<tr bgcolor=\"#c0c0c0\" align=\"center\"><td class=\"error\">";
		$errmsg.="<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		$errmsg.= $l_noremoveentry."</td></tr>";
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
$removeprelude = str_replace("{email}",$email,$l_subscriptionremoveprelude);
echo "<TR BGCOLOR=\"$row_bgcolor\"><td align=\"center\">";
echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
echo $removeprelude."</span></td></tr>";
?>
<form name="inputform" method="post" action="<?php echo $act_script_url?>">
<?php
if(is_konqueror())
	echo "<tr><td></td></tr>";
if(isset($layout))
	echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
if(isset($limitprog))
	echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
?>
<input type="hidden" name="prog" value="<?php echo $prog?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="email" value="<?php echo $email?>">
<input type="hidden" name="id" value="<?php echo $id?>">
<input type="hidden" name="mode" value="delete">
<TR BGCOLOR="<?php echo $actionbgcolor?>">
<td align="center" colspan="2"><input class="faqebutton2" type="submit" value="<?php echo $l_yes?>"></td></tr></form>
<?php
echo "</table></td></tr></table></div>";
include_once("./includes/bottom.inc");
exit;
?>