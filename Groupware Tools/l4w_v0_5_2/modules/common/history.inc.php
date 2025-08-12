<?php 

  /**
    * $Id: history.inc.php,v 1.9 2005/05/27 08:00:22 carsten Exp $
    *
    * history table with standard mappings
    * 
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package common
    */

    $res = get_history ($history_object_type, $history_object_id);    
    if ($history_object_id > 0 && mysql_num_rows ($res) > 0) {  
    $old_time = '';
?>

    <table align=left border=0>
	<tr class="line">
	    <td colspan=5>
	            
	    <table>
	    <tr>
	        <th class=box width='100'><?=translate('date')?></th>
	        <th class=box width='100'><?=translate('user')?></th>
	        <th class=box width='80'><?=translate('column')?></th>
	        <th class=box width='100'><?=translate('old value')?></th>
	        <th class=box width='100'><?=translate('new value')?></th>
	    </tr>
	<?php 
	    while ($row = mysql_fetch_array ($res)) { 
	        $time = substr ($row['tstamp'],6,2).".".
	                substr ($row['tstamp'],4,2).".".
	                substr ($row['tstamp'],0,4)." ".
	                substr ($row['tstamp'],8,2).":".
	                substr ($row['tstamp'],10,2).":".
	                substr ($row['tstamp'],12,2);
	        switch ($row['col']) {
	            case 'grp':
	                $old = get_group_alias ($row['old_value']);    
	                $new = get_group_alias ($row['new_value']);    
                    break;	            
	            case 'state':
	                if ($row['old_value'] != '' && $row['old_value'] >= 0)
    	                $old = get_state_name($history_object_type,$row['old_value']);    
	                else 
	                    $old = "-";
	                $new = get_state_name($history_object_type,$row['new_value']);    
                    break;	            
	            case 'country':
	                $old = get_country_name ($row['old_value']);    
	                $new = get_country_name ($row['new_value']);    
                    break;	            
	            default: 
	                $old = $row['old_value'];    
	                $new = $row['new_value'];    
                    break;
            }    
	        
	        ?>
    	<tr>
            <td><?if ($time != $old_time) { echo $time; $old_time = $time; }?></td>
	        <td><?=get_username_by_user_id($row['user_id'])?></td>
	        <td><?=translate($row['col'])?></td>
	        <td><?=$old?></td>
	        <td><?=$new?></td>
	    </tr>
    <?php } ?>
        </table>
        </td>
    </tr>
    </table>
    <?php } 
    else if ($history_object_id > 0 && mysql_num_rows ($res) == 0) {  
        echo translate ('no element found');
    }
    ?>