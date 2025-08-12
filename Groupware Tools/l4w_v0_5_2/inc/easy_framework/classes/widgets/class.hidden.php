<?php
/**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: class.hidden.php,v 1.7 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
class hidden {

	var $name      = '';
	var $value	   = null; // Datarow Array	
	
	function hidden ($name, $data) {
		$this->name  = $name;
		$this->value = $data;
	}
	
	function getHTML () {
		return '<input type="hidden" name="'.$this->name.'" value="'.$this->value.'">';
	}
		
}

?>
