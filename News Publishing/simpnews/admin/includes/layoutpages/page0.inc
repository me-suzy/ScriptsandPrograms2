<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2" nowrap valign="top"><b><?php echo $l_heading?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_text?>:</td>
<td align="left"><input class="sninput" type="text" name="heading" value="<?php echo $heading?>" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("headingbgcolor",$headingbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("headingfontcolor",$headingfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="headingfont" value="<?php echo $headingfont?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="headingfontsize" value="<?php echo $headingfontsize?>" size="4" maxlength="4"></td></tr>
