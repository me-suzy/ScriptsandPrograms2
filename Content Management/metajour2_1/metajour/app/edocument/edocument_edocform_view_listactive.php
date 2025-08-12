<?php
/**
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eProject
 * @subpackage view
 * $Id: edocument_edocform_view_listactive.php,v 1.1 2005/02/04 06:20:44 jan Exp $
 */

require_once($system_path.'basic_view_list.php');

class edocument_edocform_view_listactive extends basic_view_list {

	function setFilters() {
		parent::setFilters();
		$obj =& $this->_listobj;
		$obj->setfilter_search('status', CASE_OPEN, EQUAL);
	}

	function loadLanguage() {
		parent::loadLanguage();
		$this->loadLangFile('edocform_view_listactive');
	}

}

?>