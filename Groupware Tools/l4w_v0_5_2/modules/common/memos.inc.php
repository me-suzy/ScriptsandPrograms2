<?php
  /**
    * $Id: memos.inc.php,v 1.7 2005/04/05 19:39:44 carsten Exp $
    *
    * common memos table
    * 
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package common
    */

?>

<?php if ($memo_object_id > 0) {  ?>
    
    <table align=left border=0>
	<!--<tr>
	    <td colspan=6>
	        <img onclick="toggleDisplay('memos')" 
                 align=middle
	             title='<?=translate ('explain memos')?>'
	             src='<?=$img_path?>hidden_element.gif' border=0>
	        <a href='#' onclick="toggleDisplay('memos')"><?=translate('show memos')?></a>
	    </td>
	</tr>-->
	<tr>
	    <td colspan=6>
	            
        <SPAN id="memos" style="display:visible">
	    <table>
	    <!--<tr>
	        <th class=box><?=translate('last change')?></th>
	        <th class=box><?=translate('last changer')?></th>
	        <th class=box><?=translate('memo')?></th>
	        <th class=box>&nbsp;</th>
	        <th class=box>&nbsp;</th>
	        <th class=box>&nbsp;</th>
	    </tr>
	    <tr>
	        <td colspan=6><?=translate('new memo')?></td>
	    </tr>
	    <tr>
	        <td>&nbsp;</td>
	        <td><?=get_username_by_user_id ($_SESSION['user_id'])?></td>
	        <td><input type=text name='new_memo' size=80 maxsize=100 value=''></td>
	        <td><a href='#' onClick='javascript:open_memo ("plain", "")'><?=translate('add text')?></a></td>
	        <!--<td><a href='#' onClick='javascript:open_memo ("html", "")'><?=translate('add html')?></a></td>- ->
	        <!--<td><a href=''><?=translate('professional')?></a></td> - ->
	    </tr>-->
	    <?php
	        $res = get_memos ($memo_object_type, $memo_object_id);

	        if (mysql_num_rows ($res) > 0)
	            echo "<tr><td colspan=6><hr></td></tr>\n";
	            
	        while ($row = mysql_fetch_array ($res)) {
	            switch ($row['object_type']) {
	                case "note": 
	                    $type      = translate ($row['object_type']);
	                    $obj_query = "
	                        SELECT headline, content, creator, owner, grp, created, last_change, access_level
                            FROM memos
                            LEFT JOIN ".TABLE_PREFIX."metainfo ON memos.memo_id=".TABLE_PREFIX."metainfo.object_id
                            WHERE ".TABLE_PREFIX."metainfo.object_type='note' AND
                            	    memo_id=".$row['object_id'];
	                    break;
	                default: break;
	            }    
	            
	            $obj_res = mysql_query ($obj_query);
	            logDBError (__FILE__, __LINE__, mysql_error(), $obj_query);
	            $obj_row = mysql_fetch_array ($obj_res);
	            
	            switch ($row['object_type']) {
	                case "note": 
	                    $link   = "../../modules/notes/index.php?command=edit_att_entry&entry_id=".$row['object_id'];
	                    $link  .= "&ref_object_type=".$memo_object_type;
	                    $link  .= "&ref_object_id=".$memo_object_id;
                        $link  .= "&ref_type=2";
	                    $name   = "editnote";
	                    $title  = "<a href='javascript:edit_entry(\"".$link."\", \"".$name."\");'>".$obj_row['headline']."</a>";
	                    break;
	                default: break;
	            }
                $access_pic  = "<img src='".$img_path.get_access_icon ($obj_row['access_level'])."' 
                                        title='".translate($obj_row['access_level'])."' 
                                        align=top>";

	            ?>
                <tr>
                    <td><?=$access_pic?></td>
                    <td><?=$type?></td>
                    <td><?=$title?></td>
	            </tr>
	            <?php
	            
	            /*$content = $row['content'];
	            if (trim($content) != '') {
	                
                    $content = '<a href="javascript:void(0);" 
                                    onclick="return overlib(\''.$content.'\', STICKY, CAPTION,
                                    \''.$row['headline'].'\', CENTER);" 
                                    onmouseout="nd();">['.translate('show text').']</a>';
	            }    
	            else
	                $content = "<a href='javascript:open_memo (\"plain\", \"".$row['memo_id']."\")'>".translate('add text')."</a>";    
	            
	            echo "<tr>";
	            echo "<td>".$row['updated']."</td>";
	            echo "<td>".get_username_by_user_id($row['changer'])."</td>";
	            echo "<td><a href='javascript:open_memo (\"plain\", \"".$row['memo_id']."\")'>".$row['headline']."</a></td>";
	            echo "<td>$content</td>";
	            echo "<td>&nbsp;</td>";
	            echo "<td>&nbsp;</td>";
	            echo "</tr>\n";    */
	        }    
	    ?>
        </table>
        </span>
        
        </td>
    </tr>
    </table>
    <?php } ?>