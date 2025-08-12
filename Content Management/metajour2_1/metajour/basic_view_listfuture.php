<?php
/**
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_list.php');

class basic_view_listfuture extends basic_view_list {

	function setFilters() {
		parent::setFilters();
		$obj =& $this->_listobj;
		$obj->setfilter_future($this->data['_relval']);
	}
}

?>