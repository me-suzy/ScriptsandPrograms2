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

class ext_login extends basicextension {

	function ext_login() {
		$this->basicextension();
		$this->extname = 'login';
		$this->addextparam('templatename');
		$this->addextparam('templateid');
	}

	function _do() {
		$this->useTemplate('templatename','templateid','standard_password_dialog');
	}

}
?>
