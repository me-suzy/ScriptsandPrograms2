<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_applets?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_sepchar?>:</td>
<td><input class="sninput" type="text" name="sep_char" value="<?php echo $sep_char?>" size="1" maxlength="1"> <?php echo $l_sepcharrem?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_ganmark?>:</td>
<td><input class="sninput" type="text" name="applet_ganmark" value="<?php echo $applet_ganmark?>" size="20" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_anmark?>:</td>
<td><input class="sninput" type="text" name="applet_anmark" value="<?php echo $applet_anmark?>" size="20" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_entrieslinkto?>:</td><td>
<input type="radio" name="useappletlinkdest" value="0" <?php if($useappletlinkdest==0) echo "checked"?>>singlenews/announce/events<br>
<input type="radio" name="useappletlinkdest" value="1" <?php if($useappletlinkdest==1) echo "checked"?>><?php echo $l_snincscript?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_snincscript?>:<br><span class="remark">(<?php echo $l_url2script2?>)</span></td><td valign="top">
<table width="100%" align="left" border="0" cellspacing="0" cellpadding="0">
<tr><td align="right" width="20%">snews.php:</td><td align="left"><input type="text" class="sninput" size="40" maxlength="240" name="appletlinkdest" value="<?php echo $appletlinkdest?>"></td></tr>
<tr><td align="right" width="20%">an_inc.php:</td><td align="left"><input type="text" class="sninput" size="40" maxlength="240" name="appletlinkdestan" value="<?php echo $appletlinkdestan?>"></td></tr>
<tr><td align="right" width="20%">ev_inc.php:</td><td align="left"><input type="text" class="sninput" size="40" maxlength="240" name="appletlinkdestev" value="<?php echo $appletlinkdestev?>"></td></tr>
</table>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_newsticker?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:<br>(<?php echo $l_usehexvalue?>)</td>
<?php echo color_chooser("newstickerbgcolor",$newstickerbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:<br>(<?php echo $l_usehexvalue?>)</td>
<?php echo color_chooser("newstickerfontcolor",$newstickerfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_highlightcolor?>:<br>(<?php echo $l_usehexvalue?>)</td>
<?php echo color_chooser("newstickerhighlightcolor",$newstickerhighlightcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="newstickerfont" value="<?php echo do_htmlentities(stripslashes($newstickerfont))?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="newstickerfontsize" value="<?php echo $newstickerfontsize?>" size="4" maxlength="4"> <?php echo $l_points?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_width?>:</td>
<td align="left"><input class="sninput" type="text" name="newstickerwidth" value="<?php echo $newstickerwidth?>" size="4" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_height?>:</td>
<td align="left"><input class="sninput" type="text" name="newstickerheight" value="<?php echo $newstickerheight?>" size="4" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_scrollspeed?>:</td>
<td align="left">
<select name="newstickerscrollspeed">
<?php
for($i=1;$i<11;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$newstickerscrollspeed)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_scrolldelay?>:</td>
<td align="left"><input class="sninput" type="text" name="newstickerscrolldelay" value="<?php echo $newstickerscrolldelay?>" size="4" maxlength="10"> ms
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linktarget?>:</td>
<td align="left"><input class="sninput" type="text" name="newstickertarget" value="<?php echo $newstickertarget?>" size="40" maxlength="80">
</td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_showednews?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_timeframe?>:</td>
<td align="left"><input class="sninput" type="text" name="newstickermaxdays" value="<?php echo $newstickermaxdays?>" size="4" maxlength="10"> <?php echo $l_days?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxentries?>:</td>
<td align="left"><input class="sninput" type="text" name="newstickermaxentries" value="<?php echo $newstickermaxentries?>" size="4" maxlength="10">
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_newsscroller?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="nsheadingsep" value="1" <?php if($newsscrollerheadingsep==1) echo "checked"?>>
<?php echo $l_sepheading?></td>
<tr class="inputrow"><td align="right"><?php echo $l_sepheadingchar?>:</td><td>
<input type="text" name="newsscrollerheadingsepchar" value="<?php echo $newsscrollerheadingsepchar?>" class="sninput" size="1" maxlength="1"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_seplength?>:</td><td>
<input type="text" name="newsscrollernumsepchars" value="<?php echo $newsscrollernumsepchars?>" class="sninput" size="4" maxlength="10"> <?php echo $l_chars?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:<br>(<?php echo $l_usehexvalue?>)</td>
<?php echo color_chooser("newsscrollerbgcolor",$newsscrollerbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:<br>(<?php echo $l_usehexvalue?>)</td>
<?php echo color_chooser("newsscrollerfontcolor",$newsscrollerfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="newsscrollerfont" value="<?php echo do_htmlentities(stripslashes($newsscrollerfont))?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="newsscrollerfontsize" value="<?php echo $newsscrollerfontsize?>" size="4" maxlength="4"> <?php echo $l_points?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_width?>:</td>
<td align="left"><input class="sninput" type="text" name="newsscrollerwidth" value="<?php echo $newsscrollerwidth?>" size="4" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_height?>:</td>
<td align="left"><input class="sninput" type="text" name="newsscrollerheight" value="<?php echo $newsscrollerheight?>" size="4" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgimage?>:</td>
<?php echo gfx_selector("newsscrollerbgimage",$newsscrollerbgimage,2)?>
<tr class="inputrow"><td align="right"><?php echo $l_fgimage?>:</td>
<?php echo gfx_selector("newsscrollerfgimage",$newsscrollerfgimage,2)?>
<tr class="inputrow"><td align="right"><?php echo $l_scrollspeed?>:</td>
<td align="left">
<select name="newsscrollerscrollspeed">
<?php
for($i=1;$i<11;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$newsscrollerscrollspeed)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_scrolldelay?>:</td>
<td align="left"><input class="sninput" type="text" name="newsscrollerscrolldelay" value="<?php echo $newsscrollerscrolldelay?>" size="4" maxlength="10"> ms
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_scrollpause?>:</td>
<td align="left"><input class="sninput" type="text" name="newsscrollerscrollpause" value="<?php echo $newsscrollerscrollpause?>" size="4" maxlength="10"> ms
</td></tr>
<tr class="inputrow"><td align="right">&nbsp;</td><td>
<input type="checkbox" value="1" name="scrollerstoponmouse" <?php if($newsscrollermousestop==1) echo "checked"?>><?php echo $l_stoponmouseover?></td></tr>
<tr class="inputrow"><td align="right">&nbsp;</td><td>
<input type="checkbox" value="1" name="scrollerdate" <?php if($newsscrollerdisplaydate==1) echo "checked"?>><?php echo $l_displaydate?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat?>:</td>
<td align="left"><input class="sninput" type="text" name="newsscrollerdateformat" value="<?php echo $newsscrollerdateformat?>" size="20" maxlength="20">
</td></tr>
<tr class="inputrow"><td align="right">&nbsp;</td><td>
<input type="checkbox" value="1" name="scrollerwordwrap" <?php if($newsscrollerwordwrap==1) echo "checked"?>><?php echo $l_wordwrap?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_scrollertype?>:</td>
<td align="left">
<select name="newsscrollertype">
<?php
for($i=0;$i<count($l_scrollertypes);$i++)
{
	echo "<option value=\"".($i+1)."\"";
	if($i+1==$newsscrollertype)
		echo " selected";
	echo ">".$l_scrollertypes[$i]."</option>";
}
?>
</select>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linktarget?>:</td>
<td align="left"><input class="sninput" type="text" name="newsscrollertarget" value="<?php echo $newsscrollertarget?>" size="40" maxlength="80">
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_xoffset?>:</td>
<td align="left"><input class="sninput" type="text" name="newsscrollerxoffset" value="<?php echo $newsscrollerxoffset?>" size="4" maxlength="4"> <?php echo $l_pixel?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_yoffset?>:</td>
<td align="left"><input class="sninput" type="text" name="newsscrolleryoffset" value="<?php echo $newsscrolleryoffset?>" size="4" maxlength="4"> <?php echo $l_pixel?>
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="disablescrollerlink" value="1" <?php if($newsscrollernolinking==1) echo "checked"?>>
<?php echo $l_newsscrollernolinking?></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_showednews?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_timeframe?>:</td>
<td align="left"><input class="sninput" type="text" name="newsscrollermaxdays" value="<?php echo $newsscrollermaxdays?>" size="4" maxlength="10"> <?php echo $l_days?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxentries?>:</td>
<td align="left"><input class="sninput" type="text" name="newsscrollermaxentries" value="<?php echo $newsscrollermaxentries?>" size="4" maxlength="10">
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxchars?>:</td>
<td align="left"><input class="sninput" type="text" name="newsscrollermaxchars" value="<?php echo $newsscrollermaxchars?>" size="4" maxlength="10">
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxlines?>:</td>
<td align="left"><input class="sninput" type="text" name="newsscrollermaxlines" value="<?php echo $newsscrollermaxlines?>" size="4" maxlength="4">
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_newstyper?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:<br>(<?php echo $l_usehexvalue?>)</td>
<?php echo color_chooser("newstyperbgcolor",$newstyperbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:<br>(<?php echo $l_usehexvalue?>)</td>
<?php echo color_chooser("newstyperfontcolor",$newstyperfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyperfont" value="<?php echo do_htmlentities(stripslashes($newstyperfont))?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyperfontsize" value="<?php echo $newstyperfontsize?>" size="4" maxlength="4"> <?php echo $l_points?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontstyle?>:</td>
<td align="left">
<select name="newstyperfontstyle">
<?php
for($i=0;$i<count($l_fontstyles);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$newstyperfontstyle)
		echo " selected";
	echo ">".$l_fontstyles[$i]."</option>";
}
?>
</select>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_width?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyperwidth" value="<?php echo $newstyperwidth?>" size="4" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_height?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyperheight" value="<?php echo $newstyperheight?>" size="4" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgimage?>:</td>
<?php echo gfx_selector("newstyperbgimage",$newstyperbgimage,2)?>
<tr class="inputrow"><td align="right">&nbsp;</td><td>
<input type="checkbox" value="1" name="typerscroll" <?php if($newstyperscroll==1) echo "checked"?>><?php echo $l_newstyperscroll?></td></tr>
<tr class="inputrow"><td align="right">&nbsp;</td><td>
<input type="checkbox" value="1" name="typerdate" <?php if($newstyperdisplaydate==1) echo "checked"?>><?php echo $l_displaydate?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyperdateformat" value="<?php echo $newstyperdateformat?>" size="20" maxlength="20">
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_xoffset?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyperxoffset" value="<?php echo $newstyperxoffset?>" size="4" maxlength="4"> <?php echo $l_pixel?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_yoffset?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyperyoffset" value="<?php echo $newstyperyoffset?>" size="4" maxlength="4"> <?php echo $l_pixel?>
</td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_showednews?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_timeframe?>:</td>
<td align="left"><input class="sninput" type="text" name="newstypermaxdays" value="<?php echo $newstypermaxdays?>" size="4" maxlength="10"> <?php echo $l_days?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxentries?>:</td>
<td align="left"><input class="sninput" type="text" name="newstypermaxentries" value="<?php echo $newstypermaxentries?>" size="4" maxlength="10">
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxchars?>:</td>
<td align="left"><input class="sninput" type="text" name="newstypermaxchars" value="<?php echo $newstypermaxchars?>" size="4" maxlength="10">
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_newstyper2?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:<br>(<?php echo $l_usehexvalue?>)</td>
<?php echo color_chooser("newstyper2bgcolor",$newstyper2bgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:<br>(<?php echo $l_usehexvalue?>)</td>
<?php echo color_chooser("newstyper2fontcolor",$newstyper2fontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyper2fontsize" value="<?php echo $newstyper2fontsize?>" size="4" maxlength="4"> <?php echo $l_points?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linespace?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyper2linespace" value="<?php echo $newstyper2linespace?>" size="4" maxlength="4"> <?php echo $l_points?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_indent?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyper2indent" value="<?php echo $newstyper2indent?>" size="4" maxlength="4"> <?php echo $l_points?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_width?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyper2width" value="<?php echo $newstyper2width?>" size="4" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_height?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyper2height" value="<?php echo $newstyper2height?>" size="4" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgimage?>:</td>
<?php echo gfx_selector("newstyper2bgimage",$newstyper2bgimage,2)?>
<tr class="inputrow"><td align="right"><?php echo $l_sound?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyper2sound" value="<?php echo $newstyper2sound?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_charpause?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyper2charpause" value="<?php echo $newstyper2charpause?>" size="10" maxlength="10"> ms</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linepause?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyper2linepause" value="<?php echo $newstyper2linepause?>" size="10" maxlength="10"> ms</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_screenpause?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyper2screenpause" value="<?php echo $newstyper2screenpause?>" size="10" maxlength="10"> ms</td></tr>
<tr class="inputrow"><td align="right">&nbsp;</td><td>
<input type="checkbox" value="1" name="typer2newscreen" <?php if($newstyper2newscreen==1) echo "checked"?>><?php echo $l_newstyper2newscreen?></td></tr>
<tr class="inputrow"><td align="right">&nbsp;</td><td>
<input type="checkbox" value="1" name="typer2waitentry" <?php if($newstyper2waitentry==1) echo "checked"?>><?php echo $l_newstyper2waitentry?></td></tr>
<tr class="inputrow"><td align="right">&nbsp;</td><td>
<input type="checkbox" value="1" name="typer2date" <?php if($newstyper2displaydate==1) echo "checked"?>><?php echo $l_displaydate?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyper2dateformat" value="<?php echo $newstyper2dateformat?>" size="20" maxlength="20">
</td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_showednews?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_timeframe?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyper2maxdays" value="<?php echo $newstyper2maxdays?>" size="4" maxlength="10"> <?php echo $l_days?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxentries?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyper2maxentries" value="<?php echo $newstyper2maxentries?>" size="4" maxlength="10">
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxchars?>:</td>
<td align="left"><input class="sninput" type="text" name="newstyper2maxchars" value="<?php echo $newstyper2maxchars?>" size="4" maxlength="10">
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_eventscroller?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="eventscrollerdate" value="1" <?php if($eventscrolleractdate==1) echo "checked"?>>
<?php echo $l_eventscrolleractdate?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="evscrollcal2" value="1" <?php if($evscrollevcal2==1) echo "checked"?>>
<?php echo $l_evscrollcal2?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_evscrollcal2dest?>:<br><span class="remark">(<?php echo $l_url2script2?>)</span></td><td>
<input type="text" class="sninput" name="evscrollcal2dest" value="<?php echo $evscrollcal2dest?>" size="40" maxlength="80">
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_starscroller?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_width?>:</td>
<td align="left"><input class="sninput" type="text" name="ss_width" value="<?php echo $ss_width?>" size="4" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_height?>:</td>
<td align="left"><input class="sninput" type="text" name="ss_height" value="<?php echo $ss_height?>" size="4" maxlength="10"> <?php echo $l_pixel?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="ss_font" value="<?php echo do_htmlentities(stripslashes($ss_font))?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("ss_fontcolor",$ss_fontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="ss_fontsize" value="<?php echo $ss_fontsize?>" size="4" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontstyle?>:</td>
<td><select class="snlayout" name="ss_fontstyle">
<?php
for($i=0;$i<count($l_ss_fontstyles);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$ss_fontstyle)
		echo " selected";
	echo ">".$l_ss_fontstyles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontshadow?>:</td>
<td><select class="snlayout" name="ss_shadow">
<?php
for($i=0;$i<count($l_ss_shadow);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$ss_shadow)
		echo " selected";
	echo ">".$l_ss_shadow[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("ss_bgcolor",$ss_bgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_stars?>:</td>
<td><select name="ss_stars">
<?php
for($i=0;$i<count($l_ss_stars);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$ss_stars)
		echo " selected";
	echo ">".$l_ss_stars[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_speed?>:</td>
<td><select class="snlayout" name="ss_speed">
<?php
for($i=1;$i<6;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$ss_speed)
		echo " selected";
	echo ">".$i."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_scrolldir?>:</td>
<td><select name="ss_dir">
<?php
for($i=0;$i<count($l_ss_dirs);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$ss_dir)
		echo " selected";
	echo ">".$l_ss_dirs[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linktarget?>:</td>
<td align="left"><input class="sninput" type="text" name="ss_targetframe" value="<?php echo $ss_targetframe?>" size="40" maxlength="80">
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="disablestarlink" value="1" <?php if($ss_nolinking==1) echo "checked"?>>
<?php echo $l_newsscrollernolinking?></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_showednews?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_timeframe?>:</td>
<td align="left"><input class="sninput" type="text" name="ss_maxdays" value="<?php echo $ss_maxdays?>" size="4" maxlength="10"> <?php echo $l_days?>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxentries?>:</td>
<td align="left"><input class="sninput" type="text" name="ss_maxentries" value="<?php echo $ss_maxentries?>" size="4" maxlength="10">
</td></tr>
