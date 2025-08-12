<?php
/**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: class.select.php,v 1.7 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
class select_widget {

	var $name      = '';
	var $value	   = null;
	var $options   = null;
	var $selected  = null;
	
	function select_widget ($name, $data, $options, $selected = null) {
		$this->name     = $name;
		$this->value    = $data;
		$this->options  = $options;
		$this->selected = $selected;
	}
	
	function getHTML () {
		$html = '<select name="'.$this->name.'">'."\n";
		foreach ($this->options AS $key => $value) {
			($key == $this->selected) ? $selected = " selected" : $selected = "";
			$html .= "<option value='".$key."'$selected>".$value."</option>\n";
		}
		$html.= "</select>\n";
		return $html;
	}
		
}

?>
