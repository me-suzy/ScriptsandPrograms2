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

class gallery extends basic {

	function gallery() {
		$this->basic();
		$this->allowduplicate = false;
		$this->setobjecttable('ext_gallery');
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('title',F_LITERAL,'string');
		$this->addcolumn('folderid',F_REL,'treeselect','folder');
		$this->addcolumn('xnum',F_LITERAL,'string');
		$this->addcolumn('ynum',F_LITERAL,'string');
		$this->addcolumn('thumbsize',F_LITERAL,'string');
		$this->addcolumn('mediumsize',F_LITERAL,'string');
		$this->addcolumn('usemedium',F_LITERAL,'checkbox');
		$this->addcolumn('mediumnewwindow',F_LITERAL,'checkbox');
		$this->addcolumn('usefull',F_LITERAL,'checkbox');
		$this->addcolumn('linktofull',F_LITERAL,'checkbox');
		$this->addcolumn('fullnewwindow',F_LITERAL,'checkbox');
		$this->addcolumn('addcomment',F_LITERAL,'checkbox');
		$this->addcolumn('listcomment',F_LITERAL,'checkbox');
		$this->addcolumn('commentpos',F_COMBO,'combo');
		$this->addcolumn('templateid_index',F_REL,'relation','template');
		$this->addcolumn('templateid_medium',F_REL,'relation','template');
		$this->addcolumn('templateid_full',F_REL,'relation','template');

		$this->removeview('createvariant');
		$this->removeview('category');
		$this->removeview('access');
	}

	function tableUpdate() {
		if (!tableExists('ext_gallery')) {
			$db =& getDbConn();
			$db->execute("
				CREATE TABLE `ext_gallery` (
				  `objectid` int(11) NOT NULL default '0',
				  `name` varchar(255) NOT NULL default '',
				  `title` varchar(255) NOT NULL default '',
				  `folderid` int(11) NOT NULL default '0',
				  `xnum` int(11) NOT NULL default '0',
				  `ynum` int(11) NOT NULL default '0',
				  `thumbsize` int(11) NOT NULL default '0',
				  `mediumsize` int(11) NOT NULL default '0',
				  `usemedium` int(11) NOT NULL default '1',
				  `usefull` int(11) NOT NULL default '0',
				  `linktofull` int(11) NOT NULL default '1',
				  `addcomment` int(11) NOT NULL default '1',
				  `listcomment` int(11) NOT NULL default '1',
				  `commentpos` int(11) NOT NULL default '1',
				  `fullnewwindow` int(11) NOT NULL default '1',
				  `mediumnewwindow` int(11) NOT NULL default '0',
				  `templateid_index` int(11) NOT NULL default '0',
				  `templateid_medium` int(11) NOT NULL default '0',
				  `templateid_full` int(11) NOT NULL default '0',
				  PRIMARY KEY  (`objectid`),
				  KEY `name` (`name`)
				)
				");
		}
	}
	
}
