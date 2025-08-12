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

class listing extends basic {

	function listing() {
		$this->basic();
		$this->allowduplicate = false;
		$this->setobjecttable('ext_listing');
		$this->addcolumn('name', F_LITERAL, 'string');
		$this->addcolumn('classname', 0, UI_CLASS);
		$this->addcolumn('allowview', F_LITERAL, 'checkbox');
		$this->addcolumn('category', 0, UI_RELATION_MULTIPLE, 'category');
		$this->addcolumn('templateid', F_REL, 'relation', 'template');
		$this->addcolumn('limitcol', F_COMBO, 'combo');
		
		$this->removeview('createvariant');
		$this->removeview('category');
		$this->removeview('access');
	}

	function initLayout() {
		parent::initLayout();
		
		if ($this->elements[0]['classname']) {
			$fields = owDatatypeColsDesc($this->elements[0]['classname']);
			
			$comboarray = array(''=>'');
			foreach ($fields as $field) {
				$fieldname = $field['name'];
				$fieldlabel = $field['label'];
				$comboarray[$fieldname] = $fieldlabel;
			}
			$limitcol =& $this->getColObj('limitcol');
		
			$limitcol->setComboArray($comboarray);
		} 
	}
	
	function tableUpdate() {
		if (!tableExists('ext_listing')) {
			$db =& getDbConn();
			$db->execute("
					CREATE TABLE `ext_listing` (
					  `objectid` int(11) NOT NULL default '0',
					  `name` varchar(255) NOT NULL default '',
					  `classname` varchar(255) NOT NULL default '',
					  `allowview` tinyint(4) NOT NULL default '0',
					  PRIMARY KEY  (`objectid`),
					  KEY `name` (`name`)
					);
				");
			$db->execute("
					CREATE TABLE `ext_listingdata` (
					  `objectid` int(11) NOT NULL default '0',
					  `name` varchar(255) NOT NULL default '',
					  `sortorder` int(11) NOT NULL default '0',
					  KEY `objectid` (`objectid`)
					);
				");
		}
		if (!colExists($this->objecttable, 'templateid')) {
			$db =& getDbConn();
			$db->execute('ALTER TABLE `'.$this->objecttable.'` ADD `templateid` INT(11) NOT NULL');
		}
		
		if (!colExists($this->objecttable, 'limitcol')) {
			$db =& getDbConn();
			$db->execute('ALTER TABLE `'.$this->objecttable.'` ADD `limitcol` varchar(255) NOT NULL');
		}
		
	}

	function prv_updateobject($arr) {
		basic::prv_updateobject($arr);
		$this->_adodb->execute("delete from ext_listingdata where objectid = ".$this->getObjectId());
		$i = 0;
		if (is_array($arr['fieldname'])) {
			foreach($arr['fieldname'] as $name) {
				if ($name != '') $this->_adodb->execute("insert into ext_listingdata (objectid, name, sortorder) values (".$this->getObjectId().",'".$arr['fieldname'][$i]."','".$arr['fieldsortorder'][$i]."')");
				$i++;
			}
		}
	}
	
	function prv_readobject() {
		$res =& $this->_adodb->execute("select name,sortorder from ext_listingdata where objectid = ".$this->getObjectId()." order by sortorder");
		while ($row = $res->fetchrow()) {
			$arr['fieldname'][] = $row['name'];
			$arr['fieldsortorder'][] = $row['sortorder'];
		}
		return $arr;
	}
	
}

?>
