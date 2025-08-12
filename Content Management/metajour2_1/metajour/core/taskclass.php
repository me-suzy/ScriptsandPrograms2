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

class task extends basic {

	function task() {
		$this->basic();
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('companyid',F_REL,'relation','company');
		$this->addcolumn('taskid',F_REL,'relation','task');
		$this->addcolumn('content',F_LITERAL,'text');
		$this->addcolumn('priority',F_LITERAL,'string');
		$this->addcolumn('status',0,UI_COMBO);
		$this->addcolumn('typecode',0,UI_COMBO);
		$this->addcolumn('budget',F_LITERAL,'decimal');
		$this->addcolumn('offer',F_LITERAL,'decimal');
		$this->addcolumn('expstart',F_LITERAL,'date');
		$this->addcolumn('expfinish',F_LITERAL,'date');
		$this->addcolumn('finished',F_LITERAL,'date');
	
		$this->removeview('createvariant');
	}

	function stdListCol() {
		$result[] = 'name';
		$result[] = 'priority';
		$result[] = 'status';
		$result[] = 'typecode';
		$result[] = 'taskid';
		return $result;
	}
	
}