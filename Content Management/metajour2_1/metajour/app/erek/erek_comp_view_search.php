<?php
/**
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eReklamation
 * @subpackage view
 * $Id: erek_comp_view_search.php,v 1.1 2005/02/15 12:21:44 jan Exp $
 */

global $system_path;
require_once($system_path.'basic_view_search.php');

class erek_comp_view_search extends basic_view_search {

	function erek_comp_view_search() {
		$this->basic_view_search();
		$this->_preset = true;
	}

}

?>