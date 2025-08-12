<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_wap_newsfeed?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="wap_show_numentries" value="1" <?php if(bittst($wap_options,BIT_3)) echo "checked"?>> <?php echo $l_show_numentries_avail?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="wap_show_lastupdated" value="1" <?php if(bittst($wap_options,BIT_9)) echo "checked"?>> <?php echo $l_show_lastupdated?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="wap_link_catlist" value="1" <?php if(bittst($wap_options,BIT_10)) echo "checked"?>> <?php echo $l_linkcatlist?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="wap_nopagecount" value="1" <?php if(bittst($wap_options,BIT_12)) echo "checked"?>> <?php echo $l_nopagecount?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_copyrightnotice?>:</td>
<td><input type="text" class="sninput" name="wap_copyright" value="<?php echo $wap_copyright?>" size="30" maxlength="100"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_entries?></b></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_auto_title?>:</td>
<td><input type="text" name="wap_auto_title" size="3" maxlength="3" value="<?php echo $wap_auto_title?>" class="sninput"> <?php echo $l_chars?> <?php printf($l_maxchars2,"100")?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_auto_short?>:</td>
<td><input type="text" name="wap_auto_short" size="3" maxlength="3" value="<?php echo $wap_auto_short?>" class="sninput"> <?php echo $l_chars?> <?php printf($l_maxchars2,"500")?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_news?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_title?>:</td>
<td><input type="text" class="sninput" name="wap_title" value="<?php echo $wap_title?>" size="30" maxlength="100"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_description?>:</td>
<td><input type="text" class="sninput" name="wap_description" value="<?php echo $wap_description?>" size="30" maxlength="255"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="wap_rss_short" value="1" <?php if(bittst($wap_options,BIT_1)) echo "checked"?>> <?php echo $l_use_rss_short?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_maxentries?>:</td>
<td><input type="text" name="wap_maxentries" size="4" maxlength="10" value="<?php echo $wap_maxentries?>" class="sninput"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_events?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_title?>:</td>
<td><input type="text" class="sninput" name="wap_ev_title" value="<?php echo $wap_ev_title?>" size="30" maxlength="100"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_description?>:</td>
<td><input type="text" class="sninput" name="wap_ev_description" value="<?php echo $wap_ev_description?>" size="30" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_maxentries?>:</td>
<td><input type="text" name="wap_ev_maxentries" size="4" maxlength="10" value="<?php echo $wap_ev_maxentries?>" class="sninput"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_limitdays?>:<br><?php echo $l_no_limiting?></td>
<td><input type="text" name="wap_ev_maxdays" size="4" maxlength="10" value="<?php echo $wap_ev_maxdays?>" class="sninput"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_def_mode?>:</td><td>
<input type="radio" name="wap_ev_mode" value="0" <?php if(!bittst($wap_options,BIT_2)) echo "checked"?>> <?php echo $l_events_today?><br>
<input type="radio" name="wap_ev_mode" value="1" <?php if(bittst($wap_options,BIT_2)) echo "checked"?>> <?php echo $l_events_future?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_announcements?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_title?>:</td>
<td><input type="text" class="sninput" name="wap_an_title" value="<?php echo $wap_an_title?>" size="30" maxlength="100"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_description?>:</td>
<td><input type="text" class="sninput" name="wap_an_description" value="<?php echo $wap_an_description?>" size="30" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_maxentries?>:</td>
<td><input type="text" name="wap_an_maxentries" size="4" maxlength="10" value="<?php echo $wap_an_maxentries?>" class="sninput"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_limitdays?>:<br><?php echo $l_no_limiting?></td>
<td><input type="text" name="wap_an_maxdays" size="4" maxlength="10" value="<?php echo $wap_an_maxdays?>" class="sninput"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_ev_search?></b></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="wap_evs_llink" value="1" <?php if(bittst($wap_options,BIT_4)) echo "checked"?>> <?php echo $l_wap_evs_llink?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_wap_evs_maxldays?>:<br><span class=\"remark\">(&gt;=2)</span></td><td>
<input type="text" class="sninput" name="wap_evs_maxldays" value="<?php echo $wap_evs_maxldays?>" size="4" maxlength="10"> <?php echo $l_days?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="wap_evs_drange" value="1" <?php if(bittst($wap_options,BIT_5)) echo "checked"?>> <?php echo $l_wap_evs_drange?></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_wap_evs_dayrange?>:<br><span class=\"remark\">(&gt;=1)</span></td><td>
<input type="text" class="sninput" name="wap_evs_dayrange" value="<?php echo $wap_evs_dayrange?>" size="4" maxlength="10"> <?php echo $l_days?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="wap_evs_text" value="1" <?php if(bittst($wap_options,BIT_7)) echo "checked"?>> <?php echo $l_wap_evs_text?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_defsearchtype?>:</td><td>
<input type="radio" name="wap_evs_searchtype" value="0" <?php if(!bittst($wap_options,BIT_8)) echo "checked"?>> <?php echo $l_standardsearch?><br>
<input type="radio" name="wap_evs_searchtype" value="1" <?php if(bittst($wap_options,BIT_8)) echo "checked"?>> <?php echo $l_textsearch?>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_catlist?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_title?>:</td>
<td><input type="text" class="sninput" name="wap_cl_title" value="<?php echo $wap_cl_title?>" size="30" maxlength="80"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_description?>:</td>
<td><input type="text" class="sninput" name="wap_cl_description" value="<?php echo $wap_cl_description?>" size="30" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_logogfx?>:</td>
<?php echo gfx_selector("wap_cl_logo",$wap_cl_logo)?>
<tr class="inputrow"><td align="right"><?php echo $l_entriesperpage?>:<br><span class="remark">(0=<?php echo $l_allentries?>)</span></td>
<td><input type="text" name="wap_catlist_epp" value="<?php echo $wap_catlist_epp?>" class="sninput" size="4" maxlength="10"></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="wap_catlist" value="1" <?php if(bittst($wap_options,BIT_6)) echo "checked"?>> <?php echo $l_only_wap_catlist?></td></tr>
<tr class="inputrow"><td>&nbsp;</td><td>
<input type="checkbox" name="wap_cl_nomode" value="1" <?php if(bittst($wap_options,BIT_11)) echo "checked"?>> <?php echo $l_suppress_mode?></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b>wap_short_events2.php</b></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_entriesperpage?>:<br><span class="remark">(&gt;=1)</span></td>
<td><input type="text" name="wap_evlist2_epp" value="<?php echo $wap_evlist2_epp?>" class="sninput" size="4" maxlength="10"></td></tr>
