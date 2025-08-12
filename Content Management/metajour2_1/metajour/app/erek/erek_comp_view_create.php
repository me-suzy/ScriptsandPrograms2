<?php
/**
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eReklamation
 * @subpackage view
 * $Id: erek_comp_view_create.php,v 1.3 2005/02/15 12:21:44 jan Exp $
 */

global $system_path;
require_once($system_path.'basic_view_create.php');

class erek_comp_view_create extends basic_view_create {

	function parseFields() {
		unset($this->_objcols['compsolutionid']);
		unset($this->_objcols['compdecisionid']);
		unset($this->_objcols['credit']);
		unset($this->_objcols['cost']);
		unset($this->_objcols['comment']);
		unset($this->_objcols['compdepartmentid']);
		unset($this->_objcols['comment1']);
		return parent::parseFields();
	}
	
}

?>