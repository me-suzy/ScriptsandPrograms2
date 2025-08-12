<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage extension
 */

require_once($GLOBALS['system_path'] . 'core/basicclass.php');

class sitemap extends basic {

	function sitemap() {
		$this->basic();
		$this->setobjecttable('ext_sitemap');
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('templateid',0,UI_RELATION_NODEFAULT,'template');
		$this->addcolumn('structureid',0,UI_RELATION_NODEFAULT,'structure');
	}
	
	function tableUpdate() {
		if (!tableExists('ext_sitemap')) {
			$db =& getDbConn();
			$db->execute("
				CREATE TABLE `ext_sitemap` (
					`objectid` int(11) NOT NULL default '0',
					`name` varchar(255) NOT NULL default '',
					`templateid` int(11) NOT NULL default '0',
					`structureid` int(11) NOT NULL default '0',
					PRIMARY KEY  (`objectid`),
					KEY `name` (`name`)
				);
			");
		}
	}

}
