<?php

  /**
    * $Id: fields_definition.inc.php,v 1.4 2005/07/14 06:01:22 carsten Exp $
    *
    * controls additional field definitions
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package      development
    */

	$this->entry['command']      = new easy_string  ('');      // holding the next command to execute

    $this->entry['name']         = new easy_string  (null,null,null,false);
    $this->entry['name']->set_empty_allowed (false);
    $this->entry['name']->class  = "focus";
    $this->entry['name']->style  = "width:500px";
    
	$this->entry['description']  = new easy_string  ('');      
    $this->entry['description']->style  = "width:500px";

	//$this->entry['type']         = new easy_string  ('');      
    $this->entry['type']         = new easy_select   (
        array ("blank"      => translate ('blank',  null, true),
               "collections" => translate ('collections', null, true)
              ),  1, 'collections');

	//$this->entry['version']      = new easy_string  ('0.1.0');      

    $this->entry['version_main']   = new easy_integer (0,0);
    $this->entry['version_main']->set_empty_allowed (false); 
    $this->entry['name']->class  = "focus";
         
    $this->entry['version_sub']    = new easy_integer (0,0);
    $this->entry['version_sub']->set_empty_allowed (false);      
    $this->entry['version_sub']->class  = "focus";
    
    $this->entry['version_detail'] = new easy_integer (1,0);      
    $this->entry['version_detail']->set_empty_allowed (false);
    $this->entry['version_detail']->class  = "focus";

	$this->entry['author']       = new easy_string  ('');      

	$this->entry['copyright']    = new easy_string  ('');      

	$this->entry['package']      = new easy_string  ('');      

	$this->entry['sql']          = new easy_string  ('');      

	$this->entry['schema']       = new easy_string  ('');   
	$this->entry['schema']->set_empty_allowed (false);      
    $this->entry['schema']->class  = "focus";   

?>