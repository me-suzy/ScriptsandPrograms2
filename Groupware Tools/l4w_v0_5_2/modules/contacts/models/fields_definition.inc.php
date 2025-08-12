<?php

  /**
    * $Id: fields_definition.inc.php,v 1.17 2005/07/22 07:06:45 carsten Exp $
    *
    * controls additional field definitions
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package      contacts
    */

    // --- general ---

	$this->entry['contact_id']               = new easy_integer  (null,  0);

	$this->entry['command']                  = new easy_string   ('');        // holding the next command to execute

    $this->entry['use_group']                = new easy_integer (null,0);
    $this->entry['use_group']->class         = "formular";
    
    $this->entry['access']                   = new easy_string  ('-rwxrw----');

    $this->entry['owner']                    = new easy_integer (null,0);

    $this->entry['locked']                   = new easy_integer (0,0,1); // 0 false, 1 true    
    
    $this->entry['goto_tab']                 = new easy_integer (1,1);    

	$this->entry['content']                  = new easy_string ('');   // for adding attachments
    $this->entry['external_link_name']       = new easy_string (null); // - " -
    $this->entry['scheme']                   = new easy_string (null); // - " -
    $this->entry['external_link_path']       = new easy_string (null); // - " -
    
    // --- tab 1 ---

    $this->entry['salutation']               = new easy_select   (
        array ("Mr"  => translate ('Mr',  null, true),
               "Mrs" => translate ('Mrs', null, true),
               "n/a" => translate ('n/a', null, true)
              ),  1, 'Mr');
    $this->entry['salutation']->class        = "formular_small";

    $this->entry['title']                    = new easy_string   ('',     10);
    $this->entry['title']->class             = "formular";

    $this->entry['firstname']                = new easy_string   ('',   50);
    $this->entry['firstname']->class         = "formular";

    $this->entry['lastname']                 = new easy_string   ('',   50);
    $this->entry['lastname']->class          = "focus";
    $this->entry['lastname']->set_empty_allowed (false);
    $this->entry['lastname']->add_rule(array ('contacts_fields_validations', 'minLength'), $this);	

    $this->entry['email']                    = new easy_string   (null,   60);
	$this->entry['email']->class             = "formular";

    $this->entry['street']                   = new easy_string   ('',    100);
    $this->entry['street']->class            = "formular";
    
    $this->entry['zipcode']                  = new easy_string   ('',     10);
    $this->entry['zipcode']->class           = "formular";

    $this->entry['city']                     = new easy_string   ('',    100);
    $this->entry['city']->class              = "formular";

    $this->entry['phone_company1']           = new easy_string   (null,   30);
    $this->entry['phone_company1']->class    = "formular";

    $this->entry['mobile_phone']             = new easy_string   (null,   30);
    $this->entry['mobile_phone']->class      = "formular";

    $this->entry['fax']                      = new easy_string  (null);
    $this->entry['fax']->class               = "formular";

    $this->entry['phone_private1']           = new easy_string   (null,   30);
    $this->entry['phone_private1']->class    = "formular";

    // --- tab 2 ---

    $this->entry['salutation_letter']        = new easy_string   (null,  100);
    $this->entry['salutation_letter']->class = "formular";

    $this->entry['company']                  = new easy_integer  (null,0);
    $this->entry['company']->class           = "formular";

    $this->entry['department']               = new easy_string    (null,  50);
    $this->entry['department']->class        = "formular";
    
    $this->entry['function']                 = new easy_string    (null,  50);
    $this->entry['function']->class          = "formular";
   
    $this->entry['homepage']                 = new easy_string    (null, 100);
    $this->entry['homepage']->class          = "formular";

    $this->entry['phone_company2']           = new easy_string    (null,  30);
    $this->entry['phone_company2']->class    = "formular";

    $this->entry['phone_private2']           = new easy_string    (null,  30);
    $this->entry['phone_private2']->class    = "formular";
        
    $this->entry['country']                  = new easy_select   (array (),  1, 82);
    $query = "SELECT id, country FROM ".TABLE_PREFIX."countries";
    $res   = mysql_query ($query);
    $this->entry['country']->fillFromResultSet ($res);
    
    //$this->entry['country']->class         = "formular_small";

    $this->entry['freetext1']                = new easy_string  (null);
    $this->entry['freetext1']->class         = "formular";
    
    $this->entry['freetext2']                = new easy_string  (null);
    $this->entry['freetext2']->class         = "formular";
    
    $this->entry['freetext3']                = new easy_string  (null);
    $this->entry['freetext3']->class         = "formular";

	//$this->entry['further_emails']           = new easy_string  (null);

    $this->entry['further_emails']           = new easy_select   (array (),  1, null);
    $this->entry['further_emails']->class    = "formular";    

    /*$this->entry['category']                  = new easy_select   (array (),  1, 82);
	$gen_group = get_main_group($_SESSION['user_id']);
	$query = "SELECT * FROM ".TABLE_PREFIX."categories WHERE id=1 OR grp!=1 ORDER BY id";
    $res   = mysql_query ($query);
    $this->entry['category']->fillFromResultSet ($res);
    */

    // easy_date?
    $this->entry['birthday']                 = new easy_string  ('dd.mm.yyyy');


    
    
	// todos:
    
    $this->entry['remark']                   = new easy_string  (null);
    $this->entry['remark']->class            = "formular";
    
    $this->entry['state']                    = new easy_integer (null,-1);
    
    $this->entry['new_memo']                 = new easy_string  (null);
        
    
    $this->entry['ref_desc']                 = new easy_string  (null);
    $this->entry['ref_object_type']          = new easy_string  (null);
    $this->entry['ref_object_id']            = new easy_integer (null,0);
    $this->entry['ref_type']                 = new easy_integer (1,1);

?>