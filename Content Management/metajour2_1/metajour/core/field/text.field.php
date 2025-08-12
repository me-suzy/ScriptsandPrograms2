<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage field
 */

require_once('field.php');

class textfield extends field {
	
	function formOut() {
		#$wrap = "wrap=off";
		#$value = htmlspecialchars($this->getValueOutput());
		$wrap = "wrap=on";
		$value = $this->getValueOutput();
		
		$s = '<textarea validate="'.$this->_fieldvalidate.'" name="'.$this->_fieldname.'" id="'.$this->_fieldname.'" style="width: 80%; height: 150px; font-family: courier-new, monospace; '.$this->_fieldstyle.'" '.$wrap.'>'.$value.'</textarea>';
		if ($this->disabledOnValue()) {
			$s .= '<script type="text/javascript">';
			$s .= 'document.getElementById(\''.$this->_fieldname.'\').disabled = true;';
			$s .= '</script>';
		}
		return $s;
	}
	
	function listOut() {
		return substr($this->getValueOutput(),0,70);
	}
	
}

?>