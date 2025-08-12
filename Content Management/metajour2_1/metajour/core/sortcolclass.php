<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage core
 */

require_once(dirname(__FILE__) . '/basicclass.php');

class sortcol extends basic {

	function sortcol() {
		$this->basic();
		$this->addcolumn('name', 0, UI_CLASS);
		$this->addcolumn('makedefault', 0, UI_CHECKBOX);
	
		$this->removeview('createvariant');
		$this->removeview('createfuture');
		$this->removeview('approvepublish');
		$this->removeview('requestapproval');
		$this->removeview('category');
	}
	
	function prv_updateobject($arr) {
		basic::prv_updateobject($arr);
		$db =& $this->_adodb;
		$db->execute("delete from sortcol_data where objectid = ".$this->getObjectId());
		$i = 0;
		if (is_array($arr['fieldname'])) {
			foreach($arr['fieldname'] as $name) {
				if ($name != '') {
					$res = $db->execute(sprintf("insert into sortcol_data (objectid, name, way, sortorder) values (%d,%s,%s,%s)",
							       $this->getObjectId(),
							       $db->qstr($arr['fieldname'][$i]),
							       $db->qstr($arr['fieldway'][$i]),
							       $db->qstr($arr['fieldsortorder'][$i])));
					if ($res === false) {
						$this->errorhandler->seterror('sortcol::prv_updateobject: ' . $db->errormsg());
					}
				}
	
				$i++;
			}
		}
	}
	
	function prv_readobject() {
		$res =& $this->_adodb->execute("select name, way, sortorder from sortcol_data where objectid = ".$this->getObjectId()." order by sortorder");
		if ($res === false) {
			$this->errorhandler->seterror($this->_adodb->errormsg());
			return array();
		}
		$arr['fieldname'] = array();
		$arr['fieldsortorder'] = array();
		$arr['fieldway'] = array();
		if ($res) {
			while ($row = $res->fetchrow()) {
				$arr['fieldname'][] = $row['name'];
				$arr['fieldsortorder'][] = $row['sortorder'];
				$arr['fieldway'][] = $row['way'];
			}
		}
		return $arr;
	}

}

?>
