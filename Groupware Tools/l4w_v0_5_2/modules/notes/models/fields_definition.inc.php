<?php

  /**
    * $Id: fields_definition.inc.php,v 1.14 2005/07/21 07:54:10 carsten Exp $
    *
    * controls additional field definitions
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package      notes
    */

    // --- general ---

	$this->entry['memo_id']          = new easy_integer (null,0);

	$this->entry['parent']           = new easy_integer (0,0);

	$this->entry['command']          = new easy_string   ('');        // holding the next command to execute

    $this->entry['use_group']        = new easy_integer (null,0);
    $this->entry['use_group']->class = "formular";
    
    $this->entry['access']           = new easy_string  ('-rwxrw----');

    $this->entry['owner']            = new easy_integer (null,0);

    $this->entry['locked']           = new easy_integer (0,0,1); // 0 false, 1 true    
    
    $this->entry['goto_tab']         = new easy_integer (1,1);    

	$this->entry['keyword']          = new easy_string  ('');      // holding search string

    $this->entry['state']            = new easy_integer (0,-1);
    $this->entry['state']->strict    = true;

    // references to other entries
    $this->entry['ref_desc']         = new easy_string  (null);
    $this->entry['ref_object_type']  = new easy_string  (null);
    $this->entry['ref_object_id']    = new easy_integer (null,0);

    $this->entry['scheme']           = new easy_string  (null);
    $this->entry['external_link_name'] = new easy_string (null);
    $this->entry['external_link_path'] = new easy_string (null);

	// where to go when an notes attachment was added
	$this->entry['return_to']        = new easy_string ('');

    // --- tab 1 ---

    $this->entry['headline']         = new easy_string  (null,null,null,false);
    //$this->entry['headline']->set_empty_allowed (false);
    $this->entry['headline']->class  = "focus";

	$this->entry['content']          = new easy_string  ('');      

    $this->entry['parents_headline'] = new easy_string  (null,null,null,false);

	$this->entry['foldernodes']      = new easy_string  ('');      

    $this->entry['from_object_id']   = new easy_integer (null); // check relevance
        
    $this->entry['priority']         = new easy_integer (2,1);                  // not used in notes, but for subclasses

    $this->entry['starts']           = new easy_date (time() + (60*60*24*1));   // not used in notes, but for subclasses   
    $this->entry['starts']->set_empty_allowed (true);

    $this->entry['followup']         = new easy_date ();      
    $this->entry['followup']->set_empty_allowed (true);

    $this->entry['due']              = new easy_date (time() + (60*60*24*14));  // not used in notes, but for subclasses    
    $this->entry['due']->set_empty_allowed (true);    

    $this->entry['done']             = new easy_integer (0,0,100);      
    $this->entry['done']->set_empty_allowed (true);

?>