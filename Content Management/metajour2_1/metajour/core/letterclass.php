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

class letter extends basic {

	function letter() {
		$this->basic();
		$this->addcolumn('contactid',F_REL,'relation','contact');
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('content',F_LITERAL,'text');

		$this->removeview('createvariant');
	}

}