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

class hiddenfield extends field {

	function hiddenfield() {
		parent::field();
		$this->setVisibility(false);
	}
		
	function formOut() {
		return '<input type="hidden" validate="'.$this->_fieldvalidate.'" name="'.$this->_fieldname.'" value="'.$this->_fieldvalue.'">';
	}
	
}

?>