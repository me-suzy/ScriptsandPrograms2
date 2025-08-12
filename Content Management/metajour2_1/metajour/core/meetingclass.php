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

class meeting extends basic {

	function meeting() {
		$this->basic();
		$this->addcolumn('companyid',F_REL,'relation','company');
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('visitdate',F_LITERAL,'date');
		$this->addcolumn('content',F_LITERAL,'text');

		$this->removeview('createvariant');
	}

}