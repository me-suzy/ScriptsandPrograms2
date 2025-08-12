<?php

  /**
    * $Id: fields_definition.inc.php,v 1.8 2005/08/04 19:56:32 carsten Exp $
    *
    * controls additional field definitions
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package docs
    */

	$this->entry['doc_id']                   = new easy_integer (null,0);

	$this->entry['parent']                   = new easy_integer (0,0);

	$this->entry['command']                  = new easy_string  ('');      // holding the next command to execute

    $this->entry['name']                     = new easy_string  (null,null,null,false);
    $this->entry['name']->class              = "focus";
    
	$this->entry['description']              = new easy_string  ('');      

	$this->entry['fullpath']                 = new easy_string  ('');      
    
    $this->entry['from_object_id']   = new easy_integer (null);

    //$this->entry['category']                 = new easy_integer (null,0);
    //$this->entry['category']->class          = "formular";
    
    $this->entry['use_group']                = new easy_integer (null,0);
    $this->entry['use_group']->class         = "formular";
    
    $this->entry['access']                   = new easy_string  ('-rwxrw----');
    
    $this->entry['birthday']                 = new easy_string  ('dd.mm.yyyy');
    
    $this->entry['state']                    = new easy_integer (null,-1);
    
    $this->entry['new_memo']                 = new easy_string  (null);
    
    
    $this->entry['owner']                    = new easy_integer (null,0);

    $this->entry['locked']                   = new easy_integer (0,0,1); // 0 false, 1 true    

    $this->entry['scheme']           = new easy_string  (null);
    
    $this->entry['external_link_name'] = new easy_string (null);

    $this->entry['external_link_path'] = new easy_string (null);
    
?>