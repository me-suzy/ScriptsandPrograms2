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

class comment extends basic {

	function comment() {
		$this->basic();
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('subject',F_LITERAL,'string');
		$this->addcolumn('content',F_LITERAL,'text');
	
		$this->removeview('createvariant');
	}

	function tableUpdate() {
		if (!tableExists('comment')) {
			$db =& getDbConn();
			$db->execute("
				CREATE TABLE `comment` (
				  `objectid` int(11) NOT NULL default '0',
				  `name` varchar(255) NOT NULL default '',
				  `subject` varchar(255) NOT NULL default '',
				  `content` mediumtext NOT NULL,
				  PRIMARY KEY  (`objectid`),
				  KEY `name` (`name`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;
				");
		}
	}

	function stdListCol() {
		$arr[] = 'name';
		$arr[] = 'subject';
		$arr[] = 'content';
		$arr[] = 'createdbyname';
		$arr[] = 'changed';
		$arr[] = 'objectid';
		return $arr;
	}
	
}
?>