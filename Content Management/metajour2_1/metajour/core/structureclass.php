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

class structure extends basic {

	function structure() {
		$this->basic();
		$this->setsubtype('structureelement');
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('templateid',F_LITERAL,'hidden'); #compatibility - relation to template
		$this->addcolumn('cacheallowed',F_LITERAL,'hidden');
		$this->addcolumn('cachelifetime',F_LITERAL,'hidden');

		$this->removeview('category');
	}

}
