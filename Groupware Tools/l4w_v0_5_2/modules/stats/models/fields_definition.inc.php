<?php

  /**
    * $Id: fields_definition.inc.php,v 1.2 2005/07/26 13:23:12 carsten Exp $
    *
    * controls additional field definitions
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package      stats
    */

    // --- general ---

	$this->entry['command']          = new easy_string   ('');        // holding the next command to execute

    $this->entry['from']             = new easy_date (time()-(60*60*24*7));      

    $this->entry['till']             = new easy_date (time());      
    
    $this->entry['type']      = new easy_string ('');
    
    $this->entry['id']        = new easy_integer (null, 0);

?>