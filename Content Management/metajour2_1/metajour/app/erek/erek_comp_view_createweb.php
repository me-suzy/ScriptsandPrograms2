<?php
/**
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eReklamation
 * @subpackage view
 * $Id: erek_comp_view_createweb.php,v 1.1 2005/02/15 12:21:44 jan Exp $
 */

require_once('erek_comp_view_create.php');

class erek_comp_view_createweb extends erek_comp_view_create {

	function parseFields() {
		$this->_objcols['description']['style'] = "width: 400px; height: 80px";
		unset($this->_objcols['itemtext']['skipstart']);
		unset($this->_objcols['itemtext']['skipend']);
		unset($this->_objcols['itemnum']['skipstart']);
		unset($this->_objcols['itemnum']['skipend']);
		unset($this->_objcols['itemnum']['labelstyle']);
		unset($this->_objcols['compunitid']['skipstart']);
		unset($this->_objcols['compunitid']['skipend']);
		unset($this->_objcols['compunitid']['labelstyle']);
		unset($this->_objcols['messageto']);
		return parent::parseFields();
	}
	
}

?>