<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_newsmail?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("emailbgcolor",$emailbgcolor)?>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_customheader?>:</td>
<td align="left"><textarea class="sninput" name="emailcustomheader" cols="40" rows="8"><?php echo do_htmlentities($emailcustomheader)?></textarea></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_customfooter?>:</td>
<td align="left"><textarea class="sninput" name="emailcustomfooter" cols="40" rows="8"><?php echo do_htmlentities($emailcustomfooter)?></textarea></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_emailpageremark?>:</td>
<td align="left"><textarea class="sninput" name="emailpageremark" cols="40" rows="8"><?php echo do_htmlentities($emailpageremark)?></textarea></td></tr>
