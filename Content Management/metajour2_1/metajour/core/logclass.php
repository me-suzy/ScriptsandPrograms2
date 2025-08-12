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

class log extends basic {

	function log() {
		$this->basic();
		$this->addcolumn('name',0,UI_STRING);
	
		$this->removeview('createvariant');
	}

	function tableUpdate() {
		if (!tableExists('log')) {
			$db =& getDbConn();
			$db->execute("
				CREATE TABLE `log` (
				  `objectid` int(11) NOT NULL default '0',
				  `name` varchar(255) NOT NULL default '',
				  PRIMARY KEY  (`objectid`),
				  KEY `name` (`name`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1;
				");
		}
	}

}