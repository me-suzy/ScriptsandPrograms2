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

class checkboxfield extends field {
	
	function formOut() {
		$s = '<input type="hidden" name="'.$this->_fieldname.'" value="0">';
		$s .= '<input type="checkbox" validate="'.$this->_fieldvalidate.'" name="'.$this->_fieldname.'" value="1"';
		if ($this->_fieldvalue == 1) $s .= ' checked';
		$s .= ' style="'.$this->_fieldstyle.'">';
		return $s;
	}
	
	function listOut() {
		if ($this->_fieldvalue == 1) {
			return '<img src="'.$this->userhandler->getSystemUrl().'image/view/infocol_default.gif">';
		} else {
			return '';
		}
	}
	
	function viewOut() {
		if ($this->_fieldvalue == 1) {
			return '<img src="'.$this->userhandler->getSystemUrl().'image/view/infocol_default.gif">';
		} else {
			return '';
		}
	}
	
}

?>