<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_hierarchy.php');

class basic_view_hierarchydialog extends basic_view_hierarchy {

	function basic_view_hierarchydialog() {
		parent::basic_view_hierarchy();
		$this->onclickview = false;
	}
			
	function ondblclick() {
		return "
		var result = new Object;
		result.id = ".$this->tree[$this->cnt][2].";
		result.name = '".$this->tree[$this->cnt][1]."';
		window.returnValue = result; window.close(); return false;";
	}

}
?>