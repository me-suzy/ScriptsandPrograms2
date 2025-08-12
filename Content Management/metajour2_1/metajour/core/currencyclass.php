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

class currency extends basic {

	function currency() {
		$this->basic();
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('currency',F_LITERAL,'string');
		
		$this->addview('default');
		$this->removeview('createvariant');
	}

}
