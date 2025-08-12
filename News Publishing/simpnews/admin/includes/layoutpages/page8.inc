<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_eventlists?></b></td>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_leftindent?>:</td>
<td><input type="text" class="sninput" name="evbindent" value="<?php echo $evbindent?>" size="6" maxlength="20"></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_eventcal?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_heading?>:</td>
<td align="left"><input class="sninput" type="text" name="eventheading" value="<?php echo $eventheading?>" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat?>:</td>
<td align="left"><input class="sninput" type="text" name="event_dateformat" value="<?php echo $event_dateformat?>" size="20" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformatwithtime?>:</td>
<td align="left"><input class="sninput" type="text" name="event_dateformat2" value="<?php echo $event_dateformat2?>" size="20" maxlength="20"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="eventcalmarkers" value="1" <?php if($eventcalonlymarkers==1) echo "checked"?>
<?php if($eventcalshortnews==1) echo "disabled"?>>
<?php echo $l_eventcalonlymarkers?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_evmarkcolgeneral?>:</td>
<?php echo color_chooser("evmarkcolgeneral",$evmarkcolgeneral)?>
<tr class="inputrow"><td>&nbsp;</td><td><input onclick="evcalshorts(this)" type="checkbox" name="eventcalshorts" value="1" <?php if($eventcalshortnews==1) echo "checked"?>>
<?php echo $l_eventcalshortnews?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="shortheadings" value="1" <?php if($eventcalshortonlyheadings==1) echo "checked"?>>
<?php echo $l_onlyheadings?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="showevnum" value="1" <?php if($displayevnum==1) echo "checked"?>> <?php echo $l_displayevnum?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_eventcalshortnum?>:</td>
<td><input class="sninput" type="text" name="eventcalshortnum" value="<?php echo $eventcalshortnum?>" size="4" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_eventcalshortlength?>:</td>
<td><input class="sninput" type="text" name="eventcalshortlength" value="<?php echo $eventcalshortlength?>" size="4" maxlength="10"> <?php echo $l_chars?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_weekstart?>:</td><td>
<select class="snlayout" name="weekstart">
<?php
for($i=0;$i<count($l_weekstarts);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$weekstart)
		echo " selected";
	echo ">".$l_weekstarts[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right">&nbsp;</td><td>
<input type="checkbox" name="evcalweek" value="1" <?php if($evshowcalweek==1) echo "checked"?>><?php echo $l_showcalweek?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_caljumpbox?></b></td>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="caljump" value="1" <?php if($caljumpbox==1) echo "checked"?>> <?php echo $l_enable?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_startyear?>:</td><td><?php echo $l_actualyear?> -
<input type="text" class="sninput" name="cjminyear" value="<?php echo $cjminyear?>" size="4" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_endyear?>:</td><td><?php echo $l_actualyear?> +
<input type="text" class="sninput" name="cjmaxyear" value="<?php echo $cjmaxyear?>" size="4" maxlength="10"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_grafiks?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_monthback?>:</td>
<?php echo gfx_selector("prevpic",$prevpic)?>
<tr class="inputrow"><td align="right"><?php echo $l_monthforward?>:</td>
<?php echo gfx_selector("fwdpic",$fwdpic)?>
<tr class="listheading0"><td align="left" colspan="2"><b>evlist3.php</b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat2?>:</td>
<td><input type="text" class="sninput" size="40" maxlength="40" name="ev3_dateformat" value="<?php echo $ev3_dateformat?>"></td></tr>