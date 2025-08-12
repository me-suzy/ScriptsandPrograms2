<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_rss_newsfeed?></b></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_channel?></b></td></tr>
<tr class="inputrow"><td align="right" width="30%"><?php echo $l_title?>:</td>
<td><input type="text" class="sninput" name="rss_channel_title" value="<?php echo $rss_channel_title?>" size="30" maxlength="100"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_description?>:</td>
<td><input type="text" class="sninput" name="rss_channel_description" value="<?php echo $rss_channel_description?>" size="30" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_website_link?>:</td>
<td><input type="text" class="sninput" name="rss_channel_link" value="<?php echo $rss_channel_link?>" size="30" maxlength="255"></td></tr>
<tr class="inputrow"><td align="right"><?php echo $l_copyrightnotice?>:</td>
<td><input type="text" class="sninput" name="rss_channel_copyright" value="<?php echo $rss_channel_copyright?>" size="30" maxlength="100"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_managingeditor?>:<br><?php echo $l_rss_mailformat?></td>
<td><input type="text" class="sninput" name="rss_channel_editor" value="<?php echo $rss_channel_editor?>" size="30" maxlength="100"></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_webmaster?>:<br><?php echo $l_rss_mailformat?></td>
<td><input type="text" class="sninput" name="rss_channel_webmaster" value="<?php echo $rss_channel_webmaster?>" size="30" maxlength="100"></td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_entries?></b></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_auto_title?>:</td>
<td><input type="text" name="rss_auto_title" size="3" maxlength="3" value="<?php echo $rss_auto_title?>" class="sninput"> <?php echo $l_chars?> <?php printf($l_maxchars2,"100")?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_auto_short?>:</td>
<td><input type="text" name="rss_auto_short" size="3" maxlength="3" value="<?php echo $rss_auto_short?>" class="sninput"> <?php echo $l_chars?> <?php printf($l_maxchars2,"500")?></td></tr>
<tr class="inputrow"><td align="right" valign="top"><?php echo $l_maxentries?>:</td>
<td><input type="text" name="rss_maxentries" size="4" maxlength="10" value="<?php echo $rss_maxentries?>" class="sninput"></td></tr>
