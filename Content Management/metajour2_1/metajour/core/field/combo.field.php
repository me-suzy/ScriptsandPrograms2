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

class combofield extends field {
	
	function formOut() {
		$s = '<select validate="'.$this->_fieldvalidate.'" name="'.$this->_fieldname.'" style="width:260px; '.$this->_fieldstyle.'">';
		if (is_array($this->_fieldcomboarray)) {
			foreach ($this->_fieldcomboarray as $key => $val) {
			$selected = '';
			if ($this->_fieldvalue == $key) $selected = ' SELECTED';
				$s .= '<option value="'.$key.'"'.$selected.'>'.$val.'</option>';
			}
		}
		$s .= '</select>';
		return $s;
	}
	
	function listOut() {
		return $this->_fieldcomboarray[$this->_fieldvalue];
	}

	function viewOut() {
		return $this->_fieldcomboarray[$this->_fieldvalue];
	}
	
}

?>