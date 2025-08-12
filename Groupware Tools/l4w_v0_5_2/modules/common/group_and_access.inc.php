<?php
  /**
    * $Id: group_and_access.inc.php,v 1.15 2005/07/28 21:34:57 carsten Exp $
    *
    * shows group and permissions
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package      common
    */

    list ($def_group, $def_access) = get_defaults();

    // --- group ----------------------------------------------------
    $sel_id = $this->model->entry['use_group']->get();
    if ($sel_id == "") 
        $sel_id = $def_group;
    
    // --- access ---------------------------------------------------
    if (isset ($use_access)) 
        $def_access = $use_access;
    
    // --- group options, cnt ---------------------------------------    
    list ($group_options, $cnt, $single_hit_id) = get_group_options ($GLOBALS['gacl_api'], $sel_id, false);
  
  	// --- echo tr --------------------------------------------------
  	$echo_tr = false;
  	
  	// --- group ----------------------------------------------------
    if ($cnt > 1 && $group_and_access_show_groups) {
        //if ($group_and_access_show_text) { 
        if (isset($_SESSION['helpmode']) && $_SESSION['helpmode'] == "true") { 
        	$echo_tr = true;
        ?>
            <tr class='line'>
        	    <td colspan=2>&nbsp;</td>
        	    <td colspan=3>
        	        <?=translate ('group and permissions')?>
                </td>
            </tr>
            <tr>
		    	<td class='narrow' colspan=5><img src='".$img_path."shim.gif' height=1></td>
			</tr>
    	<?php } ?>    
	    
    	<tr class='line'>
            <th class='box' colspan=2><?=translate('group')?>:</th>
    	    <td colspan=1>
    	        <select name='use_group' class='formular'>
                <?=$group_options?>
    	        </select>
            <td align='left' colspan=2>
    	            <input type='checkbox' name='make_group_default'>&nbsp;
    	            <?=translate ('make default')?>
    	        </td>
      	    </td>
    	</tr>
	<?php 
	} 
	elseif ($cnt == 1) {
	    ?>
	        <input type=hidden name='use_group' value='<?=$single_hit_id?>'>    
        <?php
	}    
	else { 
		?>
    		<input type=hidden name='use_group' value='<?=$sel_id?>'>
	<?php } ?>
	
	<?php 
    // --- access ---------------------------------------------------
    if ($group_and_access_show_access) { 
        $sel_access = $def_access;
        $echo_tr    = true;
        list ($options, $cnt, $alt_value) = access_options($sel_access);
        if ($cnt > 1) {
            ?>
        	<tr class='line'>
                <th class='box' colspan=2><?=translate('access')?>:</th>
	            <td colspan=1>
	                <select name='access' class='formular'>
	                <?=$options?>
	                </select>
    	        </td>	
	            <td align='left' colspan=2>
	                <input type='checkbox' name='make_access_default'>&nbsp;
	                <?=translate ('make default')?>
	            </td>
    	    </tr>
    	    <?php
    	 }
    	 else {
            echo "<input type=hidden name='access' value='".$alt_value."'>";	    
    	 }   
    } ?>
    
    <?php
    	if ($echo_tr) {
    		echo "    
    		    <tr>
		            <td class='narrow' colspan=5><img src='".$img_path."shim.gif' height=1></td>
	            </tr>
            ";
    	}	
    ?>
