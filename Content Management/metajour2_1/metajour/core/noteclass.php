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

class note extends basic {

	function note() {
		$this->basic();
		$this->addcolumn('name',0,UI_TEXT_WRAP);
		$this->removeview('createvariant');
		$this->removeview('createfuture');
		$this->removeview('approvepublish');
		$this->removeview('requestapproval');
	}
	
	function tableUpdate() {
		$t = getDbColType('note','name');
		if ($t == 'varchar(255)') {
			$db =& getDbConn();
			$db->execute("ALTER TABLE `note` CHANGE `name` `name` MEDIUMTEXT");
		}
	}

}
