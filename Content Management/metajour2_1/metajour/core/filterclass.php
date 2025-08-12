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

class filter extends basic {

	function filter() {
		$this->basic();
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('datatype', 0, UI_CLASS);
		$this->addcolumn('content',0,UI_TEXT);
		$this->addcolumn('mimetype',0,UI_STRING);
		$this->addcolumn('classtype',0,UI_HIDDEN);
		$this->addcolumn('filtertype',0,UI_COMBO);
		$this->addcolumn('filterfiletype',0,UI_STRING);
		$this->addcolumn('binfileid',0,UI_STRING);

		$this->removeview('createvariant');
		$this->removeview('createfuture');
		$this->removeview('approvepublish');
		$this->removeview('requestapproval');
		$this->removeview('category');
	}

}