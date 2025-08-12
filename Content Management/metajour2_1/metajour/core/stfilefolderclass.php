<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage core
 */
require_once('staticfolderclass.php');

class stfilefolder extends staticfolder {

	function stfilefolder() {
		$this->staticfolder();
	}

	function getRoot() {
		return $this->userhandler->getDirStfilebinfile();
	}

}