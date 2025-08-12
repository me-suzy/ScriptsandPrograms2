<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_list.php');

class basic_view_listdialog extends basic_view_list {
		
	function ondblclick() {
		return "
		var result = new Object;
		result.id = this.id
		var nametd = document.getElementById('name-' + this.id);
		if (nametd) {
			result.name = nametd.innerText;
		}
		window.returnValue = result; window.close(); return false;";
	}

}
?>