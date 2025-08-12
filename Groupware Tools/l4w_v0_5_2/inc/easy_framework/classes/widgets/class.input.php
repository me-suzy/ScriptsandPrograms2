<?php

/**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: class.input.php,v 1.7 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
    class input {

	var $name      = '';
	var $value	   = null; // Datarow Array	
	var $align     = 'left';
	var $maxlength = null;
	var $size      = null;
	var $type      = 'text';
	var $tabindex  = null;
	var $datatype  = null;
	
	function input ($name, $data, $datatype = null) {
		$this->datatype = $datatype;
		if (is_null($this->datatype)) {
			$this->name  = $name;
			$this->value = $data;
		}
		else {
			$this->name  = $name;
			$this->value = $datatype->get();
		}
	}
	
	function setAttributes ($att_arr) {
		foreach ($att_arr AS $attribute => $value) {
			switch($attribute){
				case 'align': 
					$this->align = $value; 
					break;
				case 'maxlength': 
					$this->maxlength = $value;
					break;
				default:
					;
			} // switch
		}
	}
	
	function getHTML () {
		return '<input type="text" name="'.$this->name.'" value="'.$this->value.'">';
	}
		
}

?>
