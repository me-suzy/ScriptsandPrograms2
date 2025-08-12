<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage core
 */

require_once('absfileclass.php');

class staticbinfile extends absfile {
	var $ERR_CANNOTMAKEDIR = 8;

	function staticbinfile() {
		$this->absfile();
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('description',0,UI_STRING);
		$this->removeview('createvariant');
	}

	function getPhysicalFile() {
		return $this->getPath($this->getParentId()).$this->getName();
	}
	
	function getRoot() {
		return $this->userhandler->getDirStaticbinfile();
	}

	function getPath($parentid)	{
		if ($parentid == 0) return $this->getRoot();
		$obj = owRead($parentid);
		return $obj->getFullPath().'/';
	}
	
	function prv_createobject(&$arr) {
		$uploaddir = $this->getPath($this->getParentId());
		if ($_FILES['__uploadfile__']['tmp_name']) {
			if (move_uploaded_file($_FILES['__uploadfile__']['tmp_name'], 
			   $uploaddir.basename($_FILES['__uploadfile__']['name']))) {
				$arr['name'] = $_FILES['__uploadfile__']['name'];
				parent::prv_createobject($arr);
			} else {
				# make an error statement here
			}
		} else {
			# in case we're creating an object based on a file already in
			# the filesystem (remember to set $arr['name'] on createObject() )
			parent::prv_createobject($arr);
		}
	}
	
	function createProbeFile($name) {
		$uploaddir = $this->getpath($this->getParentId());
		$file = $uploaddir .$name;
		$h = fopen($file,'w');
		echo $file;
		if (!fwrite($h,'dummy')) die('error');
		fclose($h);
	}
	
	function prv_readobject() {
		# check for exist, or delete the entry from the staticbinfile table
		$uploaddir = $this->getpath($this->getParentId());
		if (($res['size'] = @filesize($uploaddir .$this->prv_options['name'])) !== FALSE) {
			$res['realfile'] = $uploaddir .$this->prv_options['name'];
			include_once($this->userhandler->getSystemPath().'core/util/class.mimetypes.php');
			$mime =& new Mime_Types($this->userhandler->getSystemPath().'core/util/mime.types');
			$res['mimetype'] = $mime->get_type($this->prv_options['name']);
			return $res;
		} else {
			$db =& getDbConn();
			$db->Execute("delete from ".$this->objecttable." where objectid = ".$this->getObjectId() );
			$this->_removeelement = true;
			return array();
		}
	}

	function prv_updateobject($arr) {
		$obj = owRead($this->getObjectId());
		if ($obj) {
			if ($arr['name'] != $obj->elements[0]['name']) {
				if (rename($this->getpath($this->getParentId()).$obj->elements[0]['name'],
					$this->getpath($this->getParentId()).$arr['name'])) {
						parent::prv_UpdateObject($arr);
					} else {
						# make an error statement here
					}
			} else {
				# in case we just changed the description
				parent::prv_UpdateObject($arr);
			}
		}
	}

	function deleteObject() {
		$res = $this->prv_readobject();
		if (@unlink($res['realfile'])) {
			parent::deleteObject();
		}
	}
	
	function listObjects($parentid=0,$objectid=0) {
		$matches = glob($this->getPath($parentid).'*');
		if (is_array($matches)) {
			$db = &getDbConn();
			$files = $db->getCol("select name from ".$this->objecttable.", object where ".$this->objecttable.".objectid = object.objectid and object.deleted = 0 and object.parentid = $parentid");
			if (!$files) $files = array();
			foreach ($matches as $fname) {
				if (!is_dir($fname)) {
					if (!in_array(basename($fname),$files)) {
						$obj = owNew($this->type);
						$obj->createObject(array('name' => basename($fname)),$parentid);
					}
				}
			}
		}
		return parent::listObjects($parentid,$objectid);
	}

}

?>
