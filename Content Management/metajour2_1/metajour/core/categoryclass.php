<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage core
 */

require_once('basicclass.php');

class category extends basic {

	function category() {
		$this->basic();
		$this->addcolumn('name');
		$this->addcolumn('datatype', 0, UI_CLASS);
	
		$this->removeview('createvariant');
		$this->removeview('createfuture');
		$this->removeview('approvepublish');
		$this->removeview('requestapproval');
		$this->removeview('category');
	}
	
	function stdListCol() {
		$result = array();
		$result[] = 'name';
		$result[] = 'datatype';
		$result[] = 'createdbyname';
		$result[] = 'changed';
		$result[] = 'objectid';
		return $result;
	}

}
