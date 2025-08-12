<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_create.php');

class profile_view_create extends basic_view_create {

	function unsetAllExcept($arr) {
		foreach ($this->_objcols as $key => $val) {
			if (!in_array($key, $arr)) unset($this->_objcols[$key]);
		}
	}

	function parseFields() {
		$this->unsetAllExcept(array('name'));
		return parent::parseFields();
	}

	function view() {
		$this->ret = 'combi';
		return parent::view();
	}
	
}

?>