<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage extension
 */

require_once($system_path . "extension/basicextension.class.php");

class ext_slide extends basicextension {

	function ext_slide() {
		$this->basicextension();
		$this->extname = 'slide';
		$this->addextparam('templatename');
		$this->addextparam('templateid');
	}

	function _do() {
		$this->useTemplate('templatename','templateid','standard_slide');
	}

}
?>
