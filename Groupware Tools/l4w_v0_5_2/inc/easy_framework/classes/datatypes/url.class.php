<?php

/**
     * Class String (Easy)
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

class url_string extends datatype {

	var $allowed_array  = null;

    function url_string ($value, $allowed_array = null, $strict = false) {

    	parent::datatype($strict);
		if (!(is_null($value)))
    		assert (is_string($value));
		if ($allowed_array != null) {
		    $this->allowed_array = $allowed_array;
		}	
		$this->data = $value;
	}
    
    function validate () {
		
    	if (!parent::validate()) return false;
		
		$validated = true;

		if ($this->strict && $this->allowed_array != null)
			assert ('in_array ($this->data, $this->allowed_array)');
		elseif ($this->allowed_array != null) { 
			if (!$validated = in_array ($this->data, $this->allowed_array)) {
				$this->error = 'VALUE NOT ALLOWED'; 
			}
		}
        return $validated;
    }
	
}

?>
