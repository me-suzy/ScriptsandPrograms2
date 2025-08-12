<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_emails?></b></td>
<tr class="inputrow"><td align="right" valign="top" width="30%"><?php echo $l_signature?>:</td>
<td><textarea class="sninput" name="defsignature" rows="5" cols="40"><?php echo $defsignature?></textarea></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_subscriptions?></b></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_emailremark?>:</td>
<td><textarea class="sninput" name="emailremark" rows="5" cols="40"><?php echo do_htmlentities($emailremark)?></textarea></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_subscriptionbox?></td><td>
<input type="radio" name="displaysubscriptionbox" value="0"<?php if($displaysubscriptionbox==0) echo "checked"?>> <?php echo $l_dontdisplay?><br>
<input type="radio" name="displaysubscriptionbox" value="1"<?php if($displaysubscriptionbox==1) echo "checked"?>> <?php echo $l_displaysubscriptionbox?><br>
<input type="radio" name="displaysubscriptionbox" value="2"<?php if($displaysubscriptionbox==2) echo "checked"?>> <?php echo $l_displaysubscriptionlink?><br>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_newsletterformat?>:</td><td>
<select name="subemailtype">
<?php
	for($i=0;$i<count($l_newsletterformats);$i++)
	{
		echo "<option value=\"$i\"";
		if($subemailtype==$i)
			echo " selected";
		echo ">".$l_newsletterformats[$i]."</option>";
	}
?>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_box?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("subscriptionbgcolor",$subscriptionbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("subscriptionfontcolor",$subscriptionfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="subscriptionfont" value="<?php echo do_htmlentities(stripslashes($subscriptionfont))?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="subscriptionfontsize" value="<?php echo $subscriptionfontsize?>" size="4" maxlength="4"></td></tr>
