<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_catlist?></b></td></tr>
<tr class="inputrow"><td width="30%">&nbsp;</td>
<td><input type="checkbox" name="usecatlist" value="1" <?php if($enablecatlist==1) echo "checked"?>>&nbsp;<?php echo $l_enable?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_framenewsscript?>:</td>
<td>
<input type="radio" name="catframenewslist" value="0" <?php if($catframenewslist==0) echo "checked"?>>&nbsp;news.php<br>
<input type="radio" name="catframenewslist" value="1" <?php if($catframenewslist==1) echo "checked"?>>&nbsp;news4.php<br>
<input type="radio" name="catframenewslist" value="2" <?php if($catframenewslist==2) echo "checked"?>>&nbsp;news5.php
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_framewidth?>:</td>
<td><input class="sninput" name="clwidth" type="text" size="10" maxlength="10" value="<?php echo $clwidth?>">&nbsp;<?php echo $l_points?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_frameheight?>:<span class="remark">(newsframe2.php)</span></td>
<td><input class="sninput" name="nfheight" type="text" size="10" maxlength="10" value="<?php echo $nfheight?>">&nbsp;<?php echo $l_points?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_clinline?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_clleftwidth?>:</td>
<td><input type="text" class="sninput" name="clleftwidth" value="<?php echo $clleftwidth?>" size="10" maxlength="10"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_clrightwidth?>:</td>
<td><input type="text" class="sninput" name="clrightwidth" value="<?php echo $clrightwidth?>" size="10" maxlength="10"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="noclwrap" value="1" <?php if($clnowrap==1) echo "checked"?>>&nbsp;<?php echo $l_clnowrap?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_heading?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("clheadingbgcolor",$clheadingbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("clheadingfontcolor",$clheadingfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="clheadingfont" value="<?php echo $clheadingfont?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="clheadingfontsize" value="<?php echo $clheadingfontsize?>" size="4" maxlength="4"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_catlist?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("clcontentbgcolor",$clcontentbgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("clcontentfontcolor",$clcontentfontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="clcontentfont" value="<?php echo $clcontentfont?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="clcontentfontsize" value="<?php echo $clcontentfontsize?>" size="4" maxlength="4"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_clhighlight?>:</td>
<?php echo color_chooser("clcontenthighlight",$clcontenthighlight)?>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="noclactlink" value="1" <?php if($clactdontlink==1) echo "checked"?>>&nbsp;<?php echo $l_clactdontlink?></td></tr>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_categories?></b></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_catinfotext?></b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_leftindent?>:</td>
<td><input type="text" class="sninput" name="catinfoindent" value="<?php echo $catinfoindent?>" size="6" maxlength="20"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_bgcolor?>:</td>
<?php echo color_chooser("catinfobgcolor",$catinfobgcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_fontcolor?>:</td>
<?php echo color_chooser("catinfofontcolor",$catinfofontcolor)?>
<tr class="inputrow"><td align="right"><?php echo $l_font?>:</td>
<td align="left"><input class="sninput" type="text" name="catinfofont" value="<?php echo $catinfofont?>" size="40" maxlength="240"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_fontsize?>:</td>
<td align="left"><input class="sninput" type="text" name="catinfofontsize" value="<?php echo $catinfofontsize?>" size="4" maxlength="4"></td></tr>
