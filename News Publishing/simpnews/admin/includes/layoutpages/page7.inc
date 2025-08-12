<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_proped?></b></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_optionrow?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("or_bgcolor",$or_bgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("or_fontcolor",$or_fontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td><input class="sninput" type="text" size="40" maxlength="240" name="or_font" value="<?php echo do_htmlentities(stripslashes($or_font))?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="sninput" type="text" size="4" maxlength="20" name="or_fontsize" value="<?php echo $or_fontsize?>"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_bbcbox?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("bbc_bgcolor",$bbc_bgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_bordercolor?>:</td>
<?php echo color_chooser("bbc_bordercolor",$bbc_bordercolor)?>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_selectboxes?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("bbcsel_bgcolor",$bbcsel_bgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("bbcsel_fontcolor",$bbcsel_fontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_bordercolor?>:</td>
<?php echo color_chooser("bbcsel_bordercolor",$bbcsel_bordercolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td><input class="sninput" type="text" size="40" maxlength="240" name="bbcsel_font" value="<?php echo do_htmlentities(stripslashes($bbcsel_font))?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="sninput" type="text" size="4" maxlength="20" name="bbcsel_fontsize" value="<?php echo $bbcsel_fontsize?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_borderstyle?>:</td>
<td align="left"><select class="snlayout" name="bbcsel_borderstyle">
<?php
for($i=0;$i<count($l_borderstyles);$i++)
{
	echo "<option value=\"".$l_borderstyles[$i]."\"";
	if($l_borderstyles[$i]==$bbcsel_borderstyle)
		echo " selected";
	echo ">".$l_borderstyles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_borderwidth?>:</td>
<td align="left"><input class="sninput" type="text" name="bbcsel_borderwidth" value="<?php echo $bbcsel_borderwidth?>" size="10" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontstyle?>:</td>
<td align="left"><select class="snlayout" name="bbcsel_fontstyle">
<?php
for($i=0;$i<count($l_fstyles);$i++)
{
	echo "<option value=\"".$l_fontstyles[$i]."\"";
	if($l_fstyles[$i]==$bbcsel_fontstyle)
		echo " selected";
	echo ">".$l_fstyles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontweight?>:</td>
<td align="left"><select class="snlayout" name="bbcsel_fontweight">
<?php
for($i=0;$i<count($l_fontweights);$i++)
{
	echo "<option value=\"".$l_fontweights[$i]."\"";
	if($l_fontweights[$i]==$bbcsel_fontweight)
		echo " selected";
	echo ">".$l_fontweights[$i]."</option>";
}
?>
</select></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_infobox?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("bbchelp_bgcolor",$bbchelp_bgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("bbchelp_fontcolor",$bbchelp_fontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td><input class="sninput" type="text" size="40" maxlength="240" name="bbchelp_font" value="<?php echo do_htmlentities(stripslashes($bbchelp_font))?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="sninput" type="text" size="4" maxlength="20" name="bbchelp_fontsize" value="<?php echo $bbchelp_fontsize?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontweight?>:</td>
<td align="left"><select class="snlayout" name="bbchelp_fontweight">
<?php
for($i=0;$i<count($l_fontweights);$i++)
{
	echo "<option value=\"".$l_fontweights[$i]."\"";
	if($l_fontweights[$i]==$bbchelp_fontweight)
		echo " selected";
	echo ">".$l_fontweights[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontstyle?>:</td>
<td align="left"><select class="snlayout" name="bbchelp_fontstyle">
<?php
for($i=0;$i<count($l_fstyles);$i++)
{
	echo "<option value=\"".$l_fontstyles[$i]."\"";
	if($l_fstyles[$i]==$bbchelp_fontstyle)
		echo " selected";
	echo ">".$l_fstyles[$i]."</option>";
}
?>
</select></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_smiliebox?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("sb_bgcolor",$sb_bgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_bordercolor?>:</td>
<?php echo color_chooser("sb_bordercolor",$sb_bordercolor)?>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_smilielink?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_hovercolor?>:</td>
<?php echo color_chooser("el_hovercolor",$el_hovercolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td><input class="sninput" type="text" size="40" maxlength="240" name="el_font" value="<?php echo do_htmlentities(stripslashes($el_font))?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td><input class="sninput" type="text" size="4" maxlength="20" name="el_fontsize" value="<?php echo $el_fontsize?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontweight?>:</td>
<td align="left"><select class="snlayout" name="el_fontweight">
<?php
for($i=0;$i<count($l_fontweights);$i++)
{
	echo "<option value=\"".$l_fontweights[$i]."\"";
	if($l_fontweights[$i]==$el_fontweight)
		echo " selected";
	echo ">".$l_fontweights[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontstyle?>:</td>
<td align="left"><select class="snlayout" name="el_fontstyle">
<?php
for($i=0;$i<count($l_fstyles);$i++)
{
	echo "<option value=\"".$l_fontstyles[$i]."\"";
	if($l_fstyles[$i]==$el_fontstyle)
		echo " selected";
	echo ">".$l_fstyles[$i]."</option>";
}
?>
</select></td></tr>
