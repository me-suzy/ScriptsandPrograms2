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

class event extends basic {

	function event() {
		$this->basic();
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('subject',0,UI_STRING);
		$this->addcolumn('content',0,UI_TEXT);
		$this->addcolumn('triggertype',0,UI_CLASS);
		$this->addcolumn('triggerevent',0,UI_COMBO);
		$this->addcolumn('msgtype1',0,UI_COMBO_MULTIPLE);
		$this->addcolumn('msgdest1',0,UI_COMBO_MULTIPLE);
		$this->addcolumn('msgtype2',0,UI_COMBO_MULTIPLE);
		$this->addcolumn('msgdest2',0,UI_USERSGROUPS_MULTIPLE);

		$this->byside2('msgtype1','msgdest1');
		$this->byside2('msgtype2','msgdest2');
	}

}