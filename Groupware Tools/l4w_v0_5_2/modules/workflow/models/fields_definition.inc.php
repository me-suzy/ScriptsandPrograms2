<?php

  /**
    * $Id: fields_definition.inc.php,v 1.6 2005/07/26 13:23:12 carsten Exp $
    *
    * controls additional field definitions
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package      notes
    */

    // --- general ---

	$this->entry['command']          = new easy_string   ('');        // holding the next command to execute

    $this->entry['reference']           = new easy_string (null);

    $this->entry['state']               = new easy_string (null, -1);

    $this->entry['selected']            = new easy_integer (0,0,1);
            
    // --- tab 1 ---

    $this->entry['type']               = new easy_select   (
        array ("contact"  => translate ('contact',  null, true),
               "ticket"   => translate ('ticket', null, true),
               "todo"     => translate ('todo', null, true),
               "note"     => translate ('note', null, true)
              ),  1, 'contact');
    $this->entry['type']->style = "width:200px;";

    $this->entry['status']              = new easy_integer (0);
    
    $this->entry['name']                = new easy_string ('');
    
    $this->entry['color']               = new easy_string ('#000000');

    $this->entry['description']         = new easy_string ('');
    
    $this->entry['grp']                 = new easy_select   (array (),  4, 0);
    $query = "
            SELECT gag.id, name FROM ".TABLE_PREFIX."gacl_aro_groups gag
            LEFT JOIN ".TABLE_PREFIX."group_details gd ON gd.id = gag.value
            WHERE gd.mandator_id=".$_SESSION['mandator']." 
            ORDER BY gag.name
        ";
    $res   = mysql_query ($query);
    $this->entry['grp']->data[0] = translate ('all', null, true);
    $this->entry['grp']->fillFromResultSet ($res, $query);
    $this->entry['grp']->style = "width:200px;";
    $this->entry['grp']->multiple = true;

    $this->entry['usr']                 = new easy_select   (array (),  4, 0);
    $query = "
            SELECT id, CONCAT(lastname,' ',firstname) AS name FROM ".TABLE_PREFIX."users u
            LEFT JOIN ".TABLE_PREFIX."user_mandator um ON um.user_id = u.id
            WHERE um.mandator_id=".$_SESSION['mandator']." 
        ";
    $res   = mysql_query ($query);
    $this->entry['usr']->data[0] = translate ('all', null, true);
    $this->entry['usr']->fillFromResultSet ($res, $query);
    $this->entry['usr']->style = "width:200px;";
    $this->entry['usr']->multiple = true;

    $this->entry['state_new'] = new easy_select   (array (),  1, 0);
    $this->entry['state_new']->style = "width:200px;";

?>