<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_includescripts?></b></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>snews.php</b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_tablewidth?>:</td>
<td><input type="text" name="sns_tablewidth" class="sninput" value="<?php echo $sns_tablewidth?>" size="10" maxlength="10"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="sns_displaycat" value="1" <?php if(bittst($sns_options,BIT_6)) echo "checked"?>>
<?php echo $l_displaycat?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="sns_catlink" value="1" <?php if(bittst($sns_options,BIT_1)) echo "checked"?>>
<?php echo $l_linkcat?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="sns_displaydate" value="1" <?php if(bittst($sns_options,BIT_2)) echo "checked"?>>
<?php echo $l_displaydate?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="sns_displayheading" value="1" <?php if(bittst($sns_options,BIT_3)) echo "checked"?>>
<?php echo $l_displayheading?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="sns_displaypageheading" value="1" <?php if(bittst($sns_options,BIT_4)) echo "checked"?>>
<?php echo $l_displaypageheading?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="sns_displayposter" value="1" <?php if(bittst($sns_options,BIT_5)) echo "checked"?>>
<?php echo $l_displayposter?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="sns_linkposter" value="1" <?php if(bittst($sns_options,BIT_7)) echo "checked"?>>
<?php echo $l_linkposter?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="sns_printicon" value="1" <?php if(bittst($sns_options,BIT_8)) echo "checked"?>>
<?php echo $l_printicon?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="sns_gotopicon" value="1" <?php if(bittst($sns_options,BIT_9)) echo "checked"?>>
<?php echo $l_gotopicon?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="sns_emailnews" value="1" <?php if(bittst($sns_options,BIT_10)) echo "checked"?>>
<?php echo $l_newsmail?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="sns_rating" value="1" <?php if(bittst($sns_options,BIT_11)) echo "checked"?>>
<?php echo $l_enablerating?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="sns_displaynav" value="1" <?php if(bittst($sns_options,BIT_12)) echo "checked"?>>
<?php echo $l_displaynav?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="sns_displaycatinfo" value="1" <?php if(bittst($sns_options,BIT_13)) echo "checked"?>>
<?php echo $l_displaycatinfotext?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>an_inc.php</b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_tablewidth?>:</td>
<td><input type="text" name="aninc_tablewidth" class="sninput" value="<?php echo $aninc_tablewidth?>" size="10" maxlength="10"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="aninc_displaycat" value="1" <?php if(bittst($aninc_options,BIT_1)) echo "checked"?>>
<?php echo $l_displaycat?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="aninc_catlink" value="1" <?php if(bittst($aninc_options,BIT_2)) echo "checked"?>>
<?php echo $l_linkcat?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="aninc_displaydate" value="1" <?php if(bittst($aninc_options,BIT_3)) echo "checked"?>>
<?php echo $l_displaydate?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="aninc_displayheading" value="1" <?php if(bittst($aninc_options,BIT_4)) echo "checked"?>>
<?php echo $l_displayheading?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="aninc_displayposter" value="1" <?php if(bittst($aninc_options,BIT_5)) echo "checked"?>>
<?php echo $l_displayposter?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="aninc_linkposter" value="1" <?php if(bittst($aninc_options,BIT_6)) echo "checked"?>>
<?php echo $l_linkposter?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="aninc_printicon" value="1" <?php if(bittst($aninc_options,BIT_7)) echo "checked"?>>
<?php echo $l_printicon?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="aninc_gotopicon" value="1" <?php if(bittst($aninc_options,BIT_8)) echo "checked"?>>
<?php echo $l_gotopicon?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="aninc_emailnews" value="1" <?php if(bittst($aninc_options,BIT_9)) echo "checked"?>>
<?php echo $l_newsmail?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="aninc_displaycatinfo" value="1" <?php if(bittst($aninc_options,BIT_10)) echo "checked"?>>
<?php echo $l_displaycatinfotext?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>ev_inc.php</b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_tablewidth?>:</td>
<td><input type="text" name="evinc_tablewidth" class="sninput" value="<?php echo $evinc_tablewidth?>" size="10" maxlength="10"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="evinc_displaypageheading" value="1" <?php if(bittst($evinc_options,BIT_1)) echo "checked"?>>
<?php echo $l_displaypageheading?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="evinc_displaydate" value="1" <?php if(bittst($evinc_options,BIT_2)) echo "checked"?>>
<?php echo $l_displaydate?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="evinc_displayheading" value="1" <?php if(bittst($evinc_options,BIT_3)) echo "checked"?>>
<?php echo $l_displayheading?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="evinc_displayposter" value="1" <?php if(bittst($evinc_options,BIT_4)) echo "checked"?>>
<?php echo $l_displayposter?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="evinc_linkposter" value="1" <?php if(bittst($evinc_options,BIT_5)) echo "checked"?>>
<?php echo $l_linkposter?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="evinc_printicon" value="1" <?php if(bittst($evinc_options,BIT_6)) echo "checked"?>>
<?php echo $l_printicon?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="evinc_gotopicon" value="1" <?php if(bittst($evinc_options,BIT_7)) echo "checked"?>>
<?php echo $l_gotopicon?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="evinc_displaycat" value="1" <?php if(bittst($evinc_options,BIT_9)) echo "checked"?>>
<?php echo $l_displaycat?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="evinc_catlink" value="1" <?php if(bittst($evinc_options,BIT_10)) echo "checked"?>>
<?php echo $l_linkcat?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="evinc_displaycatinfo" value="1" <?php if(bittst($evinc_options,BIT_8)) echo "checked"?>>
<?php echo $l_displaycatinfotext?></td></tr>
