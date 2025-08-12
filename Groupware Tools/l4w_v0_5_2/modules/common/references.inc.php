	<TABLE class="frame">
	<tr class="line">
		<th  class='box' width=100>
			<?=translate ('add reference')?>
		</th>
	    <td colspan=4>
            <select name='reference'>
                <option value='note'><?=translate ('note')?></option>
                <?php
                    /*$query = "SELECT DISTINCT object_type AS type FROM events ORDER BY type";
                    $res   = mysql_query ($query);
                    while ($row = mysql_fetch_array ($res))
                        echo "<option value='".$row['type']."'>".translate ($row['type'])."</option>\n";
                    */
                ?>
            </select>
            <a href='javascript:add_reference(<?=$ref_object_id?>)'>
                <img src='<?=$img_path?>run.gif' border=0 title='<?=translate ('execute')?>' align=top>
	        </a>
	    </td>
	</tr>
	<tr class="line">
		<th  class='box' width=100>
			<?=translate ('added references')?>
		</th>
	    <td colspan=4>
            <select name='added_references' size=4 style='width:300px' readonly>
            </select>
	    </td>
	</tr>
	<tr class="line">
		<th  class='box' width=100>
			<?=translate ('description')?>
		</th>
	    <td colspan=4>
            <input type=text name='ref_desc' style='width:300px' value='<?=$this->model->entry['ref_desc']->get()?>'>
	    </td>
	</tr>
	<input type=hidden name='new_references' value=''>
	<tr class="line">
		<th  class='box' width=100>
			<?=translate ('references')?>
		</th>
	    <td colspan=4>
            <table>
            <?php
                if ($ref_object_id > 0) {

                    $query = "SELECT * FROM refering 
                              WHERE from_object_type='note' AND 
                                    from_object_id=".$ref_object_id." AND
                                    to_object_type != 'collection'
                              ORDER BY to_object_type";
                    $res   = mysql_query ($query);
                    while ($row = mysql_fetch_array ($res)) {
                        // Find out rights about this entry:
                        $entry_values   = get_entries_for_primary_key (
                                                "memos", 
                                                array ("memo_id" => $row['to_object_id']));
    
                        $ref_meta_values    = get_entries_for_primary_key(
                                                "metainfo", 
                                                array ("object_type" => 'note',
                                                       "object_id"   => $row['to_object_id']));
                        
                        // --- sufficient rights ? ------------------------------
                        if (!user_may_read ($ref_meta_values['owner'],$ref_meta_values['grp'],$ref_meta_values['access_level'])) {
                            continue;
                        }
                        $access_pic  = "<img src='".$img_path.get_access_icon ($ref_meta_values['access_level'])."' 
                                            title='".translate($ref_meta_values['access_level'])."' 
                                            align=top>";
                        echo "<tr>";
                        echo "<td>".$access_pic." ".translate ($row['to_object_type'])."</td>";
                        echo "<td><a name='ref_".$row['to_object_type']."_".$row['to_object_id']."' style='' ";
                        echo "href='../../modules/notes/index.php?command=edit_entry&entry_id=".$row['to_object_id']."'>";
                        echo $entry_values['headline']."</a></td>";
                        //echo "<td>".translate ($row['description'])."</td>";
                        // Referenz löschen?
                        if (user_may_edit ($ref_meta_values['owner'],$ref_meta_values['grp'],$ref_meta_values['access_level'])) {
                            echo "<td><a href='javascript:delete_reference(\"note\",".$ref_object_id.",\"note\",".$row['to_object_id'].")'>";
                            echo "<img src='".$img_path."delete2.gif' border=0 title='".translate ('delete reference')."'></a></td>";
                        }
                        else
                            echo "<td>&nbsp;</td>";
                        echo "</tr>\n";
                    }                              
                }
            ?>
            </table>
	    </td>
	</tr>
    <?php if (!(bool)$this->model->entry['locked']->get()) { ?>
	<tr class="line"><td colspan=5><hr></td></tr>
	<tr class="line">
	    <td colspan=5>
	        <input type=submit class=submit name='submit_me' onClick='javascript:something_changed=false;' value='<?=translate('save')?>'>&nbsp;&nbsp;
            <input type=submit class=submit name='apply'     onClick='javascript:run_apply (<?=$tab_nr?>);' value='<?=translate('apply')?>'>&nbsp;&nbsp;
	    </td>
	</tr>
	<?php } ?>
	<tr><td colspan=5 valign="top"><hr></td></tr>
	</table>
