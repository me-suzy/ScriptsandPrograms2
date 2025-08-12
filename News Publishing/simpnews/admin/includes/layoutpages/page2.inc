<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_news?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_leftindent?>:</td>
<td><input type="text" class="sninput" name="nbindent" value="<?php echo $nbindent?>" size="6" maxlength="20"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="nonewsicons" value="1" <?php if($newsnoicons==1) echo "checked"?>> <?php echo $l_newsnoicons?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="futurenews" value="1" <?php if($showfuturenews==1) echo "checked"?>> <?php echo $l_showfuturenews?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_heading?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("newsheadingbgcolor",$newsheadingbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("newsheadingfontcolor",$newsheadingfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="newsheadingfont" value="<?php echo $newsheadingfont?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="newsheadingfontsize" value="<?php echo $newsheadingfontsize?>" size="4" maxlength="4"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_style?>:</td>
<td align="left"><select name="newsheadingstyle">
<?php
for($i=0;$i<count($l_styles);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$newsheadingstyle)
		echo " selected";
	echo ">".$l_styles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_poster?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="showposter" value="1" <?php if($displayposter==1) echo "checked"?>>
<?php echo $l_displayposter?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="posterlink" value="1" <?php if($linkposter==1) echo "checked"?>>
<?php echo $l_linkposter?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="proposershow" value="1" <?php if($showproposer==1) echo "checked"?>>
<?php echo $l_showproposer?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("posterbgcolor",$posterbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("posterfontcolor",$posterfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="posterfont" value="<?php echo $posterfont?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="posterfontsize" value="<?php echo $posterfontsize?>" size="4" maxlength="4"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_style?>:</td>
<td align="left"><select name="posterstyle">
<?php
for($i=0;$i<count($l_styles);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$posterstyle)
		echo " selected";
	echo ">".$l_styles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_text?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("contentbgcolor",$contentbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("contentfontcolor",$contentfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="contentfont" value="<?php echo $contentfont?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="contentfontsize" value="<?php echo $contentfontsize?>" size="4" maxlength="4"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_timestamp?></b></td>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="nnodate" value="1" <?php if(bittst($newsnodate,BIT_1)) echo "checked"?>>
<?php echo $l_dont_display_date?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("timestampbgcolor",$timestampbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("timestampfontcolor",$timestampfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="timestampfont" value="<?php echo $timestampfont?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="timestampfontsize" value="<?php echo $timestampfontsize?>" size="4" maxlength="4"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_style?>:</td>
<td align="left"><select name="timestampstyle">
<?php
for($i=0;$i<count($l_styles);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$timestampstyle)
		echo " selected";
	echo ">".$l_styles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat?>:</td>
<td align="left"><input class="sninput" type="text" name="dateformat" value="<?php echo $dateformat?>" size="20" maxlength="20"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_categorylayout?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("categorybgcolor",$categorybgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("categoryfontcolor",$categoryfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td><input class="sninput" type="text" size="40" maxlength="240" name="categoryfont" value="<?php echo $categoryfont?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="sninput" type="text" size="4" maxlength="4" name="categoryfontsize" value="<?php echo $categoryfontsize?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_style?>:</td>
<td align="left"><select name="categorystyle">
<?php
for($i=0;$i<count($l_styles);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$categorystyle)
		echo " selected";
	echo ">".$l_styles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_copyrightnotice?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("copyrightbgcolor",$copyrightbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("copyrightfontcolor",$copyrightfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="copyrightfont" value="<?php echo $copyrightfont?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="copyrightfontsize" value="<?php echo $copyrightfontsize?>" size="4" maxlength="4"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_position?>:</td><td align="left">
<input type="radio" name="copyrightpos" value="0" <?php if($copyrightpos==0) echo "checked"?>> <?php echo $l_beforefooter?><br>
<input type="radio" name="copyrightpos" value="1" <?php if($copyrightpos==1) echo "checked"?>> <?php echo $l_afterfooter?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_comments?> <span class="remark">(<?php echo $l_inlinedisplay?>)</span></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="inlinecomments" value="1" <?php if($commentsinline==1) echo "checked"?>> <?php echo $l_commentsinline?>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="icemail" value="1" <?php if($icdisplayemail==1) echo "checked"?>> <?php echo $l_icdisplayemail?></td></tr>
</td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_heading?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("ic_heading_bgcolor",$ic_heading_bgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("ic_heading_fontcolor",$ic_heading_fontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td><input class="sninput" type="text" size="40" maxlength="240" name="ic_heading_font" value="<?php echo $ic_heading_font?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="sninput" type="text" size="4" maxlength="4" name="ic_heading_fontsize" value="<?php echo $ic_heading_fontsize?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_style?>:</td>
<td align="left"><select name="ic_heading_style">
<?php
for($i=0;$i<count($l_styles);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$ic_heading_style)
		echo " selected";
	echo ">".$l_styles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_entry?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("ic_body_bgcolor",$ic_body_bgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("ic_body_fontcolor",$ic_body_fontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td><input class="sninput" type="text" size="40" maxlength="240" name="ic_body_font" value="<?php echo $ic_body_font?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="sninput" type="text" size="4" maxlength="4" name="ic_body_fontsize" value="<?php echo $ic_body_fontsize?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_style?>:</td>
<td align="left"><select name="ic_body_style">
<?php
for($i=0;$i<count($l_styles);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$ic_body_style)
		echo " selected";
	echo ">".$l_styles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_news5?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_startdate?>:</td><td>
<table width="50%" cellpadding="0" cellspacing="0" border="0" align="left">
<tr>
<td align="center" width="20%"><?php echo $l_day?></td>
<td align="center" width="20%"><?php echo $l_month?></td>
<td align="center" width="20%"><?php echo $l_year?></td>
</tr>
<tr>
<td align="center"><select name="news5startday">
<?php
for($i=1;$i<32;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$news5startday)
		echo " selected";
	echo ">$i</option>";
}
?>
</select></td>
<td align="center"><select name="news5startmonth">
<?php
for($i=1;$i<13;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$news5startmonth)
		echo " selected";
	echo ">".$l_monthname[$i-1]."</option>";
}
?>
</select></td>
<td align="center"><select name="news5startyear">
<?php
if($news5startyear<($actualyear-$yearrange))
	echo "<option value=\"$news5startyear\" selected>$news5startyear</option>";
for($i=$actualyear-$yearrange;$i<$actualyear+$yearrange+1;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$news5startyear)
		echo " selected";
	echo ">$i</option>";
}
?>
</select></td>
</tr>
</table></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_enddate?>:</td><td>
<table width="50%" cellpadding="0" cellspacing="0" border="0" align="left">
<tr>
<td align="center" width="20%"><?php echo $l_day?></td>
<td align="center" width="20%"><?php echo $l_month?></td>
<td align="center" width="20%"><?php echo $l_year?></td>
</tr>
<tr>
<td align="center"><select name="news5endday">
<?php
for($i=1;$i<32;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$news5endday)
		echo " selected";
	echo ">$i</option>";
}
?>
</select></td>
<td align="center"><select name="news5endmonth">
<?php
for($i=1;$i<13;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$news5endmonth)
		echo " selected";
	echo ">".$l_monthname[$i-1]."</option>";
}
?>
</select></td>
<td align="center"><select name="news5endyear">
<?php
if($news5endyear<$actualyear)
	echo "<option value=\"$news5endyear\" selected>$news5endyear</option>";
for($i=$actualyear;$i<$actualyear+6;$i++)
{
	echo "<option value=\"$i\"";
	if($i==$news5endyear)
		echo " selected";
	echo ">$i</option>";
}
?>
</select></td>
</tr>
</table></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_globalheadingdateformat?>:</td>
<td><input class="sninput" type="text" name="news5headingdateformat" value="<?php echo $news5headingdateformat?>" size="20" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linktarget?>:</td>
<td><input class="sninput" type="text" name="news5linktarget" value="<?php echo $news5linktarget?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat2?>:</td>
<td><input class="sninput" type="text" name="news5dateformat" value="<?php echo $news5dateformat?>" size="20" maxlength="20"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="disablen5globalprint" value="1" <?php if($news5noglobalprint==1) echo "checked"?>> <?php echo $l_noglobalprint?>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>evlist2.php</b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="news5poster" value="1" <?php if($news5displayposter==1) echo "checked"?>>
<?php echo $l_displayposter?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="news5icons" value="1" <?php if($news5displayicons==1) echo "checked"?>>
<?php echo $l_displayicons?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="events2onlyheadings" value="1" <?php if($ev2onlyheadings==1) echo "checked"?>> <?php echo $l_onlyheadings?></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_heading?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="ev2_newsheadingfontsize" value="<?php echo $ev2_newsheadingfontsize?>" size="4" maxlength="4"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_style?>:</td>
<td align="left"><select name="ev2_newsheadingstyle">
<?php
for($i=0;$i<count($l_styles);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$ev2_newsheadingstyle)
		echo " selected";
	echo ">".$l_styles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_timestamp?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="ev2_timestampfontsize" value="<?php echo $ev2_timestampfontsize?>" size="4" maxlength="4"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_style?>:</td>
<td align="left"><select name="ev2_timestampstyle">
<?php
for($i=0;$i<count($l_styles);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$ev2_timestampstyle)
		echo " selected";
	echo ">".$l_styles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_text?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="ev2_contentfontsize" value="<?php echo $ev2_contentfontsize?>" size="4" maxlength="4"></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_poster?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="ev2_posterfontsize" value="<?php echo $ev2_posterfontsize?>" size="4" maxlength="4"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_style?>:</td>
<td align="left"><select name="ev2_posterstyle">
<?php
for($i=0;$i<count($l_styles);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$ev2_posterstyle)
		echo " selected";
	echo ">".$l_styles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_csvexport?></b> <span class="remark">(Events)</span></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat2?>:</td>
<td><input class="sninput" type="text" name="csvexportdateformat" value="<?php echo $csvexportdateformat?>" size="20" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_fields2export?>:</td>
<td>
<input type="checkbox" name="csv_date" value="1" <?php if(bittst($csvexportfields,BIT_1)) echo "checked"?>> <?php echo $l_date?><br>
<input type="checkbox" name="csv_lang" value="1" <?php if(bittst($csvexportfields,BIT_2)) echo "checked"?>> <?php echo $l_language?><br>
<input type="checkbox" name="csv_category" value="1" <?php if(bittst($csvexportfields,BIT_3)) echo "checked"?>> <?php echo $l_category?><br>
<input type="checkbox" name="csv_heading" value="1" <?php if(bittst($csvexportfields,BIT_4)) echo "checked"?>> <?php echo $l_heading?><br>
<input type="checkbox" name="csv_entry" value="1" <?php if(bittst($csvexportfields,BIT_5)) echo "checked"?>> <?php echo $l_entrytext?><br>
<input type="checkbox" name="csv_poster" value="1" <?php if(bittst($csvexportfields,BIT_6)) echo "checked"?>> <?php echo $l_poster?><br>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>news5.php</b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxshortchars?>:</td>
<td><input class="sninput" type="text" name="news5maxchars" value="<?php echo $news5maxchars?>" size="3" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right">&nbsp;</td>
<td><input type="checkbox" name="news5ddlink" value="1" <?php if($news5useddlink==1) echo "checked"?>>&nbsp;<?php echo $l_useddlink?></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_heading?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="n5_newsheadingfontsize" value="<?php echo $n5_newsheadingfontsize?>" size="4" maxlength="4"></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_timestamp?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="n5_timestampfontsize" value="<?php echo $n5_timestampfontsize?>" size="4" maxlength="4"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_style?>:</td>
<td align="left"><select name="n5_timestampstyle">
<?php
for($i=0;$i<count($l_styles);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$n5_timestampstyle)
		echo " selected";
	echo ">".$l_styles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_yearheading?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("news5yearbgcolor",$news5yearbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("news5yearfontcolor",$news5yearfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="news5yearfont" value="<?php echo $news5yearfont?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="news5yearfontsize" value="<?php echo $news5yearfontsize?>" size="4" maxlength="4"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_style?>:</td>
<td align="left"><select name="news5yearfontstyle">
<?php
for($i=0;$i<count($l_styles);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$news5yearfontstyle)
		echo " selected";
	echo ">".$l_styles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_monthheading?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("news5monthbgcolor",$news5monthbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("news5monthfontcolor",$news5monthfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="news5monthfont" value="<?php echo $news5monthfont?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="news5monthfontsize" value="<?php echo $news5monthfontsize?>" size="4" maxlength="4"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_style?>:</td>
<td align="left"><select name="news5monthfontstyle">
<?php
for($i=0;$i<count($l_styles);$i++)
{
	echo "<option value=\"$i\"";
	if($i==$news5monthfontstyle)
		echo " selected";
	echo ">".$l_styles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="news5displayyear" value="1" <?php if($news5monthdisplayyear==1) echo "checked"?>>
<?php echo $l_displayyear?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_news2?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_entriespercategory?>:</td>
<td align="left"><input class="sninput" type="text" name="news2entries" value="<?php echo $news2entries?>" size="2" maxlength="2"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_targetframe?>:</td>
<td><input class="sninput" type="text" name="news2target" value="<?php echo $news2target?>" size="40" maxlength="240"></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_news3?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_entriespercategory?>:</td>
<td align="left"><input class="sninput" type="text" name="news3entries" value="<?php echo $news3entries?>" size="2" maxlength="2"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="sninput" type="text" name="news3fontsize" value="<?php echo $news3fontsize?>" size="20" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linktarget?>:</td>
<td><input class="sninput" type="text" name="news3linktarget" value="<?php echo $news3linktarget?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat2?>:</td>
<td><input class="sninput" type="text" name="news3dateformat" value="<?php echo $news3dateformat?>" size="20" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxshortchars?>:</td>
<td><input class="sninput" type="text" name="news3maxchars" value="<?php echo $news3maxchars?>" size="3" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right">&nbsp;</td>
<td><input type="checkbox" name="news3ddlink" value="1" <?php if($news3useddlink==1) echo "checked"?>>&nbsp;<?php echo $l_useddlink?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_news4?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="sninput" type="text" name="news4fontsize" value="<?php echo $news4fontsize?>" size="20" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linktarget?>:</td>
<td><input class="sninput" type="text" name="news4linktarget" value="<?php echo $news4linktarget?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_dateformat2?>:</td>
<td><input class="sninput" type="text" name="news4dateformat" value="<?php echo $news4dateformat?>" size="20" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxshortchars?>:</td>
<td><input class="sninput" type="text" name="news4maxchars" value="<?php echo $news4maxchars?>" size="3" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_margin?> (<?php echo $l_top?>/<?php echo $l_bottom?>):</td><td>
<input class="sninput" type="text" name="n4tbmargin" size="10" maxlength="10" value="<?php echo $n4tbmargin?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_margin?> <?php echo $l_left?>:</td><td>
<input class="sninput" type="text" name="n4leftmargin" size="10" maxlength="10" value="<?php echo $n4leftmargin?>"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td align="left">
<input type="checkbox" name="non4date" value="1" <?php if($n4nodate==1) echo "checked"?>> <?php echo $l_dontdisplaydate?></td></tr>
<tr class="inputrow"><td align="right">&nbsp;</td>
<td><input type="checkbox" name="news4ddlink" value="1" <?php if($news4useddlink==1) echo "checked"?>>&nbsp;<?php echo $l_useddlink?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="news4catshow" value="1" <?php if($news4showcat==1) echo "checked"?>> <?php echo $l_news4showcat?>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>news4b.php</b></td>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="n4addbr" value="1" <?php if(bittst($news4addoptions,BIT_1)) echo "checked"?>>&nbsp;<?php echo $l_headingaddbr?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b>singlenews.php</b></td>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="singlenodate" <?php if($snnodate==1) echo "checked"?>> <?php echo $l_dontdisplaydate?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="sn_hideallnews" <?php if($sn_hideallnewslink==1) echo "checked"?>> <?php echo $l_sn_hideallnewslink?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_catlinkdest?>:</td><td>
<select name="sncatlink">
<?php
$avail_catlinks=array("news.php","news4.php","news4b.php","news5.php","source script");
for($i=0;$i<count($avail_catlinks);$i++)
{
	echo "<option value=\"".$avail_catlinks[$i]."\"";
	if($sncatlink==$avail_catlinks[$i])
		echo " selected";
	echo ">".$avail_catlinks[$i]."</option>";
}
?>
</select>
</td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_ratings?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_infotext?>:</td>
<td align="left"><input type="text" class="sninput" name="ratingprelude" value="<?php echo do_htmlentities($ratingprelude)?>" size="40" maxlength="80"></td></tr>
