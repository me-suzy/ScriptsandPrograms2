<?php

   /**
    * Fields used for model
    * 
    * This file contains the fields used in the categories model. Every field is an instance
    * of a certain datatype of easy framework
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      categories
    */
    
	$this->entry['collection_id'] = new easy_integer (null,0);

	$this->entry['parent']        = new easy_integer (0,0);

	$this->entry['command']       = new easy_string  ('');      // holding the next command to execute

    $this->entry['name']          = new easy_string  ('',null,null,false);
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
    $query = "
        SELECT id, module_name FROM ".TABLE_PREFIX."components 
        WHERE module_type='system' AND enabled='1'";
    $res   = mysql_query ($query);
    $this->entry['components']->fillFromResultSet ($res);
    $this->entry['components']->style='width:220px;';
    $this->entry['components']->multiple=true;
    
    
    // datagrid fields
    //$this->order                  = new easy_integer (1,0);



    //$this->entry['use_group']->class         = "formular";
    
        
    
    //$this->entry['new_memo']      = new easy_string  (null);
        
    /*$this->entry['owner']         = new easy_integer (null,0);       // needed for ../common/views/folder.tpl
    $this->entry['state']         = new easy_integer (null,0);       // needed for ../common/views/folder.tpl
    $this->entry['access']        = new easy_string  ('-rwxrw----'); // needed for ../common/views/folder.tpl
    $this->entry['use_group']     = new easy_integer (null,0);       // needed for ../common/views/folder.tpl
    */
    //$this->entry['locked']        = new easy_integer (0,0,1); // 0 false, 1 true    
    
?>