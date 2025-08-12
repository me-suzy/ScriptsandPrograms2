<?php

/**
     * Class String (Easy)
     *
     * Class to handle Srrings
     * @package datatypes
     */
     
     /**
	   * Class String (Easy)
	   *
	   * Class to handle Srrings
	   * @package datatypes
	  */
die ("deprecated");
class easy_sql_safe_string extends datatype {

	var $escape  		 = true;
	var $forbidden_array = array ("'",'"');
	
    function easy_sql_safe_string ($value, $allowed_array = null) {
		if (!(is_null($value)))
    		assert (is_string($value));
		if ($allowed_array != null) {
		    $this->allowed_array = $allowed_array;
		}	
		$this->data = $value;
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
