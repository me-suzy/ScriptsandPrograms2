<?php

   /**
    * Fields used for model
    * 
    * @author       ###author###
    * @copyright    ###copyright###
    * @package      ###package###
    */
            
	$this->entry['###name###_id'] = new easy_integer (null,0);

	$this->entry['parent']        = new easy_integer (0,0);

	$this->entry['command']       = new easy_string  ('');      // holding the next command to execute

    $this->entry['name']          = new easy_string  (null,null,null,false);
    $this->entry['name']->set_empty_allowed (false);

    $this->entry['name']->class   = "focus";
    $this->entry['name']->maxlength = 50;
    
	$this->entry['description']   = new easy_string  ('');      
	$this->entry['description']->style = 'width:220px;';
    
    $selected  = array ();
    if (isset ($_REQUEST['entry_id']) && $_REQUEST['entry_id'] > 0) {
        $query = "
            SELECT component_id FROM ".TABLE_PREFIX."category_component 
            WHERE category_id=".$_REQUEST['entry_id'];
            //echo $query;
        $sel_res   = mysql_query ($query);
        while ($sel_row = mysql_fetch_array ($sel_res))
            $selected[] = $sel_row['component_id']; 
    }
            
    $this->entry['components']    = new easy_select   (array (), 10, 200, $selected);
    $query = "SELECT id, module_name FROM ".TABLE_PREFIX."components WHERE module_type='system'";
    $res   = mysql_query ($query);
    $this->entry['components']->fillFromResultSet ($res);
    $this->entry['components']->style='width:220px;';
    $this->entry['components']->multiple=true;
        
?>