<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_js_scripts?></b></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_entrieslinkto?>:</td><td>
<input type="radio" name="usejslinkdest" value="0" <?php if($usejslinkdest==0) echo "checked"?>>singlenews/announce/events<br>
<input type="radio" name="usejslinkdest" value="1" <?php if($usejslinkdest==1) echo "checked"?>><?php echo $l_snincscript?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_snincscript?>:<br><span class="remark">(<?php echo $l_url2script2?>)</span></td><td valign="top">
<table width="100%" align="left" border="0" cellspacing="0" cellpadding="0">
<tr><td align="right" width="20%">snews.php:</td><td align="left"><input type="text" class="sninput" size="40" maxlength="240" name="jslinkdest" value="<?php echo $jslinkdest?>"></td></tr>
<tr><td align="right" width="20%">an_inc.php:</td><td align="left"><input type="text" class="sninput" size="40" maxlength="240" name="jslinkdestan" value="<?php echo $jslinkdestan?>"></td></tr>
<tr><td align="right" width="20%">ev_inc.php:</td><td align="left"><input type="text" class="sninput" size="40" maxlength="240" name="jslinkdestev" value="<?php echo $jslinkdestev?>"></td></tr>
</table>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_js_newsfader?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_width?>:</td>
<td align="left"><input class="sninput" type="text" name="jsnf_width" value="<?php echo $jsnf_width?>" size="4" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_height?>:</td>
<td align="left"><input class="sninput" type="text" name="jsnf_height" value="<?php echo $jsnf_height?>" size="4" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="jsnf_font" value="<?php echo do_htmlentities(stripslashes($jsnf_font))?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("jsnf_fontcolor",$jsnf_fontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="jsnf_fontsize" value="<?php echo $jsnf_fontsize?>" size="4" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_delay?>:</td>
<td align="left"><input class="sninput" type="text" name="jsnf_delay" value="<?php echo $jsnf_delay?>" size="4" maxlength="10"> ms</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="jsnf_linkingoff" value="1" <?php if($jsnf_nolinking) echo "checked"?>> <?php echo $l_jsnf_nolinking?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linktarget?>:</td>
<td align="left"><input class="sninput" type="text" name="jsnf_linktarget" value="<?php echo $jsnf_linktarget?>" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="jsnf_showdate" value="1" <?php if($jsnf_displaydate) echo "checked"?>> <?php echo $l_displaydate?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat?>:</td>
<td align="left"><input class="sninput" type="text" name="jsnf_dateformat" value="<?php echo $jsnf_dateformat?>" size="20" maxlength="20"></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_showednews?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxentries?>:</td>
<td align="left"><input class="sninput" type="text" name="jsnf_maxentries" value="<?php echo $jsnf_maxentries?>" size="4" maxlength="4"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_timeframe?>:</td>
<td align="left"><input class="sninput" type="text" name="jsnf_maxdays" value="<?php echo $jsnf_maxdays?>" size="4" maxlength="4"> <?php echo $l_days?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxchars3?>:</td>
<td align="left"><input class="sninput" type="text" name="jsnf_maxchars" value="<?php echo $jsnf_maxchars?>" size="4" maxlength="10"> <?php echo $l_chars?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_js_newsscroller?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="jsns_headingsep" value="1" <?php if($jsns_sepheading==1) echo "checked"?>>
<?php echo $l_sepheading?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("jsns_bgcolor",$jsns_bgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_width?>:</td>
<td align="left"><input class="sninput" type="text" name="jsns_width" value="<?php echo $jsns_width?>" size="4" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_height?>:</td>
<td align="left"><input class="sninput" type="text" name="jsns_height" value="<?php echo $jsns_height?>" size="4" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="jsns_font" value="<?php echo do_htmlentities(stripslashes($jsns_font))?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("jsns_fontcolor",$jsns_fontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="jsns_fontsize" value="<?php echo $jsns_fontsize?>" size="4" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_scrolldir?>:</td>
<td><select name="jsns_direction">
<?php
for($i=0;$i<count($l_jsns_directions);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$jsns_direction)
		echo " selected";
	echo ">".$l_jsns_directions[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_speed?>:</td>
<td align="left"><input class="sninput" type="text" name="jsns_speed" value="<?php echo $jsns_speed?>" size="4" maxlength="10"> ms</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_stepsize?>:</td>
<td align="left"><input class="sninput" type="text" name="jsns_step" value="<?php echo $jsns_step?>" size="4" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="jsns_linkingoff" value="1" <?php if($jsns_nolinking) echo "checked"?>> <?php echo $l_jsns_nolinking?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linktarget?>:</td>
<td align="left"><input class="sninput" type="text" name="jsns_linktarget" value="<?php echo $jsns_linktarget?>" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="jsns_showdate" value="1" <?php if($jsns_displaydate) echo "checked"?>> <?php echo $l_displaydate?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat?>:</td>
<td align="left"><input class="sninput" type="text" name="jsns_dateformat" value="<?php echo $jsns_dateformat?>" size="20" maxlength="20"></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_showednews?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxentries?>:</td>
<td align="left"><input class="sninput" type="text" name="jsns_maxentries" value="<?php echo $jsns_maxentries?>" size="4" maxlength="4"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_timeframe?>:</td>
<td align="left"><input class="sninput" type="text" name="jsns_maxdays" value="<?php echo $jsns_maxdays?>" size="4" maxlength="4"> <?php echo $l_days?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxchars_full?>:</td>
<td align="left"><input class="sninput" type="text" name="jsns_maxchars" value="<?php echo $jsns_maxchars?>" size="4" maxlength="10"> <?php echo $l_chars?></td></tr>
