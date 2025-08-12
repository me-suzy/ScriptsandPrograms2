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

class caleventtype extends basic {

	function caleventtype() {
		$this->basic();
		$this->addcolumn('name',F_LITERAL,'string');

		$this->removeview('createvariant');
	}

	function tableUpdate() {
		if (!tableExists('caleventtype')) {
			$db =& getDbConn();
			$db->execute("
				CREATE TABLE `caleventtype` (
				  `objectid` int(11) NOT NULL default '0',
				  `name` varchar(255) NOT NULL default '',
				  PRIMARY KEY  (`objectid`),
				  KEY `name` (`name`)
				);
			");
		}
	}

}