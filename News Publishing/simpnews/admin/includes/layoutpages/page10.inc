<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo "$l_layout - $l_general"?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_entriesperpage?>:</td>
<td align="left"><input class="sninput" type="text" name="entriesperpage" value="<?php echo $entriesperpage?>" size="2" maxlength="2"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_tablealign?>:</td>
<td><select class="snlayout" name="tablealign">
<?php
for($i=0;$i<count($l_table_aligns);$i++)
{
	echo "<option value=\"$i\"";
	if($tablealign==$i)
		echo " selected";
	echo ">".$l_table_aligns[$i]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_tableborder?>:</td>
<?php echo color_chooser("bordercolor",$bordercolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_tablebgcolor?>:</td>
<?php echo color_chooser("tablebgcolor",$tablebgcolor)?>
<tr class="inputrow"><td>&nbsp;</td>
<td><input type="checkbox" name="sepbylang" value="1" <?php if($separatebylang==1) echo "checked"?>>
<?php echo $l_separatebylang?></td></tr>
<tr class="inputrow"><td>&nbsp;</td>
<td><input type="checkbox" name="enablecurrtime" value="1" <?php if($showcurrtime==1) echo "checked"?>>
<?php echo $l_showcurrtime?></td></tr>
<tr class="inputrow"><td>&nbsp;</td>
<td><input type="checkbox" name="disableprinticon" value="1" <?php if($noprinticon==1) echo "checked"?>>
<?php echo $l_noprinticon?></td></tr>
<tr class="inputrow"><td>&nbsp;</td>
<td><input type="checkbox" name="disablegotopicon" value="1" <?php if($nogotopicon==1) echo "checked"?>>
<?php echo $l_nogotopicon?></td></tr>
<tr class="inputrow"><td>&nbsp;</td>
<td><input type="checkbox" name="enablepagenavdetails" value="1" <?php if($pagenavdetails==1) echo "checked"?>>
<?php echo $l_pagenavdetails?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_tablewidth?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_direct_scripts?>:</td>
<td align="left"><input class="sninput" type="text" name="TableWidth" value="<?php echo $TableWidth?>" size="10" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_include_scripts?>:</td>
<td align="left"><input class="sninput" type="text" name="TableWidth2" value="<?php echo $TableWidth2?>" size="10" maxlength="10"></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_pagelayout?></b></td>
<tr class="inputrow"><td align="right" valign="top" width="30%"><?php echo $l_customheader?>:<br>
<input name="enablecustomheader" value="1" type="checkbox"
<?php if($usecustomheader==1) echo " checked"?>> <?php echo $l_enable?></td>
<td align="left"><textarea class="sninput" name="customheader" cols="40" rows="8"><?php echo do_htmlentities($customheader)?></textarea>
<?php
if($upload_avail)
{
?>
<hr noshade color="#000000" size="1" width="90%" align="center">
<?php echo $l_uploadfromfile?>: <input class="sninput" type="file" name="customheaderfile">
<?php
}
?>
<hr noshade color="#000000" size="1" width="90%" align="center">
<input type="checkbox" name="chnobrtrans" value="1" <?php if(bittst($nonltrans,BIT_1)) echo "checked"?>> <?php echo $l_nobrtranslation?>
<hr noshade color="#000000" size="1">
<input type="checkbox" value="1" name="clearcustomheader"> <?php echo $l_clear?>
<hr noshade color="#000000" size="1">
<?php echo $l_includefile?>: <input class="sninput" type="text" size="30" maxlength="240" name="headerfile" value="<?php echo $headerfile?>"><br>
<input type="radio" name="headerfilepos" value="0" <?php if ($headerfilepos==0) echo "checked"?>> <?php echo $l_beforecustomheader?><br>
<input type="radio" name="headerfilepos" value="1" <?php if ($headerfilepos==1) echo "checked"?>> <?php echo $l_aftercustomheader?>
<hr noshade color="#000000" size="1" width="90%" align="center">
<input type="checkbox" name="nocheadbr" value="1" <?php if($cheadnobr==1) echo " checked"?>> <?php echo $l_chnobr?>
</td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_customfooter?>:<br>
<input name="enablecustomfooter" value="1" type="checkbox"
<?php if($usecustomfooter==1) echo " checked"?>> <?php echo $l_enable?></td>
<td align="left"><textarea class="sninput" name="customfooter" rows="8" cols="40"><?php echo do_htmlentities($customfooter)?></textarea>
<?php
if($upload_avail)
{
?>
<hr noshade color="#000000" size="1" width="90%" align="center">
<?php echo $l_uploadfromfile?>: <input class="sninput" type="file" name="customfooterfile">
<?php
}
?>
<hr noshade color="#000000" size="1" width="90%" align="center">
<input type="checkbox" name="cfnobrtrans" value="1" <?php if(bittst($nonltrans,BIT_2)) echo "checked"?>> <?php echo $l_nobrtranslation?>
<hr noshade color="#000000" size="1" width="90%" align="center">
<input type="checkbox" value="1" name="clearcustomfooter"><?php echo $l_clear?>
<hr noshade color="#000000" size="1">
<?php echo $l_includefile?>: <input class="sninput" type="text" size="30" maxlength="240" name="footerfile" value="<?php echo $footerfile?>"><br>
<input type="radio" name="footerfilepos" value="0" <?php if ($footerfilepos==0) echo "checked"?>> <?php echo $l_beforecustomfooter?><br>
<input type="radio" name="footerfilepos" value="1" <?php if ($footerfilepos==1) echo "checked"?>> <?php echo $l_aftercustomfooter?>
<hr noshade color="#000000" size="1" width="90%" align="center">
<input type="checkbox" name="nocfootbr" value="1" <?php if($cfootnobr==1) echo " checked"?>> <?php echo $l_cfnobr?>
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="headerprint" value="1" <?php if($printheader==1) echo "checked"?>> <?php echo $l_printheader?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_contentcopy?>:</td>
<td><input class="sninput" type="text" name="contentcopy" value="<?php echo do_htmlentities(stripslashes($contentcopy))?>" size="40" maxlength="250"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_addbodytags?>:</td>
<td><input class="sninput" type="text" name="addbodytags" value="<?php echo $addbodytags?>" size="40" maxlength="250"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("pagebgcolor",$pagebgcolor)?>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_stylesheets?></b></td>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_general?>:</td>
<td><input class="sninput" type="text" name="stylesheet" value="<?php echo $stylesheet?>" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_ns4?>:</td>
<td><input class="sninput" type="text" name="ns4style" value="<?php echo $ns4style?>" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_ns6?>:</td>
<td><input class="sninput" type="text" name="ns6style" value="<?php echo $ns6style?>" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_opera?>:</td>
<td><input class="sninput" type="text" name="operastyle" value="<?php echo $operastyle?>" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_gecko?>:</td>
<td><input class="sninput" type="text" name="geckostyle" value="<?php echo $geckostyle?>" size="40" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_konqueror?>:</td>
<td><input class="sninput" type="text" name="konquerorstyle" value="<?php echo $konquerorstyle?>" size="40" maxlength="80"></td></tr>
