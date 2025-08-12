<?php

/**
     * Class String (Easy)
     *
     * pre-alpha!
     *
     * Class to handle Srrings
     * @package EasyFramework
     */
     
     /**
	   * Class String (Easy)
	   *
       * pre-alpha!
       *
	   * Class to handle Srrings
	   * @package EasyFramework
	  */

class easy_string_restrict extends datatype {

	var $forbidden_chars = null;

    function easy_string_restrict ($value, $forbidden_chars) {
		if (!(is_null($value)))
    		assert (is_string($value));
		if (!is_null($forbidden_chars)) {
		    for ($i=0; $i < count ($forbidden_chars); $i++) {
				//assert (is_string ($forbidden_chars[$i]));
				assert (!empty ($forbidden_chars[$i]));
			}
		}
		$this->forbidden_chars = $forbidden_chars;		
		$this->set ($value);
	}
    
    function validate () {
		parent::validate();
		if (count ($this->forbidden_chars) > 0) {
			foreach ($this->forbidden_chars AS  $key => $forbidden) {
				assert ('substr_count ($this->data, $forbidden) == 0');
			}
		}
		return is_string($this->data); 
    }
	
}

?>
