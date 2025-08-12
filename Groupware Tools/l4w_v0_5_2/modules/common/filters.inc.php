<?php

	$headline_right    = "<div name='filterformular'>";

	// --- workflow -------------------------------------------------
	list ($group_options, $cnt) = get_state_options ($entry_type, $_SESSION['use_my_state']);
	if ($cnt > 1) {
		$headline_right   .= translate('filter', null, true).":&nbsp;";
    	$headline_right   .= "[".translate ('workflow', null, true)."]&nbsp;";
    	$headline_right   .= "<select name='my_state' 
    	                              class='filter' 
    	                              style='width:120px;'
    	                              onChange='set_filter(\"my_state\", \"".$this->model->entry['command']->get()."\")'>";	
	    $headline_right   .= $group_options; //get_state_options ($entry_type, $_SESSION['use_my_state']);
	    $headline_right   .= "</select>&nbsp;&nbsp;&nbsp;\n";
	}
	
	// --- owner ----------------------------------------------------
	(isset($_SESSION['use_my_owner'])) ? $use_my_owner = $_SESSION['use_my_owner'] : $use_my_owner = null;
	list ($owner_options, $cnt) = get_owner_options ($use_my_owner, true);
    if ($cnt > 1) {
	    $headline_right   .= "[".translate ('owner', null, true)."]&nbsp;";
		$headline_right   .= "<select name='my_owner' 
	                              class='filter' 
   	                              style='width:120px;'
	                              onChange='set_filter(\"my_owner\", \"".$this->model->entry['command']->get()."\")'>";
		$headline_right   .= $owner_options;
		$headline_right   .= "</select>\n&nbsp;&nbsp;&nbsp;";
    }
    
	// --- Groups ---------------------------------------------------
	list ($group_options, $cnt, $single_hit_id) = 
	    get_group_options ($GLOBALS['gacl_api'], $_SESSION['use_my_group'], true);
    list ($def_group, $def_access) = get_defaults();
    
    $headline_right   .= "[".translate ('group', null, true)."]&nbsp;";
	if ($cnt > 1) {
		$headline_right   .= "<select name='my_group' 
		                              class='filter' 
   	    	                          style='width:120px;'
	        	                      onChange='set_filter(\"my_group\", \"".$this->model->entry['command']->get()."\")'>";
		$headline_right   .= $group_options;
		$headline_right   .= "</select>&nbsp;&nbsp;&nbsp;\n";
	}
	else {
		$headline_right   .= "<select name='my_group' disabled
		                              class='filter' 
   	    	                          style='width:120px;'
	        	                      onChange='set_filter(\"my_group\", \"".$this->model->entry['command']->get()."\")'>";
		$headline_right   .= "<option value='$single_hit_id'>".get_group_alias($single_hit_id)."</option>\n";
		$headline_right   .= "</select>&nbsp;&nbsp;&nbsp;\n";
    }
    
	/*else {
		$headline_right   .= "<i>".get_group_alias($single_hit_id)."</i>";
		$headline_right   .= "&nbsp;&nbsp;&nbsp;";	
	}*/
		
	// --- Clear filters --------------------------------------------
	$headline_right   .= "&nbsp;<a href='#' onClick='clear_filters(\"".$this->model->entry['command']->get()."\")'>";
	$headline_right   .= "<img src='".$img_path."clear.gif' align='top' border=0 title='".translate ('clear filter', null, true)."'>";
    $headline_right   .= "</a>&nbsp;&nbsp;";
	
    $headline_right   .= "</div>\n";

?>