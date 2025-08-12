<?php

/**
     * Class Query_tring (Easy)
     *
     * pre-alpha!
     *
     * Class to handle Query Strings
     * @package EasyFramework
     */
     
     /**
	   * Class Query_String (Easy)
	   *
	   * Class to handle Query_Strings
	   * @package EasyFramework
	  */

class query_string extends easy_string {

    var $params = null;
    
    function query_string ($value) {
        if ($value != null) {
    	    assert (is_string($value));
		    $this->data = $value;
	        $this->validate();
            parse_str ($this->data,$params);    
        }
    }
        
    function validate () { 
    	if (!$this->NULL_ALLOWED  && $this->data = null) return false;
        // muss vom Typ String sein
        if (!is_string($this->data)) return false;
        // darf folgende Zeichen / Zeichenfolgen nicht enthalten
        //(...)
        return true;        
    }

}

?>
