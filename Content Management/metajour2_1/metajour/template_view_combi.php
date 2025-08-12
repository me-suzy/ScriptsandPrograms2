<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_combi.php');

class template_view_combi extends basic_view_combi {

	function template_view_combi() {
		$this->basic_view_combi();
		$this->submittop = 0;
	}

	function readCols() {
		parent::readCols();
		if ($this->_obj->elements[0]['htmledit'] == 0) {
			$this->_objcols['content']['inputtype'] = UI_TEXT;
			unset($this->_objcols['content']['obj']);
		}
	}

	function customButtons() {
		$result = '';
		if ($this->_obj->elements[0]['param'] != '') {
			$result = '<input class="mformsubmit" value="Opsætning" type="button" onclick="src='.$this->ModalWindowLarge('',$this->_obj->elements[0]['objectid'],'','config','jswindowclose').'; if (src != \'undefined\') document.getElementById(\'setting\').value = src;">';
		}
		return $result;
	}	

}

?>
