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

class employee extends basic {

	function employee() {
		$this->basic();
		$this->addcolumn('departmentid',F_REL,'relationcreate','department');
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('jobtitle',F_LITERAL,'string');
		$this->addcolumn('binfile1',F_REL,'splitselect','binfile');
		$this->addcolumn('address1',F_LITERAL,'string');
		$this->addcolumn('address2',F_LITERAL,'hidden');
		$this->addcolumn('address3',F_LITERAL,'hidden');
		$this->addcolumn('address4',F_LITERAL,'hidden');
		$this->addcolumn('postalcode',F_LITERAL,'string');
		$this->addcolumn('city',F_LITERAL,'string');
		$this->addcolumn('state',F_LITERAL,'hidden');
		$this->addcolumn('country',F_LITERAL,'string');
		$this->addcolumn('telephone1',F_LITERAL,'string');
		$this->addcolumn('telephone2',F_LITERAL,'string');
		$this->addcolumn('telephone3',F_LITERAL,'hidden');
		$this->addcolumn('telefax',F_LITERAL,'hidden');
		$this->addcolumn('website',F_LITERAL,'hidden');
		$this->addcolumn('email1',F_LITERAL,'email');
		$this->addcolumn('email2',F_LITERAL,'hidden');
		$this->addcolumn('email3',F_LITERAL,'hidden');
		$this->addcolumn('timezone',F_LITERAL,'hidden');
		$this->addcolumn('birthday',F_LITERAL,'date');
		$this->addcolumn('specialdate1',F_LITERAL,'date');
		$this->addcolumn('specialtext1',F_LITERAL,'string');
		$this->addcolumn('comment',F_LITERAL,'text');

		$this->removeview('createvariant');
	}

	function initLayout() {
		parent::initLayout();
		$this->byside2('postalcode','city');
		$this->byside2('telephone1','telephone2');
		$this->addcolumnstyle('postalcode','width: 44px;');
		$this->addcolumnstyle('city','width: 125px;');
		#$this->addcolumnstyle('state','width: 50px;');
		$this->addcolumnstyle('telephone1','width: 66px;');
		$this->addcolumnstyle('telephone2','width: 66px;');
		#$this->addcolumnstyle('telephone3','width: 66px;');
		$this->addChildDatatype('calevent');
		$f =& $this->getColObj('binfile1');
		$f->setShowThumb(true);
	}
	
	function stdListCol() {
		$arr[] = 'name';
		$arr[] = 'jobtitle';
		$arr[] = 'departmentid';
		$arr[] = 'address1';
		$arr[] = 'postalcode';
		$arr[] = 'city';
		$arr[] = 'telephone1';
		$arr[] = 'email1';
		$arr[] = 'changed';
		return $arr;
	}
	
	function tableUpdate() {
		if (!tableExists('employee')) {
			$db =& getDbConn();
			$db->execute("
				CREATE TABLE `employee` (
				  `objectid` int(11) NOT NULL default '0',
				  `departmentid` int(11) NOT NULL default '0',
				  `name` varchar(255) NOT NULL default '',
				  `address1` varchar(100) NOT NULL default '',
				  `address2` varchar(100) NOT NULL default '',
				  `address3` varchar(100) NOT NULL default '',
				  `address4` varchar(100) NOT NULL default '',
				  `postalcode` varchar(50) NOT NULL default '',
				  `city` varchar(50) NOT NULL default '',
				  `state` varchar(50) NOT NULL default '',
				  `country` varchar(50) NOT NULL default '',
				  `binfile1` int(11) NOT NULL default '0',
				  `telephone1` varchar(50) NOT NULL default '',
				  `telephone2` varchar(50) NOT NULL default '',
				  `telephone3` varchar(50) NOT NULL default '',
				  `telefax` varchar(50) NOT NULL default '',
				  `website` varchar(100) NOT NULL default '',
				  `email1` varchar(100) NOT NULL default '',
				  `email2` varchar(100) NOT NULL default '',
				  `email3` varchar(100) NOT NULL default '',
				  `timezone` varchar(10) NOT NULL default '',
				  `birthday` varchar(10) NOT NULL default '',
				  `comment` mediumtext NOT NULL,
				  `jobtitle` varchar(255) NOT NULL default '',
				  PRIMARY KEY  (`objectid`),
				  KEY `name` (`name`)
				);
			");
		}
		if (!colExists($this->objecttable, 'specialdate1')) {
			$db =& getDbConn();
			$db->execute('ALTER TABLE `'.$this->objecttable.'` ADD `specialdate1` DATE NOT NULL');
			$db->execute('ALTER TABLE `'.$this->objecttable.'` ADD `specialtext1` VARCHAR(255) NOT NULL');
			$db->execute('ALTER TABLE `'.$this->objecttable.'` CHANGE `birthday` `birthday` DATE NOT NULL');
		}
	}
	
}