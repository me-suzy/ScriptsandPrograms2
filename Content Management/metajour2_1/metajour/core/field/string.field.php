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

class stringfield extends field {
	
	function formOut() {
		$s = '<input type="text" validate="'.$this->_fieldvalidate.'" name="'.$this->_fieldname.'" id="'.$this->_fieldname.'" value="'.htmlspecialchars($this->getValueOutput()).'" style="width: 350px; '.$this->_fieldstyle.'"';
		if ($this->_fieldonchange) $s .= ' onchange="'.$this->_fieldonchange.'"';
		if ($this->_fieldonfocus) $s .= ' onfocus="'.$this->_fieldonfocus.'"';
		if ($this->_readonly) $s .= ' disabled';
		$s .= '>';
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