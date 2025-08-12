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

class basic_view_hierarchydialogsplit extends basic_view_hierarchy {

	function basic_view_hierarchydialogsplit() {
		$this->basic_view_hierarchy();
		$this->onclickview = 'listdialog';
	}

}
?>