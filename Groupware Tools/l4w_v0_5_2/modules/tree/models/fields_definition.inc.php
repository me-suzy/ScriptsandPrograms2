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
    
    
    $this->entry['command']             = new easy_string  ('');
    $this->entry['id']                  = new easy_integer (null,0);
    $this->entry['parent_id']           = new easy_integer (null,0);
    $this->entry['name']                = new easy_string  ('');
    $this->entry['link']                = new easy_string  ('');
    $this->entry['frame']               = new easy_string  ('l4w_main');
    $this->entry['img']                 = new easy_string  ('');
    $this->entry['sign']                = new easy_string  ('');
    $this->entry['order_nr']            = new easy_integer (null,0);
    $this->entry['sign']                = new easy_string  ('');
    $this->entry['subtree_identifier']  = new easy_string  ('');
    //$this->entry['new_window']= new easy_string  ('');
    $this->entry['translate']           = new easy_string  ('');
    $this->entry['enabled']             = new easy_string  ('1');
    $this->entry['authorize']           = new easy_string  ('');

    $this->entry['img_path']        = new easy_string  ('');
    $this->entry['js_treenodes']    = new easy_string  ('');
    $this->entry['tabs_treenodes']  = new easy_string  ('');

    $this->entry['templates'] = new easy_select   (array (), 1, 200, 1);
    $query = "
        SELECT id, CONCAT(name,' (', link,')') as name FROM ".TABLE_PREFIX."tree GROUP by name ORDER BY name
        ";
    $res   = mysql_query ($query);
    $this->entry['templates']->fillFromResultSet ($res);
    $this->entry['templates']->style='width:220px;';
        
	/*$this->entry['collection_id'] = new easy_integer (null,0);

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
    $this->entry['components']->multiple=true;*/
    
    
   
    
?>