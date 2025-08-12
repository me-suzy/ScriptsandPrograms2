<?php

/**
     * Class String (Easy)
     *
     * pre-alpha!
     * Class to handle Srrings
     * @package EasyFramework
     */
     
     /**
	   * Class String (Easy)
	   *
	   * Class to handle Srrings
	   * @package EasyFramework
	  */

class easy_escaped_string extends datatype {

    function easy_escaped_string ($value) {
		if (!(is_null($value)))
    		assert (is_string($value));
		$this->data = mysql_escape_string($value);
	}
	
	function set ($value) {
	    $this->data = mysql_escape_string($value);    	
        if (!$this->validate()) {
        	assert ('$this->validate()');
        	die ("Script stopped because of validation error");
    	}
    }
	
	function get () {
        $this->validate();
        return mysql_escape_string($this->data);
    }
    
    function validate () {
		parent::validate();
		if ($this->allowed_array != null && !$this->NULL_ALLOWED) {
			assert ('in_array ($this->data, $this->allowed_array)');
		}
        return is_string($this->data); 
    }

}

?>
