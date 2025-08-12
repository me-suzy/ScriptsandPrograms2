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

class basic_view_listprint extends basic_view_list {

	function getColHeaderSortSymbols($colname) {
		return '';
	}

	function toolBar() {
		return '';
	}

	function getRowEventHandler() {
		return '';
	}
	
	function view() {
		$this->showinfocols = false;
		return parent::view();
	}

	function setLimits() {
	}
	
}

?>