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

class staticfolder extends basic {

	function staticfolder() {
		$this->basic();
		$this->addcolumn('name',0,UI_STRING);
	
		$this->removeview('createvariant');
	}

	function getRoot() {
		return $this->userhandler->getDirStaticbinfile();
	}

	function getRelativePath($objectid) {
		# returns the path above AND INCLUDING the referred objectid
		# the method will usually be called with a parentid as parameter
		# note that the complete relative path is returned WITHOUT a trailing slash
		$res = '';
		if ($objectid > 0) {
			$obj = owRead($objectid);
			if ($obj) {
				$res = $obj->getName();
				if ($obj->getParentId() > 0) $res = $this->getRelativePath($obj->getParentId()).'/'.$obj->getName();
			}
		}
		return $res;
	}
	
	function getFullPath() {
		# returns the complete absolute path to (and including) the current 
		# object. Only used from outside of the class
		$s = $this->getRelativePath($this->getParentId());
		if (!empty($s)) {
			return $this->getRoot().$s.'/'.$this->getName();
		} else {
			return $this->getRoot().$this->getName();			
		}
	}
	
	function prv_CreateObject($arr) {
		if (!file_exists($this->getRoot().$this->getRelativePath($this->getParentId()).'/'.$arr['name'])) {
			mkdir($this->getRoot().$this->getRelativePath($this->getParentId()).'/'.$arr['name']);
		}
		parent::prv_CreateObject($arr);
	}
	
	function prv_UpdateObject($arr) {
		$obj = owRead($this->getObjectId());
		if ($obj) {
			if ($arr['name'] != $obj->elements[0]['name']) {
				if (rename($this->getRoot().$this->getRelativePath($this->getParentId()).'/'.$obj->elements[0]['name'],
					$this->getRoot().$this->getRelativePath($this->getParentId()).'/'.$arr['name']))
						parent::prv_UpdateObject($arr);
			}
		}
	}
	
	function prv_ReadObject() {
		$arr = end($this->elements);
		if (!is_dir($this->getRoot().$this->getRelativePath($arr['parentid']).'/'.$arr['name'])) {
			$db = &getDbConn();
			if (!empty($objectid)) $db->Execute("delete from ".$this->objecttable." where objectid = ".$this->getObjectId());
			$this->_removeelement = true;
			return array();
		}
		parent::prv_ReadObject();
	}

	function prv_DeleteObject() {
		parent::prv_DeleteObject();
	}

	function listObjects($parentid=0,$objectid=0) {
		$s = $this->getRoot().$this->getRelativePath($parentid).'/*';
		$s = str_replace('//','/',$s);
		$matches = glob($s,GLOB_ONLYDIR);
		if (is_array($matches)) {
			$db = &getDbConn();
			$dirs = $db->getCol("select name from ".$this->objecttable.", object where ".$this->objecttable.".objectid = object.objectid and object.deleted = 0 and object.parentid = $parentid");
			if (!$dirs) $dirs = array();
			foreach ($matches as $fname) {
				if (!in_array(basename($fname),$dirs)) {
					$obj = owNew($this->type);
					$obj->createObject(array('name' => basename($fname)),$parentid);
					$check = glob($this->getRoot().$this->getRelativePath($parentid).'/'.basename($fname),GLOB_ONLYDIR);
					if (!empty($check))
						$db->execute("update object set haschild = 1 where objectid = ".$obj->getObjectId());
				}
			}
		}
		return parent::listObjects($parentid,$objectid);
	}

}