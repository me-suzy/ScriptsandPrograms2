<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage extension
 */

require_once($system_path."core/basicclass.php");

class cform extends basic {

	function cform() {
		$this->basic();
		$this->allowduplicate = false;
		$this->setobjecttable('ext_cform');
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('otype',0,UI_CLASS);
		$this->addcolumn('formtype',0,UI_COMBO);
		$this->addcolumn('templateid',0,UI_RELATION_NODEFAULT,'template');
	}

	function tableUpdate() {
		if (!tableExists('ext_cform')) {
			$db =& getDbConn();
			$db->execute("
				CREATE TABLE `ext_cform` (
				  `objectid` int(11) NOT NULL default '0',
				  `name` varchar(255) NOT NULL default '',
				  `otype` varchar(255) NOT NULL default '',
				  `formtype` int(11) NOT NULL default '0',
				  `templateid` int(11) NOT NULL default '0',
				  PRIMARY KEY  (`objectid`),
				  KEY `name` (`name`)
				)
				");
		}
	}
	
}
