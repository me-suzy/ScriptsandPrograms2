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

class message extends basic {

	function message() {
		$this->basic();
		$this->addcolumn('touser',0,UI_STRING);
		$this->addcolumn('fromuser',0,UI_STRING);
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('content',0,UI_TEXT);
		$this->addcolumn('readstatus',0,UI_HIDDEN);
		$this->addcolumn('messagetype',0,UI_HIDDEN);

		$this->removeview('createvariant');
		$this->removeview('createfuture');
		$this->removeview('approvepublish');
		$this->removeview('requestapproval');
	}

}
