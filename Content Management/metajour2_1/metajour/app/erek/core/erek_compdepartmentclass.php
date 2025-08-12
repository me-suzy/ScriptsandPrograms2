<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eReklamation
 * @subpackage core
 * $Id: erek_compdepartmentclass.php,v 1.3 2005/02/15 12:22:25 jan Exp $
 */

global $system_path;
require_once($system_path.'core/basicclass.php');

class erek_compdepartment extends basic {

	function erek_compdepartment() {
		$this->basic();
		$this->setobjecttype('compdepartment');
		$this->addcolumn('name',0,UI_STRING);
		$this->removeview('createvariant');
		$this->removeview('createfuture');
		$this->removeview('approvepublish');
		$this->removeview('requestapproval');
		$this->removeview('access');
		$this->removeview('category');
	}

}
