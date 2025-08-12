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

class filelist extends basic {

	function filelist() {
		$this->basic();
		$this->allowduplicate = false;
		$this->setobjecttable('ext_filelist');
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('title',F_LITERAL,'string');
		$this->addcolumn('folderid',F_REL,'treeselect','folder');
		$this->addcolumn('thumbnail',F_LITERAL,'checkbox');
		$this->addcolumn('templateid',F_REL,'relation','template');

		$this->removeview('createvariant');
		$this->removeview('category');
		$this->removeview('access');
	}

	function tableUpdate() {
		if (!tableExists('ext_filelist')) {
			$db =& getDbConn();
			$db->execute("
				CREATE TABLE `ext_filelist` (
				  `objectid` int(11) NOT NULL default '0',
				  `name` varchar(255) NOT NULL default '',
				  `title` varchar(255) NOT NULL default '',
				  `folderid` int(11) NOT NULL default '0',
				  `thumbnail` int(11) NOT NULL default '0',
				  `templateid` int(11) NOT NULL default '0',
				  PRIMARY KEY  (`objectid`),
				  KEY `name` (`name`)
				)
				");
		}
	}
	
}
?>