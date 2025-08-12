<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eReklamation
 * @subpackage core
 * $Id: edocument_edoccorrectionclass.php,v 1.1 2005/02/04 05:04:13 jan Exp $
 */

global $system_path;
require_once($system_path.'core/basicclass.php');

class edocument_edoccorrection extends basic {

	function edocument_edoccorrection() {
		$this->basic();
		$this->setobjecttype('edoccorrection');
		$this->addcolumn('name',0,UI_STRING);
		$this->removeview('createvariant');
		$this->removeview('access');
		$this->removeview('category');
	}

}
