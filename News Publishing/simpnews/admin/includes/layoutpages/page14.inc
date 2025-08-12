<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_hotscripts?></b></td></tr>
<tr class="inputrow"><td width="30%">&nbsp;</td><td><input type="checkbox" name="hotnoheading" value="1" <?php if($hotscriptsnoheading==1) echo "checked"?>>
<?php echo $l_suppressheading?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_headingfontsize?>:</td><td>
<input type="text" class="sninput" size="5" maxlength="20" name="hn_newsheadingfontsize" value="<?php echo $hn_newsheadingfontsize?>">
</td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="hncommentslink" value="1" <?php if($hotnewscommentslink==1) echo "checked"?>>
<?php echo $l_showcommentslink?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_entrieslinkto?>:</td><td>
<input type="radio" name="usehnlinkdest" value="0" <?php if($usehnlinkdest==0) echo "checked"?>>singlenews/announce/events<br>
<input type="radio" name="usehnlinkdest" value="1" <?php if($usehnlinkdest==1) echo "checked"?>><?php echo $l_snincscript?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_snincscript?>:<br><span class="remark">(<?php echo $l_url2script?>)</span></td><td valign="top">
<table width="100%" align="left" border="0" cellspacing="0" cellpadding="0">
<tr><td align="right" width="20%">snews.php:</td><td align="left"><input type="text" class="sninput" size="40" maxlength="240" name="hnlinkdest" value="<?php echo $hnlinkdest?>"></td></tr>
<tr><td align="right" width="20%">an_inc.php:</td><td align="left"><input type="text" class="sninput" size="40" maxlength="240" name="hnlinkdestan" value="<?php echo $hnlinkdestan?>"></td></tr>
<tr><td align="right" width="20%">ev_inc.php:</td><td align="left"><input type="text" class="sninput" size="40" maxlength="240" name="hnlinkdestev" value="<?php echo $hnlinkdestev?>"></td></tr>
</table>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linklayout?>:</td><td><select name="hn_linklayout">
<option value="" <?php if(!$hn_linklayout) echo "selected"?>><?php echo $l_ascaller?></option>
<?php
$tmpsql="select * from ".$tableprefix."_layout where lang='$layoutlang'";
if(!$tmpresult = mysql_query($tmpsql, $db))
    die("<tr class=\"errorrow\"><td>Unable to query the database.".mysql_error());
while($tmprow=mysql_fetch_array($tmpresult))
{
	echo "<option value=\"".$tmprow["id"]."\"";
	if($hn_linklayout==$tmprow["id"])
		echo " selected";
	echo ">".$tmprow["id"]."</option>";
}
?>
</select></td></tr>
<tr class="inputrow"><td align="right">&nbsp;</td><td align="left"><input type="checkbox" name="nohnlinking" value="1" <?php if($hnnolinking==1) echo "checked"?>> <?php echo $l_hnnolinking?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_hotevents?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_numdays?>:</td>
<td><input type="text" class="sninput" name="hotevmaxdays" value="<?php echo $hotevmaxdays?>" size="4" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxentries?>:</td>
<td><input type="text" class="sninput" name="hotevmaxentries" value="<?php echo $hotevmaxentries?>" size="4" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linktarget?>:</td>
<td><input class="sninput" type="text" name="hotevtarget" value="<?php echo $hotevtarget?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo "$l_maxchars_full"?>:</td>
<td align="left"><input class="sninput" type="text" name="hotevmaxchars" value="<?php echo $hotevmaxchars?>" size="2" maxlength="4"> <?php echo $l_chars?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="hotevposter" value="1" <?php if($hotevdisplayposter==1) echo "checked"?>>
<?php echo $l_displayposter?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="hotevnohtml" value="1" <?php if($hotevnohtmlformatting==1) echo "checked"?>>
<?php echo $l_removehtmlformatting?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="hotevdisplayicons" value="1" <?php if($hotevicons==1) echo "checked"?>>
<?php echo $l_displayicons?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_hotnews?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_numhotnews?>:</td>
<td align="left"><input class="sninput" type="text" name="numhotnews" value="<?php echo $numhotnews?>" size="2" maxlength="2"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_linktarget?>:</td>
<td><input class="sninput" type="text" name="hotnewstarget" value="<?php echo $hotnewstarget?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="hotnewsposter" value="1" <?php if($hotnewsdisplayposter==1) echo "checked"?>>
<?php echo $l_displayposter?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="hotnewsnohtml" value="1" <?php if($hotnewsnohtmlformatting==1) echo "checked"?>>
<?php echo $l_removehtmlformatting?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td><input type="checkbox" name="hotnewsdisplayicons" value="1" <?php if($hotnewsicons==1) echo "checked"?>>
<?php echo $l_displayicons?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxchars_full?>:</td>
<td align="left"><input class="sninput" type="text" name="hotnewsmaxchars" value="<?php echo $hotnewsmaxchars?>" size="2" maxlength="4"> <?php echo $l_chars?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_hotnews2?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_targetframe?>:</td>
<td><input class="sninput" type="text" name="hotnews2target" value="<?php echo $hotnews2target?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="hn2nocatlink" value="1" <?php if(bittst($hn_catlinking,BIT_3)) echo "checked"?>> <?php echo $l_dontlinkcat?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>hotnews6/7/8/9</b></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_limitentries?>:</td>
<td align="left"><input type="text" name="hn6_numentries" class="sninput" size="4" maxlength="10" value="<?php echo $hn6_numentries?>"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_targetframe?>:</td>
<td><input class="sninput" type="text" name="hotnews6target" value="<?php echo $hotnews6target?>" size="40" maxlength="240"></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b>hotnews7/9</b></td>
<tr class="inputrow"><td align="right">&nbsp;</td>
<td><input type="checkbox" name="hotnews7ddlink" value="1" <?php if($hotnews7useddlink) echo "checked"?>>&nbsp;<?php echo $l_useddlink?></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b>hotnews6</b></td>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="hn6nocatlink" value="1" <?php if(bittst($hn_catlinking,BIT_1)) echo "checked"?>> <?php echo $l_dontlinkcat?></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b>hotnews7</b></td>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="hn7nocatlink" value="1" <?php if(bittst($hn_catlinking,BIT_2)) echo "checked"?>> <?php echo $l_dontlinkcat?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_newsnotify?> <span class="remark">(newsnotify.php/newsnotify2.php)</span></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_newsnotifydays?>:</td>
<td align="left"><input class="sninput" type="text" name="newsnotifydays" value="<?php echo $newsnotifydays?>" size="2" maxlength="2"> <?php echo $l_days?></td></tr>
