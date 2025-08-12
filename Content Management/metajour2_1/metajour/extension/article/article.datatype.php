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

class article extends basic {

	function article() {
		$this->basic();
		$this->allowduplicate = false;
		$this->setobjecttable('ext_article');
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('title',F_LITERAL,'string');
		$this->addcolumn('categoryid',F_REL,'relation','category');
		$this->addcolumn('sortorder',F_COMBO,'combo');
		$this->addcolumn('excerptlength',F_LITERAL,'string');
		$this->addcolumn('timelimit',F_LITERAL,'string');
		$this->addcolumn('numlimit',F_LITERAL,'string');
		$this->addcolumn('showheader',F_LITERAL,'checkbox');
		$this->addcolumn('showsubheader',F_LITERAL,'checkbox');
		$this->addcolumn('showdate',F_LITERAL,'checkbox');
		$this->addcolumn('showowner',F_LITERAL,'checkbox');
		$this->addcolumn('showexcerpt',F_LITERAL,'checkbox');
		$this->addcolumn('templateid',F_REL,'relation','template');

		$this->removeview('createvariant');
		$this->removeview('category');
		$this->removeview('access');
	}

	function tableUpdate() {
		if (!tableExists('ext_article')) {
			$db =& getDbConn();
			$db->execute("
				CREATE TABLE `ext_article` (
				  `objectid` int(11) NOT NULL default '0',
				  `name` varchar(255) NOT NULL default '',
				  `title` varchar(255) NOT NULL default '',
				  `categoryid` int(11) NOT NULL default '0',
				  `sortorder` int(11) NOT NULL default '0',
				  `excerptlength` int(11) NOT NULL default '200',
				  `timelimit` int(11) NOT NULL default '0',
				  `showheader` int(11) NOT NULL default '1',
				  `showsubheader` int(11) NOT NULL default '0',
				  `showdate` int(11) NOT NULL default '1',
				  `showowner` int(11) NOT NULL default '1',
				  `showexcerpt` int(11) NOT NULL default '1',
				  `templateid` int(11) NOT NULL default '0',
				  PRIMARY KEY  (`objectid`),
				  KEY `name` (`name`)
				)
				");
		}
		
		if (!colExists('ext_article', 'numlimit')) {
			$db =& getDbConn();
			$db->execute("ALTER TABLE ext_article ADD COLUMN numlimit INT NOT NULL DEFAULT '0' AFTER timelimit");
		}
	}
	
}
?>