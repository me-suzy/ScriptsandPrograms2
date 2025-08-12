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

class payment extends basic {

	function payment() {
		$this->basic();
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('init',F_LITERAL,'string');
		$this->addcolumn('percentage',F_LITERAL,'string');
		$this->addcolumn('vatid',F_REL,'relation','vat');
		
		$this->removeview('createvariant');
	}

}
