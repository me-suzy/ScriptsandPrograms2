<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_edit.php');

class basic_view_view extends basic_view_edit {

	function basic_view_view() {
		$this->basic_view_edit();
		$this->_editable = false;
	}

	function submitButtons() {
	}

}

?>