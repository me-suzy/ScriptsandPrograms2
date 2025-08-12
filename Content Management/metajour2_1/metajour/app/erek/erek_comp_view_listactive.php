<?php
/**
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eReklamation
 * @subpackage view
 * $Id: erek_comp_view_listactive.php,v 1.5 2005/04/07 06:05:31 jan Exp $
 */

global $system_path;
require_once($system_path.'basic_view_list.php');

class erek_comp_view_listactive extends basic_view_list {

	function setFilters() {
		parent::setFilters();
		$obj =& $this->_listobj;
		$obj->setfilter_search('status', CASE_OPEN, EQUAL);
	}

	function loadLanguage() {
		parent::loadLanguage();
		$this->loadLangFile('comp_view_listactive');
	}

	function erek_comp_view_listactive() {
		$this->basic_view_list();
		$this->_preset = true;
	}

}

?>