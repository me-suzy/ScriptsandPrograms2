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

class freight extends basic {

	function freight() {
		$this->basic();
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('init',0,UI_STRING);
		$this->addcolumn('perweight',0,UI_STRING);
		$this->addcolumn('vatid',0,UI_RELATION,'vat');
		
		$this->removeview('createvariant');
		$this->removeview('createfuture');
		$this->removeview('approvepublish');
		$this->removeview('requestapproval');
	}

}
