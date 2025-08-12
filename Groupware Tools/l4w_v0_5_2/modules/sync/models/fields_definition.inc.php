<?php

  /**
    * $Id: fields_definition.inc.php,v 1.1 2005/02/09 13:19:30 carsten Exp $
    *
    * controls additional field definitions
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package sync
    */

	//$this->entry['memo_id']                  = new easy_integer (null,0);

	$this->entry['command']                  = new easy_string  ('');      // holding the next command to execute

    $this->entry['use_user']                 = new easy_string  ($_SESSION['login']);
    $this->entry['use_pass']                 = new easy_string  ($_SESSION['passwort']);
    $this->entry['remote']                   = new easy_string  ('http://localhost/leads4web4II/');
    
    //$this->entry['headline']->set_empty_allowed (false);
    //$this->entry['headline']->class              = "focus";
    
?>