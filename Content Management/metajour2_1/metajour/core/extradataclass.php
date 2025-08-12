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

class extradata extends basic {

	function extradata() {
		$this->basic();
		$this->addcolumn('name', 0, UI_CLASS);
	
		$this->removeview('createvariant');
		$this->removeview('createfuture');
		$this->removeview('approvepublish');
		$this->removeview('requestapproval');
		$this->removeview('category');
		
		$this->allowduplicate = false;
	}

	function hasAccess() {
		return true;
	}
	
	function prv_updateobject($arr) {
		basic::prv_updateobject($arr);
		$this->_adodb->execute("delete from extradata_data where objectid = ".$this->getObjectId());
		$i = 0;
		if (is_array($arr['fieldname'])) {
			foreach($arr['fieldname'] as $name) {
				if ($name != '') $this->_adodb->execute("insert into extradata_data (objectid, name, type, relation, description, sortorder) values (".$this->getObjectId().",'".$arr['fieldname'][$i]."','".$arr['fieldtype'][$i]."','".$arr['fieldrelation'][$i]."','".$arr['fielddescription'][$i]."','".$arr['fieldsortorder'][$i]."')");
				$i++;
			}
		}
	}
	
	function prv_readobject() {
		$res =& $this->_adodb->execute("select name,type,relation,description,sortorder from extradata_data where objectid = ".$this->getObjectId()." order by sortorder");
		if ($res) {
			while ($row = $res->fetchrow()) {
				$arr['fieldname'][] = $row['name'];
				$arr['fieldtype'][] = $row['type'];
				$arr['fieldrelation'][] = $row['relation'];
				$arr['fielddescription'][] = $row['description'];
				$arr['fieldsortorder'][] = $row['sortorder'];
			}
		}
		return $arr;
	}
}
