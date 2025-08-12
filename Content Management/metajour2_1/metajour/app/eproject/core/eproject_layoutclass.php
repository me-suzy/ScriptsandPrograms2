<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eProject
 * @subpackage core
 * $Id: eproject_layoutclass.php,v 1.2 2005/01/12 03:24:08 jan Exp $
 */

require_once($system_path.'core/basicclass.php');

class eproject_layout extends basic {

	function eproject_layout() {
		$this->basic();
		$this->setobjecttype('layout');
		$this->setsubtype('layoutelement');
		$this->addcolumn('name',0,UI_STRING);
	}

}
