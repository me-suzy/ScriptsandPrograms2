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

class listcol extends basic {

	function listcol() {
		$this->basic();
		$this->addcolumn('name', 0, UI_CLASS);
		$this->addcolumn('pname', 0, UI_STRING);
		$this->addcolumn('makedefault', 0, UI_CHECKBOX);
	
		$this->removeview('createvariant');
		$this->removeview('createfuture');
		$this->removeview('approvepublish');
		$this->removeview('requestapproval');
		$this->removeview('category');
	}

	function stdListCol() {
		$arr = array();
		$arr[] = 'name';
		$arr[] = 'pname';
		$arr[] = 'createdbyname';
		$arr[] = 'changed';
		$arr[] = 'language';
		$arr[] = 'objectid';
		return $arr;
	}

	function tableUpdate() {
		if (!colExists('listcol','pname')) {
			$db =& getDbConn();
			$db->execute("ALTER TABLE `listcol` ADD `pname` VARCHAR( 255 ) NOT NULL;");
		}
	}
	
	function prv_updateobject($arr) {
		basic::prv_updateobject($arr);
		$this->_adodb->execute("delete from listcol_data where objectid = ".$this->getObjectId());
		$i = 0;
		if (is_array($arr['fieldname'])) {
			foreach($arr['fieldname'] as $name) {
				if ($name != '') $this->_adodb->execute("insert into listcol_data (objectid, name, sortorder) values (".$this->getObjectId().",'".$arr['fieldname'][$i]."','".$arr['fieldsortorder'][$i]."')");
				$i++;
			}
		}
	}
	
	function prv_readobject() {
		$res =& $this->_adodb->execute("select name,sortorder from listcol_data where objectid = ".$this->getObjectId()." order by sortorder");
		if ($res) {
			while ($row = $res->fetchrow()) {
				$arr['fieldname'][] = $row['name'];
				$arr['fieldsortorder'][] = $row['sortorder'];
			}
		}
		return $arr;
	}

}

?>
