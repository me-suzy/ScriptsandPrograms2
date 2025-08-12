<?php
/**
*
* pre-alpha!
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/

/**
*
* @version      $Id: easy_array.class.php,v 1.6 2005/08/06 06:57:08 carsten Exp $
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/
class easy_array extends datatype {

    function easy_array () {
    	$this->data = array ();	
    }

	function add_entry ($entry) {
		$this->data[] = $entry;
	}
	
	function get_entry ($i) {
		return $this->data[$i];	
	}
	
	function count () {
		return count ($this->data);	
	}
    
    function validate () {
        if (!$this->NULL_ALLOWED  && $this->data = null) return false;
        if (!$this->EMPTY_ALLOWED && $this->data = "")   return false;
        return true; //is_array($this->data); 
    }
    
    function format ($data) {
    	
    	$output = "";
    	//print_r($data);
    	//die();
    	foreach ($data as $key => $element) {
    		$output .= $element."<br>";	
    	}
    	return $output;
    }

}

?>
