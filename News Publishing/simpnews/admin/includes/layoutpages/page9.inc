<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<tr class="listheading0"><td align="left" colspan="2"><b><?php echo $l_propose_entries?></b></td>
<tr class="inputrow"><td align="right" valign="top" width="30%"><?php echo $l_req_fields?>:</td>
<td valign="top">
<input type="checkbox" name="propose_name" value="1" <?php if(bittst($proposereq,BIT_1)) echo "checked"?>> <?php echo $l_authorname?><br>
<input type="checkbox" name="propose_email" value="1" <?php if(bittst($proposereq,BIT_2)) echo "checked"?>> <?php echo $l_authormail?><br>
<input type="checkbox" name="propose_heading" value="1" <?php if(bittst($proposereq,BIT_3)) echo "checked"?>> <?php echo $l_heading?><br>
</td></tr>
<tr class="listheading1"><td align="left" colspan="2"><b><?php echo $l_propose_events?></b></td>
<tr class="inputrow"><td align="right"><?php echo $l_evproposemaxyears?>:</td>
<td><input type="text" class="sninput" name="evproposemaxyears" value="<?php echo $evproposemaxyears?>" size="4" maxlength="10"></td></tr>
