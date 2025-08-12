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

class savedsearch extends basic {

	function savedsearch() {
		$this->basic();
		$this->addcolumn('name', 0, UI_STRING);
		$this->addcolumn('class', 0, UI_CLASS);
		$this->addcolumn('content', 0, UI_HIDDEN);
		$this->removeview('tree');
		$this->removeview('view');
		$this->removeview('create');
		$this->removeview('edit');
		$this->removeview('move');
		$this->removeview('properties');
		$this->removeview('createdby');
		$this->removeview('changedby');
		$this->removeview('checkedby');
		$this->removeview('created');
		$this->removeview('changed');
		$this->removeview('checked');
		$this->removeview('language');
		$this->removeview('publish');
		$this->removeview('access');
		$this->removeview('active');
		$this->removeview('approved');
		$this->removeview('category');
		$this->removeview('readonly');
		$this->removeview('createcopy');
		$this->removeview('createvariant');
	}
	
	function tableUpdate() {
		if (!tableExists('savedsearch')) {
			$db =& getDbConn();
			$db->execute("
				CREATE TABLE `savedsearch` (
				  `objectid` int(11) NOT NULL default '0',
				  `name` varchar(255) NOT NULL default '',
				  `class` varchar(50) NOT NULL default '',
				  `content` mediumtext NOT NULL,
				  PRIMARY KEY  (`objectid`),
				  KEY `name` (`name`)
				);
				");
		}
	}
	
	
}

?>