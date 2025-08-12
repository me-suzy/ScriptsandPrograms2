<?php

  /**
    * $Id: fields_definition.inc.php,v 1.10 2005/08/04 15:48:30 carsten Exp $
    *
    * controls additional field definitions
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package      tickets
    */

    // --- general ---

	$this->entry['ticket_id']                = new easy_integer  (null,  0);

	$this->entry['command']                  = new easy_string   ('');        // holding the next command to execute

    $this->entry['use_group']                = new easy_integer (null,0);
    $this->entry['use_group']->class         = "formular";
    
    $this->entry['access']                   = new easy_string  ('-rwxrw----');

    $this->entry['owner']                    = new easy_integer (null,0);

    $this->entry['locked']                   = new easy_integer (0,0,1); // 0 false, 1 true    
    
    $this->entry['goto_tab']                 = new easy_integer (1,1);    

	$this->entry['ticket']                  = new easy_string ('');   
	$this->entry['ticket']->style = "width:500px";
	
	$this->entry['content']                  = new easy_string ('');   // for adding attachments
    $this->entry['external_link_name']       = new easy_string (null); // for adding attachments
    $this->entry['scheme']                   = new easy_string (null); // - " -
    $this->entry['external_link_path']       = new easy_string (null); // - " -

	$this->entry['parent']           = new easy_integer (0,0);

    $this->entry['category']                 = new easy_integer (null,0);
    $this->entry['category']->class          = "formular";

    $this->entry['state']            = new easy_integer (0,-1);
    $this->entry['state']->strict    = true;

    // --- tab 1 ---

    $this->entry['theme']                     = new easy_string  ('',null,null,false);
    $this->entry['theme']->set_empty_allowed (false);
    $this->entry['theme']->class              = "focus";
    $this->entry['theme']->style              = "width:500px;";
    
    $this->entry['contact']                  = new easy_select   (array (),  1, 2);
    $query = "
            SELECT contact_id, CONCAT(lastname,' ',firstname) AS name FROM ".TABLE_PREFIX."contacts 
            LEFT JOIN ".TABLE_PREFIX."metainfo mi ON mi.object_id = ".TABLE_PREFIX."contacts.contact_id
            LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups gag ON gag.id=mi.grp
            LEFT JOIN ".TABLE_PREFIX."group_details gd ON gd.id=gag.value
            WHERE object_type='contact' 
                  AND
                  gd.mandator_id=".$_SESSION['mandator']."
                  AND 
                    ( owner=".$_SESSION['user_id']."
                      OR
                      ".get_all_groups_or_statement ($_SESSION['user_id'])."
                    )  
            ORDER BY ".TABLE_PREFIX."contacts.lastname
        ";
    //echo $query;
    $res   = mysql_query ($query);
    $this->entry['contact']->fillFromResultSet ($res, $query);
    $this->entry['contact']->style = "width:200px;";
    $this->entry['contact']->class = "focus";
    $this->entry['contact']->add_rule(array ('tickets_fields_validations', 'contactGiven'), $this);	

    $this->entry['due']                       = new easy_date (time() + (60*60*24*7));      
    $this->entry['due']->set_empty_allowed (true);

    $this->entry['priority']                  = new easy_select   (array (),  1, 2);
    $query = "
        SELECT prio_id, description FROM ".TABLE_PREFIX."priorities 
        WHERE mandator=".$_SESSION['mandator']."
        ORDER BY order_nr";
    $res   = mysql_query ($query);
    $this->entry['priority']->fillFromResultSet ($res);
    $this->entry['priority']->style = "width:200px;";


    // --- todos ---
      
    $this->entry['ref_desc']                 = new easy_string  (null);
    $this->entry['ref_object_type']          = new easy_string  (null);
    $this->entry['ref_object_id']            = new easy_integer (null,0);
    $this->entry['ref_type']                 = new easy_integer (1,1);

    $this->entry['from_object_id']           = new easy_integer (null);
    
    $this->entry['new_memo']                 = new easy_string  (null);
     
?>