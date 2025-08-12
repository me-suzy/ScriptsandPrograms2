<?php

  /**
    * $Id: fields_definition.inc.php,v 1.4 2005/05/27 08:00:22 carsten Exp $
    *
    * controls additional field definitions
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package      notes
    */

	$this->entry['command']          = new easy_string  ('');      // holding the next command to execute

    $this->entry['language_name']    = new easy_string  (null,null,null,false);
    $this->entry['language_name']->set_empty_allowed (false);
    $this->entry['language_name']->class  = "focus";

    $this->entry['translations']     = new easy_resource (null);
    $this->entry['translation']      = new easy_string  ('');
    $this->entry['mykey']            = new easy_string  ('');
    
    $this->entry['languages']        = new easy_resource (null);

    $this->entry['lang_id']          = new easy_integer (null,0);

?>