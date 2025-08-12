<?php

  /**
    * $Id: fields_definition.inc.php,v 1.3 2005/05/27 08:00:22 carsten Exp $
    *
    * controls additional field definitions
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package      notes
    */

	$this->entry['memo_id']          = new easy_integer (null,0);

	$this->entry['parent']           = new easy_integer (0,0);

	$this->entry['command']          = new easy_string  ('');      // holding the next command to execute

    $this->entry['headline']         = new easy_string  (null,null,null,false);
    $this->entry['headline']->set_empty_allowed (false);
    $this->entry['headline']->class  = "focus";

    $this->entry['parents_headline'] = new easy_string  (null,null,null,false);

    
	$this->entry['content']          = new easy_string  ('');      

	$this->entry['foldernodes']      = new easy_string  ('');      

    $this->entry['ref_desc']         = new easy_string  (null);
    $this->entry['ref_object_type']  = new easy_string  (null);
    $this->entry['ref_object_id']    = new easy_integer (null,0);
    $this->entry['ref_type']         = new easy_integer (1,1);

    $this->entry['return_to']        = new easy_integer (null,0);

    //$this->entry['lastname']->add_rule(array ('fields_validations', 'minLength'), $this);

    $this->entry['from_object_id']   = new easy_integer (null);

    $this->entry['category']         = new easy_integer (null,0);
    $this->entry['category']->class  = "formular";
    
    $this->entry['use_group']        = new easy_integer (null,0);
    $this->entry['use_group']->class = "formular";
    
    $this->entry['access']           = new easy_string  ('-rwxrw----');
        
    $this->entry['state']            = new easy_integer (0,-1);
    $this->entry['state']->strict    = true;

    
    $this->entry['new_memo']         = new easy_string  (null);
    
    
    $this->entry['owner']            = new easy_integer (null,0);

    $this->entry['locked']           = new easy_integer (0,0,1); // 0 false, 1 true    

    $this->entry['goto_tab']         = new easy_integer (1,1,6);    
    
    $this->entry['priority']         = new easy_integer (2,1);    

    $this->entry['starts']           = new easy_date (time() + (60*60*24*1));      
    $this->entry['starts']->set_empty_allowed (true);
    //$this->entry['starts']->add_rule(array ('todos_validations', 'checkStartsDate'), $this);	

    $this->entry['due']              = new easy_date (time() + (60*60*24*14));      
    $this->entry['due']->set_empty_allowed (true);    
    //$this->entry['due']->class          = "focus";
    //$this->entry['due']->add_rule(array ('todos_validations', 'checkDueDate'), $this);	

    $this->entry['followup']         = new easy_date ();      
    $this->entry['followup']->set_empty_allowed (true);

    $this->entry['done']             = new easy_integer (0,0,100);      
    $this->entry['done']->set_empty_allowed (true);

    $this->entry['scheme']           = new easy_string  (null);
    
    $this->entry['external_link_name'] = new easy_string (null);

    $this->entry['external_link_path'] = new easy_string (null);

    // Search:
    $this->entry['search']           = new easy_string   (null,null,null,false);
    $this->entry['res']              = new easy_resource (null);
    $this->entry['hits']             = new easy_integer  (0);
    
?>