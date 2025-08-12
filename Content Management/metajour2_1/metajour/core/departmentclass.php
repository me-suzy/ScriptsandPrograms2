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

class department extends basic {

	function department() {
		$this->basic();
		$this->addcolumn('name',F_LITERAL,'string');

		$this->removeview('createvariant');
	}

	function initLayout() {
		parent::initLayout();
		$this->addRelationDatatype('employee','objectid','departmentid');
	}

	function tableUpdate() {
		if (!tableExists('department')) {
			$db =& getDbConn();
			$db->execute("
				CREATE TABLE `department` (
				  `objectid` int(11) NOT NULL default '0',
				  `name` varchar(255) NOT NULL default '',
				  PRIMARY KEY  (`objectid`),
				  KEY `name` (`name`)
				);
			");
		}
	}

}