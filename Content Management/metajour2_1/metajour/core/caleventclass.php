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

class calevent extends basic {

	function calevent() {
		$this->basic();
		$this->addcolumn('caleventtypeid',F_REL,'relation','caleventtype');
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('content',F_LITERAL,'html');
		$this->addcolumn('begindate',F_LITERAL,'date');
		$this->addcolumn('enddate',F_LITERAL,'date');
		$this->addcolumn('hidedate',F_LITERAL,'checkbox');
		$this->addcolumn('documentid',F_REL,'relationcreate','document');

		$this->removeview('createvariant');
	}

	function tableUpdate() {
		if (!tableExists('calevent')) {
			$db =& getDbConn();
			$db->execute("
				CREATE TABLE `calevent` (
				  `objectid` int(11) NOT NULL default '0',
				  `name` varchar(255) NOT NULL default '',
				  `caleventtypeid` INT(11) NOT NULL default '',
				  `content` MEDIUMTEXT NOT NULL default '',
				  `begindate` DATE NOT NULL default '',
				  `enddate` DATE NOT NULL default '',
				  PRIMARY KEY  (`objectid`),
				  KEY `name` (`name`)
				);
			");
		}
		if (!colExists($this->objecttable, 'hidedate')) {
			$db =& getDbConn();
			$db->execute('ALTER TABLE `'.$this->objecttable.'` ADD `hidedate` INT(11) NOT NULL');
			$db->execute('ALTER TABLE `'.$this->objecttable.'` ADD `documentid` INT(11) NOT NULL');
		}
	}

	function stdListCol() {
		$arr[] = 'caleventtypeid';
		$arr[] = 'name';
		$arr[] = 'begindate';
		$arr[] = 'enddate';
		return $arr;
	}

}