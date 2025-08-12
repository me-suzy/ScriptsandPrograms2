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

class profile extends basic {

	function profile() {
		$this->basic();
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('editorstyle',F_LITERAL,'checkbox');
		$this->addcolumn('editortable',F_LITERAL,'checkbox');
		$this->addcolumn('editorcolor',F_LITERAL,'checkbox');
		$this->addcolumn('editorspecial',F_LITERAL,'checkbox');
	
		$this->removeview('createvariant');
		$this->removeview('category');
		$this->removeview('access');
		$this->addview('default');
	}

	function tableUpdate() {
		if (!colExists($this->objecttable, 'editorstyle')) {
			$db =& getDbConn();
			$db->execute('ALTER TABLE `'.$this->objecttable."` ADD `editorstyle` INT(11) NOT NULL default '1'");
			$db->execute('ALTER TABLE `'.$this->objecttable."` ADD `editortable` INT(11) NOT NULL default '1'");
			$db->execute('ALTER TABLE `'.$this->objecttable."` ADD `editorcolor` INT(11) NOT NULL default '1'");
			$db->execute('ALTER TABLE `'.$this->objecttable."` ADD `editorspecial` INT(11) NOT NULL default '1'");
		}
	}
	
	function prv_createobject(&$arr) {
		basic::prv_createobject($arr);
		if (is_array($arr['fielddata'])) {
			foreach($arr['fielddata'] as $field) {
				$i = strpos($field,'#');
				$type = substr($field,0,$i);
				$view = substr($field,$i+1);
				$this->_adodb->execute("insert into profile_data (objectid, type, view) values ('".$this->getObjectId()."','$type','$view')");
			}
		} else {

			$arrdtmp = owListExtensions(true);
			foreach ($arrdtmp as $type) {
				if (owIsExtendedDatatype($type)) $arrdt[] = $type;
			}
			$arr = array_merge(owListCore(true), $arrdt);
	
			foreach ($arr as $type) {
				if (owTry($type)) {
					$obj = owNew($type);
					$views = $obj->getviews();
					foreach ($views as $view) {
						$this->_adodb->execute("insert into profile_data (objectid, type, view) values ('".$this->getObjectId()."','$type','$view')");
					}
				}
			}

		}
	}
	
	function prv_updateobject($arr) {
		basic::prv_updateobject($arr);
		$this->_adodb->execute("delete from profile_data where objectid = ".$this->getObjectId());
		if (is_array($arr['fielddata'])) {
			foreach($arr['fielddata'] as $field) {
				$i = strpos($field,'#');
				$type = substr($field,0,$i);
				$view = substr($field,$i+1);
				$this->_adodb->execute("insert into profile_data (objectid, type, view) values (".$this->getObjectId().",'$type','$view')");
			}
		}
	}
	
	function prv_readobject() {
		$res =& $this->_adodb->execute("select type,view from profile_data where objectid = ".$this->getObjectId());
		while ($row = $res->fetchrow()) {
			$arr[$row['type']][$row['view']] = true;
		}
		return $arr;
	}

}

?>
