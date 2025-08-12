<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_newsletter?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("newsletterbgcolor",$newsletterbgcolor)?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_customheader?>:</td>
<td align="left"><textarea class="sninput" name="newslettercustomheader" cols="40" rows="8"><?php echo do_htmlentities($newslettercustomheader)?></textarea><br>
<?php display_bbcode_buttons($l_bbbuttons,"newslettercustomheader",false,false,"layoutform")?>
<input type="checkbox" name="nchnobrtrans" value="1" <?php if(bittst($nonltrans,BIT_3)) echo "checked"?>> <?php echo $l_nobrtranslation?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_customfooter?>:</td>
<td align="left"><textarea class="sninput" name="newslettercustomfooter" cols="40" rows="8"><?php echo do_htmlentities($newslettercustomfooter)?></textarea><br>
<?php display_bbcode_buttons($l_bbbuttons,"newslettercustomfooter",false,false,"layoutform")?>
<input type="checkbox" name="ncfnobrtrans" value="1" <?php if(bittst($nonltrans,BIT_4)) echo "checked"?>> <?php echo $l_nobrtranslation?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_subredirecturl?>:</td>
<td align="left"><input class="sninput" type="text" name="subredirecturl" size="40" maxlength="255" value="<?php echo $subredirecturl?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_alignment?>:</td>
<td><select class="snlayout" name="newsletteralign">
<?php
for($i=0;$i<count($l_newsletter_aligns);$i++)
{
	echo "<option value=\"$i\"";
	if($newsletteralign==$i)
		echo " selected";
	echo ">".$l_newsletter_aligns[$i]."</option>";
}
?>
</select></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>nlsenddate.php</b></td>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_heading?>:</td><td align="left">
<input type="text" class="sninput" name="nlsend_heading" value="<?php echo $nlsend_heading?>" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat2?></td><td align="left">
<input type="text" class="sninput" name="nlsend_dateformat" value="<?php echo $nlsend_dateformat?>" size="40" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("nlsend_bgcolor",$nlsend_bgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="nlsend_font" value="<?php echo do_htmlentities(stripslashes($nlsend_font))?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("nlsend_fontcolor",$nlsend_fontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="nlsend_fontsize" value="<?php echo $nlsend_fontsize?>" size="4" maxlength="10"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>massub.php</b></td>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="msublastsenddate" value="1" <?php if(bittst($masssuboptions,BIT_1)) echo "checked"?>> <?php echo $l_showlastsend?>
</td></tr>
