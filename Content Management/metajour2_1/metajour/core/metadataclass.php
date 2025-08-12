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

class metadata extends basic {

	function metadata() {
		$this->basic();
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('description',0,UI_STRING);
		$this->addcolumn('keyword',0,UI_STRING);
		$this->addcolumn('copyright',0,UI_STRING);
		$this->addcolumn('publisher',0,UI_STRING);
		$this->addview('default');
	}

}
