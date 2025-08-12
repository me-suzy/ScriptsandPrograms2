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
	   * Class to handle Srrings
	   * @package EasyFramework
	  */

class easy_resource extends datatype {

    function easy_resource ($value) {
		$this->data = $value;
	}
    
    function validate () {
		
		$validated = true;

        return $validated;
    }
	
}

?>
