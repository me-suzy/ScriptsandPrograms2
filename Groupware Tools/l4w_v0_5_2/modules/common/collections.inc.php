	<TABLE class="frame">
	<tr class="line">
		<th  class='box' width=100>
			<?=translate ('add to collections')?>
		</th>
	    <td colspan=4>
            <select name='addto_collection[]' size=4 style='width:300px' multiple>
            <?php
                $query = "
                    SELECT
                        collection_id,
						collections.name,
						description
                    FROM ".TABLE_PREFIX."collections 
                    LEFT JOIN ".TABLE_PREFIX."metainfo ON ".TABLE_PREFIX."metainfo.object_id=".TABLE_PREFIX."collections.collection_id
                    LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups ON ".TABLE_PREFIX."metainfo.grp=gacl_aro_groups.id
                    LEFT JOIN ".TABLE_PREFIX."users ON ".TABLE_PREFIX."users.id=".TABLE_PREFIX."metainfo.owner
                    WHERE ".TABLE_PREFIX."metainfo.object_type='collection'
                          AND ".get_all_groups_or_statement ($_SESSION['user_id'])." 
                          AND (".TABLE_PREFIX."metainfo.owner=".$_SESSION['user_id']." OR
                                 (".TABLE_PREFIX."metainfo.access_level LIKE '____r_____') OR
                                 (".TABLE_PREFIX."metainfo.access_level LIKE '_______r__')
                            )";    
                $res   = mysql_query ($query);
                while ($row = mysql_fetch_array ($res)) {
                    echo "<option value='".$row['collection_id']."'>".$row['name']."</option>\n";    
                }    
            ?>
            </select>
            
	    </td>
	</tr>
	<tr class="line">
		<th  class='box' width=100>
			<?=translate ('collections')?>
		</th>
	    <td colspan=4>
            <table border=0>
            <?php
            
                //if ($this->model->entry['memo_id']->get() > 0) {
                if ($coll_object_id > 0) {
                    $query = "SELECT 
                                collection_id, 
                                name, 
                                collections.description 
                              FROM refering 
                              LEFT JOIN collections ON refering.to_object_id=collections.collection_id
                              WHERE from_object_type='note' AND 
                                    from_object_id=".$coll_object_id." AND
                                    to_object_type='collection'
                              ORDER BY to_object_type";

                    $res   = mysql_query ($query);
                    while ($row = mysql_fetch_array ($res)) {
                        // Find out rights about this entry:
                        $col_meta_values    = get_entries_for_primary_key(
                                                "metainfo", 
                                                array ("object_type" => 'collection',
                                                       "object_id"   => $row['collection_id']));
                        
                        // --- sufficient rights ? ------------------------------
                        if (!user_may_read ($col_meta_values['owner'],$col_meta_values['grp'],$col_meta_values['access_level'])) {
                            continue;
                        }
                        $access_pic  = "<img src='".$img_path.get_access_icon ($col_meta_values['access_level'])."' 
                                            title='".translate($col_meta_values['access_level'])."' 
                                            align=top>";
                                                        
                        echo "<tr>";
                        echo "<td><span name='ref_collection_".$row['collection_id']."'>".$access_pic.$row['name']."</span></td>";
                        if (user_may_edit ($col_meta_values['owner'],$col_meta_values['grp'],$col_meta_values['access_level'])) {
                            echo "<td><a href='javascript:delete_from_collection(\"note\",".$coll_object_id.",\"collection\",".$row['collection_id'].")'>";
                            echo "<img src='".$img_path."delete2.gif' border=0 title='".translate ('delete collection')."'></a></td>";
                        }
                        else
                            echo "<td>&nbsp;</td>";
                        echo "</tr>\n";
                        // Show other entries in collection (if any):
                        $ref_query = "SELECT * FROM refering 
                                  WHERE to_object_type='collection' AND 
                                        to_object_id=".$row['collection_id']." 
                                  ORDER BY from_object_type";
                        $ref_res = mysql_query ($ref_query);
                        while ($ref_row = mysql_fetch_array ($ref_res)) {
                            // Find out rights about this entry:
                            $entry_values   = get_entries_for_primary_key (
                                                    "memos", 
                                                    array ("memo_id" => $ref_row['from_object_id']));
        
                            $ref_meta_values    = get_entries_for_primary_key(
                                                    "metainfo", 
                                                    array ("object_type" => 'note',
                                                           "object_id"   => $ref_row['from_object_id']));
        
                            // --- sufficient rights ? ------------------------------
                            if (!user_may_read ($ref_meta_values['owner'],$ref_meta_values['grp'],$ref_meta_values['access_level'])) {
                                continue;
                            }
                            $access_pic  = "<img src='".$img_path.get_access_icon ($ref_meta_values['access_level'])."' 
                                                title='".translate($ref_meta_values['access_level'])."' 
                                                align=top>";
                            echo "<tr>";
                            echo "<td colspan=2>&nbsp;&nbsp;&nbsp;&rarr;".$access_pic." ".translate ($ref_row['from_object_type'])."&nbsp;";
                            echo "<a ";
                            echo "href='../../modules/notes/index.php?command=edit_entry&entry_id=".$ref_row['from_object_id']."'>";
                            echo $entry_values['headline']."</a></td>";
                            //echo "<td>".translate ($ref_row['description'])."</td>";
                            echo "</tr>\n";
                        }                              
                        
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




