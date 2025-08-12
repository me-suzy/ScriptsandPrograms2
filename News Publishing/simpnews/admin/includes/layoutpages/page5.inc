<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_announce?></b></td>
<tr class="inputrow"><td width="30%">&nbsp;</td><td>
<input type="checkbox" name="an_evlist" value="1" <?php if(bittst($announceoptions,BIT_1)) echo "checked"?>> <?php echo $l_ineventlist?><br>
<input type="checkbox" name="an_newslist" value="1" <?php if(bittst($announceoptions,BIT_2)) echo "checked"?>> <?php echo $l_innewslist?><br>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_eventcal?></b></td>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="an_evcal_on" value="1" <?php if(bittst($announceoptions,BIT_3)) echo "checked"?>> <?php echo $l_enable?><hr>
<b><?php echo $l_display?>:</b><br>
<input type="radio" name="an_evcal" value="0" <?php if(!bittst($announceoptions,BIT_4)) echo "checked"?>> <?php echo $l_onlyasicon?><br>
<input type="radio" name="an_evcal" value="1" <?php if(bittst($announceoptions,BIT_4)) echo "checked"?>> <?php echo $l_asentry?><br>
</td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_maxevcannounce?>:</td><td>
<input type="text" class="sninput" name="maxevcannounce" size="4" maxlength="4" value="<?php echo $maxevcannounce?>"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>news5.php</b></td>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_displaymode?>:</td><td>
<input type="radio" name="an_n5dmode" value="0" <?php if(!bittst($announceoptions,BIT_5)) echo "checked"?>> <?php echo $l_n5ontop?><br>
<input type="radio" name="an_n5dmode" value="1" <?php if(bittst($announceoptions,BIT_5)) echo "checked"?>> <?php echo $l_n5inline?>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>evlist2.php</b></td>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_displaymode?>:</td><td>
<input type="radio" name="an_ev2dmode" value="0" <?php if(!bittst($announceoptions,BIT_6)) echo "checked"?>> <?php echo $l_ev2ontop?><br>
<input type="radio" name="an_ev2dmode" value="1" <?php if(bittst($announceoptions,BIT_6)) echo "checked"?>> <?php echo $l_ev2inline?>
</td></tr>
<tr class="inputrow"><td></td><td>
<input type="checkbox" name="an_ev2asc" value="1" <?php if(bittst($announceoptions,BIT_7)) echo "checked"?>> <?php echo $l_displayinasclist?> <?php echo $l_inlineremark?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_newsticker?></b></td>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="an_newsticker" value="1" <?php if(bittst($announceoptions,BIT_8)) echo "checked"?>> <?php echo $l_newstickerinclude?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>currentevents.php</b></td>
<tr class="inputrow"><td></td><td>
<input type="checkbox" name="an_curev" value="1" <?php if(bittst($announceoptions,BIT_9)) echo "checked"?>> <?php echo $l_curevinclude?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_displaymode?>:</td><td>
<input type="radio" name="an_curevdisp" value="0" <?php if(!bittst($announceoptions,BIT_10)) echo "checked"?>> <?php echo $l_onlyasicon?><br>
<input type="radio" name="an_curevdisp" value="1" <?php if(bittst($announceoptions,BIT_10)) echo "checked"?>> <?php echo $l_asentry?>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>newsnotify(2).php</b></td></tr>
<tr class="inputrow"><td></td><td>
<input type="checkbox" name="an_nnotify" value="1" <?php if(bittst($announceoptions,BIT_11)) echo "checked"?>> <?php echo $l_nnotifyinclude?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>hotnews(2/4/5).php</b></td>
<tr class="inputrow"><td></td><td>
<input type="checkbox" name="an_hotnews" value="1" <?php if(bittst($announceoptions,BIT_12)) echo "checked"?>> <?php echo $l_hotnewsinclude?></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b>hotnews5.php</b></td>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_displaymode?>:</td><td>
<input type="radio" name="an_hn5dmode" value="0" <?php if(!bittst($announceoptions,BIT_13)) echo "checked"?>> <?php echo $l_n5ontop?><br>
<input type="radio" name="an_hn5dmode" value="1" <?php if(bittst($announceoptions,BIT_13)) echo "checked"?>> <?php echo $l_n5inline?>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>hotnews6/7/8/9</b></td>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="an_hn6show" value="1" <?php if(bittst($announceoptions,BIT_14)) echo "checked"?>> <?php echo $l_hotnewsinclude?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_limitentries?>:</td><td>
<input type="text" class="sninput" name="anhn6numentries" size="4" maxlength="10" value="<?php echo $anhn6numentries?>"></td></tr>
<tr class="listheading2"><td align="left" colspan="2"><b>hotnews6/7</b></td>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_displaymode?>:</td><td>
<input type="radio" name="an_hn6dmode" value="0" <?php if(!bittst($announceoptions,BIT_15)) echo "checked"?>> <?php echo $l_n6ontop?><br>
<input type="radio" name="an_hn6dmode" value="1" <?php if(bittst($announceoptions,BIT_15)) echo "checked"?>> <?php echo $l_n6inline?>
</td></tr>
