<?php
if(!function_exists('viewArray')){
function viewArray($arr)
{
    ob_start();
		$state = 1;
		$color[0] = '#9999ff';
		$color[1] = '#ccccff';		
    echo '<table cellpadding="0" cellspacing="0" border="0">';
    foreach ($arr as $key1 => $elem1){
		    $state = ($state>0)?0:1;			
				$key1 = $key1 + 1;  		
        echo '<tr>';
        echo '<td width="5" bgcolor="'.$color[$state].'" align="right"> '.$key1.'.&nbsp;</td>';
        if (is_array($elem1)) { extArray($elem1,$color[$state]); }
        else { echo '<td bgcolor="'.$color[$state].'">'.$elem1.'&nbsp;</td>'; }
        echo '</tr>';
    }
    echo '</table>';
		$result = ob_get_contents(); 
		@ob_end_clean();
		return $result;
}

function extArray($arr,$color)
{    
    echo '<td bgcolor="'.$color.'">';
    echo '<table cellpadding="0" cellspacing="0" border="0">';
    foreach ($arr as $key => $elem) {
        echo '<tr>';
        echo '<td bgcolor="'.$color.'">'.$key.'&nbsp;</td>';
        if (is_array($elem)) { extArray($elem,$color);    }
        else { echo '<td bgcolor="'.$color.'">'.htmlspecialchars($elem).'&nbsp;</td>'; }
        echo '</tr>';
    }
    echo '</table>';
    echo '</td>';		
}

}
?>
