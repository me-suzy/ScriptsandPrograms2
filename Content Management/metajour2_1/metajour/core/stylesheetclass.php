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

class stylesheet extends basic {

	function stylesheet() {
		$this->basic();
		$this->setobjecttable('stylesheets');
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('content',0,UI_TEXT);
		$this->addcolumn('mapping', 0, 'inline', 'stylemapping');
		$this->addview('default');
	}

}

?>
