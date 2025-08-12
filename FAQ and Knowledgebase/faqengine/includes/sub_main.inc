<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<form name="subscriptionform" onsubmit="return checkform()" action="<?php echo $act_script_url?>" method="post">
<?php
if($navframe==1)
	echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
if(isset($limitprog))
	echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
if(isset($layout))
	echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
if(is_konqueror())
	echo "<tr><td></td></tr>";
?>
<input type="hidden" name="prog" value="<?php echo $prog?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<tr bgcolor="<?php echo $row_bgcolor?>" align="center">
<td align="right" width="30%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php echo $l_sendermail?>:</span></td>
<td align="left">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input class="faqeinput" type="text" name="email" maxlength="240" size="40"></span></td></tr>
<?php
if(!isset($prog))
{
	$sql="select * from ".$tableprefix."_programm where subscriptionavail=1 and language='$act_lang' group by progid order by displaypos asc";
	if(!$result = faqe_db_query($sql, $db))
	    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
	if(!$myrow = faqe_db_fetch_array($result))
	    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>$l_noprogstosubscribe");
	echo "<tr bgcolor=\"$row_bgcolor\">";
	echo "<td align=\"right\">";
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
	echo $l_program.":</span></td><td><select class=\"faqeselect\" name=\"prog\">";
	do{
		echo "<option value=\"".$myrow["progid"]."\">";
		echo display_encoded($myrow["programmname"]);
		echo "</option>";
	}while($myrow = faqe_db_fetch_array($result));
	echo "</select></td></tr>";
}
else
	echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
?>
<tr bgcolor="<?php echo $row_bgcolor?>" align="center">
<td align="right" width="30%" valign="top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php echo $l_emailtype?>:</span></td>
<td align="left"><span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input type="radio" name="emailtype" value="0" checked> <?php echo $l_htmlmail?><br>
<input type="radio" name="emailtype" value="1"> <?php echo $l_ascmail?></span></td></tr>
<?php
if($zlibavail==1)
{
?>
<TR BGCOLOR="<?php echo $row_bgcolor?>">
<td align="right" valign="top"><span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php echo $l_compressmail?>:</span></td>
<td align="left"><span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input type="radio" name="compress" value="1" checked><?php echo $l_yes?><br>
<input type="radio" name="compress" value="0"><?php echo $l_no?></span></td></tr>
<?php
}
else
	echo "<input type=\"hidden\" name=\"compress\" value=\"0\">";
?>
<TR BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<td>&nbsp;</td><td align="left"><span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input type="radio" name="mode" value="subscribe" checked><?php echo $l_subscribe?><br>
<input type="radio" name="mode" value="unsubscribe"><?php echo $l_unsubscribe?></span></td></tr>
<?php
if(isset($backurl))
	echo "<input type=\"hidden\" name=\"backurl\" value=\"".htmlentities($backurl)."\">";
?>
<TR BGCOLOR="<?php echo $actionbgcolor?>" ALIGN="CENTER">
<td align="center" colspan="2"><input class="faqebutton2" type="submit" value="<?php echo $l_ok?>"></td></tr></form>
</table></td></tr></table></div>
<?php
include_once('./includes/bottom.inc');
?>