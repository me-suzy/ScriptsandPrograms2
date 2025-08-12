<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eReklamation
 * @subpackage core
 * $Id: erek_compdecisionclass.php,v 1.3 2005/02/15 12:22:25 jan Exp $
 */

global $system_path;
require_once($system_path.'core/basicclass.php');

class erek_compdecision extends basic {

	function erek_compdecision() {
		$this->basic();
		$this->setobjecttype('compdecision');
		$this->addcolumn('name',0,UI_STRING);
		$this->removeview('createvariant');
		$this->removeview('createfuture');
		$this->removeview('approvepublish');
		$this->removeview('requestapproval');
		$this->removeview('access');
		$this->removeview('category');
	}

}
