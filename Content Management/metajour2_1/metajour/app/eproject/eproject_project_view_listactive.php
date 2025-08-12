<?php
/**
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eProject
 * @subpackage view
 * $Id: eproject_project_view_listactive.php,v 1.3 2005/02/15 12:20:39 jan Exp $
 */

require_once($system_path.'basic_view_list.php');

class eproject_project_view_listactive extends basic_view_list {

	function setFilters() {
		parent::setFilters();
		$obj =& $this->_listobj;
		$obj->setfilter_search('status', CASE_OPEN, EQUAL);
	}

	function eproject_project_view_listactive() {
		$this->basic_view_list();
		$this->_preset = true;
	}
}

?>