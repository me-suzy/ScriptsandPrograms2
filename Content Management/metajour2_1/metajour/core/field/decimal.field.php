<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage field
 */

require_once('string.field.php');

class decimalfield extends stringfield {

	function convertToDatabase($value) {
		# LOCALE 'DA'
		$value = str_replace('.','',$value);
		$value = str_replace(',','.',$value);
		return $value;
	}	

	function getValueOutput() {
		# LOCALE 'DA'
		return number_format($this->_fieldvalue, 2, ',', '.');
	}
	
}

?>