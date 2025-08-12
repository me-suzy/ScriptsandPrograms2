<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_userstyles?></b></td>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_browserscrollbar?></b></td>
<tr class="inputrow"><td width="30%">&nbsp;</td><td><input type="checkbox" name="enablesbcolors" value="1" <?php if($colorscrollbars==1) echo "checked"?>> <?php echo $l_enable?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_sbfacecolor?>:</td>
<?php echo color_chooser("sbfacecolor",$sbfacecolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sbhighlightcolor?>:</td>
<?php echo color_chooser("sbhighlightcolor",$sbhighlightcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sbshadowcolor?>:</td>
<?php echo color_chooser("sbshadowcolor",$sbshadowcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sbdarkshadowcolor?>:</td>
<?php echo color_chooser("sbdarkshadowcolor",$sbdarkshadowcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sb3dlightcolor?>:</td>
<?php echo color_chooser("sb3dlightcolor",$sb3dlightcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sbarrowcolor?>:</td>
<?php echo color_chooser("sbarrowcolor",$sbarrowcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sbtrackcolor?>:</td>
<?php echo color_chooser("sbtrackcolor",$sbtrackcolor)?>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_selectboxes?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("snsel_bgcolor",$snsel_bgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("snsel_fontcolor",$snsel_fontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="snsel_font" value="<?php echo do_htmlentities(stripslashes($snsel_font))?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="snsel_fontsize" value="<?php echo $snsel_fontsize?>" size="10" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontstyle?>:</td>
<td align="left"><select class="snlayout" name="snsel_fontstyle">
<?php
for($i=0;$i<count($l_fstyles);$i++)
{
	echo "<option value=\"".$l_fontstyles[$i]."\"";
	if($l_fstyles[$i]==$snsel_fontstyle)
		echo " selected";
	echo ">".$l_fstyles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontweight?>:</td>
<td align="left"><select class="snlayout" name="snsel_fontweight">
<?php
for($i=0;$i<count($l_fontweights);$i++)
{
	echo "<option value=\"".$l_fontweights[$i]."\"";
	if($l_fontweights[$i]==$snsel_fontweight)
		echo " selected";
	echo ">".$l_fontweights[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_borderstyle?>:</td>
<td align="left"><select class="snlayout" name="snsel_borderstyle">
<?php
for($i=0;$i<count($l_borderstyles);$i++)
{
	echo "<option value=\"".$l_borderstyles[$i]."\"";
	if($l_borderstyles[$i]==$snsel_borderstyle)
		echo " selected";
	echo ">".$l_borderstyles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_borderwidth?>:</td>
<td align="left"><input class="sninput" type="text" name="snsel_borderwidth" value="<?php echo $snsel_borderwidth?>" size="10" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bordercolor?>:</td>
<?php echo color_chooser("snsel_bordercolor",$snsel_bordercolor)?>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_inputfields_textareas?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("sninput_bgcolor",$sninput_bgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("sninput_fontcolor",$sninput_fontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="sninput_font" value="<?php echo do_htmlentities(stripslashes($sninput_font))?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="sninput_fontsize" value="<?php echo $sninput_fontsize?>" size="10" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontstyle?>:</td>
<td align="left"><select class="snlayout" name="sninput_fontstyle">
<?php
for($i=0;$i<count($l_fstyles);$i++)
{
	echo "<option value=\"".$l_fstyles[$i]."\"";
	if($l_fstyles[$i]==$sninput_fontstyle)
		echo " selected";
	echo ">".$l_fstyles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontweight?>:</td>
<td align="left"><select class="snlayout" name="sninput_fontweight">
<?php
for($i=0;$i<count($l_fontweights);$i++)
{
	echo "<option value=\"".$l_fontweights[$i]."\"";
	if($l_fontweights[$i]==$sninput_fontweight)
		echo " selected";
	echo ">".$l_fontweights[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_borderstyle?>:</td>
<td align="left"><select class="snlayout" name="sninput_borderstyle">
<?php
for($i=0;$i<count($l_borderstyles);$i++)
{
	echo "<option value=\"".$l_borderstyles[$i]."\"";
	if($l_borderstyles[$i]==$sninput_borderstyle)
		echo " selected";
	echo ">".$l_borderstyles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_borderwidth?>:</td>
<td align="left"><input class="sninput" type="text" name="sninput_borderwidth" value="<?php echo $sninput_borderwidth?>" size="10" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bordercolor?>:</td>
<?php echo color_chooser("sninput_bordercolor",$sninput_bordercolor)?>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_scrollbar?> <span class="remark">(<?php echo $l_textareas?>)</span></b></td>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="textareanoscrbar" value="1" <?php if($textareanoscroll==1) echo "checked"?>>
<?php echo $l_dontuse?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_sbfacecolor?>:</td>
<?php echo color_chooser("snisb_facecolor",$snisb_facecolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sbhighlightcolor?>:</td>
<?php echo color_chooser("snisb_highlightcolor",$snisb_highlightcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sbshadowcolor?>:</td>
<?php echo color_chooser("snisb_shadowcolor",$snisb_shadowcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sbdarkshadowcolor?>:</td>
<?php echo color_chooser("snisb_darkshadowcolor",$snisb_darkshadowcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sb3dlightcolor?>:</td>
<?php echo color_chooser("snisb_3dlightcolor",$snisb_3dlightcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sbarrowcolor?>:</td>
<?php echo color_chooser("snisb_arrowcolor",$snisb_arrowcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_sbtrackcolor?>:</td>
<?php echo color_chooser("snisb_trackcolor",$snisb_trackcolor)?>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_buttons?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("snbutton_bgcolor",$snbutton_bgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("snbutton_fontcolor",$snbutton_fontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="snbutton_font" value="<?php echo do_htmlentities(stripslashes($snbutton_font))?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="snbutton_fontsize" value="<?php echo $snbutton_fontsize?>" size="10" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontstyle?>:</td>
<td align="left"><select class="snlayout" name="snbutton_fontstyle">
<?php
for($i=0;$i<count($l_fstyles);$i++)
{
	echo "<option value=\"".$l_fstyles[$i]."\"";
	if($l_fstyles[$i]==$snbutton_fontstyle)
		echo " selected";
	echo ">".$l_fstyles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontweight?>:</td>
<td align="left"><select class="snlayout" name="snbutton_fontweight">
<?php
for($i=0;$i<count($l_fontweights);$i++)
{
	echo "<option value=\"".$l_fontweights[$i]."\"";
	if($l_fontweights[$i]==$snbutton_fontweight)
		echo " selected";
	echo ">".$l_fontweights[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_borderstyle?>:</td>
<td align="left"><select class="snlayout" name="snbutton_borderstyle">
<?php
for($i=0;$i<count($l_borderstyles);$i++)
{
	echo "<option value=\"".$l_borderstyles[$i]."\"";
	if($l_borderstyles[$i]==$snbutton_borderstyle)
		echo " selected";
	echo ">".$l_borderstyles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_borderwidth?>:</td>
<td align="left"><input class="sninput" type="text" name="snbutton_borderwidth" value="<?php echo $snbutton_borderwidth?>" size="10" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bordercolor?>:</td>
<?php echo color_chooser("snbutton_bordercolor",$snbutton_bordercolor)?>
