<?php
/**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: class.checkbox.php,v 1.7 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */

class easy_checkbox {

	var $name    = null;
	var $value   = null;
	var $checked = null;
	
	function easy_checkbox ($name, $value = "on", $checked = false) {
		$this->name    = $name;
		$this->value   = $value;
		$this->checked = $checked;
	}

	function preSelect (&$params) {
		if (isset($params[$this->name])) {	
			$this->checked = true;
			return 1;	
		}
		else {
			$this->checked = false;
			return 0;
		}
	}
	
	function setSelect ($checked) {
		assert ('is_bool ($this->checked)');
		$this->checked = $checked;	
	}
	
	function toString () {
		assert ('is_bool ($this->checked)');
		($this->checked == false) ? $checked = "" : $checked = " checked";
		$ret = '<input type=checkbox name="'.$this->name.'" ';
		if ($this->value != null)
			$ret.= 'value="'.$this->value.'" ';
		$ret.= $checked.'>';
		return $ret;
	}
	
	
}

?>
