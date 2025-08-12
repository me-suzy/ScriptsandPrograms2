<?php

  /**
    * $Id: fields_definition.inc.php,v 1.2 2005/07/13 13:59:24 carsten Exp $
    *
    * controls additional field definitions
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package mandators
    */

    // --- general ---

	//$this->entry['mandator_id']              = new easy_integer  (null,  0);

	$this->entry['command']                  = new easy_string   ('');        // holding the next command to execute
    
	$this->entry['datagrid']      = new easy_string   ('');        // holding the name of the datagrid ("categories", ...)
	$this->entry['datagrid_id']   = new easy_integer   (0,0);      // holding the id of the datagrid 
	$this->entry['column_id']     = new easy_integer   (0,0);      // holding the id of the datagrid column
	
	$this->entry['column_name']   = new easy_string   ('');        
	$this->entry['description']   = new easy_string   ('');        
	$this->entry['width']         = new easy_string   ('');        

	$this->entry['visible']       = new easy_string   ('');      
	$this->entry['is_primary']    = new easy_string   ('');      
	
	$this->entry['order_nr']      = new easy_integer  (0,0);        

    $this->entry['searchable']    = new easy_string   ('');      
	$this->entry['sortable']      = new easy_string   ('');      
	
	$this->entry['section'] = new easy_string   ('');        // holding the name of the datagrid section ("CategoryManager", ...)
	

    //$this->entry['goto_tab']                 = new easy_integer (1,1);    

    // --- tab 1 ---

    //$this->entry['name']           = new easy_string  ('',50);
    //$this->entry['name']->class    = "focus";
    //$this->entry['name']->set_empty_allowed (false);

    //$this->entry['tree_root']      = new easy_integer (1,1);    

    //$this->entry['description']    = new easy_string  ('',200);

    // name of file with alternative code for acl.inc.php
    //$this->entry['acl_inc_php']    = new easy_string   ('',30);

?>