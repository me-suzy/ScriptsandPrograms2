<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_delete.php');

class customform_view_delete extends basic_view_delete {

	function returnView() {
		return $this->returnviewpost('delete,jsopenertopmenureload,jsopenertreereload');
	}

}

?>