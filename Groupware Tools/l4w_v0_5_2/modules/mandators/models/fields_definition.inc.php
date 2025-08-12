<?php

  /**
    * $Id: fields_definition.inc.php,v 1.2 2005/07/20 07:18:41 carsten Exp $
    *
    * controls additional field definitions
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package mandators
    */

    // --- general ---

	$this->entry['mandator_id']              = new easy_integer  (null,  0);

	$this->entry['command']                  = new easy_string   ('');        // holding the next command to execute
    
    $this->entry['goto_tab']                 = new easy_integer (1,1);    

    // --- tab 1 ---

    $this->entry['name']           = new easy_string  ('',50);
    $this->entry['name']->class    = "focus";    

    $this->entry['tree_root']      = new easy_integer (1,1);    

    $this->entry['description']    = new easy_string  ('',200);

    // name of file with alternative code for acl.inc.php
    $this->entry['acl_inc_php']    = new easy_string   ('',30);

?>