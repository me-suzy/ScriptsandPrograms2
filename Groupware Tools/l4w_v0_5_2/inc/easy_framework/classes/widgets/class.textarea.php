<?php
/**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: class.textarea.php,v 1.7 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
class textarea {

	var $name      = '';
	var $value	   = null; // Datarow Array	
	var $align     = 'left';
	var $tabindex  = null;
	var $cols      = null;
	var $rows      = null;
		
	function textarea ($name, $data) {
		$this->name  = $name;
		$this->value = $data;
	}
	
	function setAttributes ($att_arr) {
		foreach ($att_arr AS $attribute => $value) {
			switch($attribute){
				case 'align': 
					$this->align = $value; 
					break;
				case 'rows': 
					$this->rows = $value;
					break;
				case 'cols': 
					$this->cols = $value;
					break;
				default:
					;
			} // switch
		}
	}
	
	function getHTML () {
		($this->rows != null) ? $rows = ' rows="'.$this->rows.'"' : $rows = '';
		($this->cols != null) ? $cols = ' cols="'.$this->cols.'"' : $cols = '';		
		return '<textarea name="'.$this->name.'"'.$rows.$cols.'>'.$this->value.'</textarea>'."\n";
	}
		
}

?>
