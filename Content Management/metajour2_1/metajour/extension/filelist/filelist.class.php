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

class ext_filelist extends basicextension {

	function ext_filelist() {
		$this->basicextension();
		$this->extname = 'filelist';
		$this->addextparam('templatename');
	}

	function _do() {
		$this->useTemplate('templatename','templateid','standard_filelist_list');
	}

}
?>