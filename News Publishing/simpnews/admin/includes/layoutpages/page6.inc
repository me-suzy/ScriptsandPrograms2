<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_attachements?></b></td></tr>
<tr class="inputrow"><td width="30%">&nbsp;</td><td><input type="checkbox" name="hidefileinfo" value="1" <?php if($nofileinfo==1) echo "checked"?>>
<?php echo $l_hidefileinfo?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_attachpos?>:</td><td>
<input type="radio" name="attachpos" value="0" <?php if($attachpos==0) echo "checked"?>> <?php echo $l_atendofentry?><br>
<input type="radio" name="attachpos" value="1" <?php if($attachpos==1) echo "checked"?>> <?php echo $l_beforeposterline?>
</td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_searching?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="nosrchlimit" value="1" <?php if($srchnolimit==1) echo "checked"?>> <?php echo $l_srchnolimit?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="srchonlyheadings" value="1" <?php if($searchonlyheadings==1) echo "checked"?>> <?php echo $l_onlyheadings?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="srchdispcats" value="1" <?php if(bittst($srchaddoptions,BIT_1)) echo "checked"?>> <?php echo $l_srchcatselection?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="srchselectparts" value="1" <?php if(bittst($srchaddoptions,BIT_2)) echo "checked"?>> <?php echo $l_srchpartselection?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxchars?></td><td>
<input type="text" name="searchmaxchars" value="<?php echo $searchmaxchars?>" class="sninput" size="4" maxlength="10"> <?php echo $l_chars?><br>
<span class="remark"><?php echo $l_willstriptags?></span></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_activcellcolor?>:<br><span class="remark">(<?php echo $l_onlyforsearchmaxchars?>)</span></td>
<?php echo color_chooser("activcellcolor",$activcellcolor)?>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_highlightsearchterms?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="highlightsearch" value="1" <?php if($searchhighlight==1) echo "checked"?>><?php echo $l_highlightsearchterms?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("searchhighlightcolor",$searchhighlightcolor)?>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_sshortmode?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_detailtarget?>:</td><td>
<input type="text" name="searchdetailtarget" value="<?php echo $searchdetailtarget?>" size="40" maxlenght="80" class="sninput"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_entryshortcut?>:</td><td>
<input type="text" name="searchshortchars" value="<?php echo $searchshortchars?>" size="4" maxlength="10" class="sninput"> <?php echo $l_chars?><br>
<span class="remark"><?php echo $l_searchshortremark?></span>
</td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_timestamp?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td><td>
<input type="text" size="10" maxlength="20" name="sshort_timestampfontsize" value="<?php echo $sshort_timestampfontsize?>" class="sninput">
</td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b><?php echo $l_heading?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td><td>
<input type="text" size="10" maxlength="20" name="sshort_headingfontsize" value="<?php echo $sshort_headingfontsize?>" class="sninput"></td></tr>
