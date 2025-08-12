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

class binfile extends absfile {
	var $ERR_CANNOTMAKEDIR = 8;

	function binfile() {
		$this->absfile();
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('mimetype',0,UI_HIDDEN);
		$this->addcolumn('description',0,UI_STRING);
		$this->addcolumn('revision',0,UI_HIDDEN);
	
		$this->removeview('createvariant');
	}
	
	function stdListInfocol() {
		$result = parent::stdListInfocol();
		$result[] = '_icon';
		return $result;
	}
	
	function stdListCol() {
		$result[] = 'name';
		$result[] = 'createdbyname';
		$result[] = 'changed';
		$result[] = 'objectid';
		return $result;
	}
	
	function getRoot() {
		return $this->userhandler->getDirBinfile();
	}

	function getPhysicalFile() {
		return $this->getRoot() . substr($this->getObjectId(),-2).'/'.$this->getObjectId();
	}

	function prv_createobject(&$arr) {
		$uploaddir = $this->getRoot();
		$uploadfile = $uploaddir .substr($this->getObjectId(),-2).'/'. $this->getObjectId();
		if (!file_exists($uploaddir .substr($this->getObjectId(),-2).'/')) {
			if(!@mkdir($uploaddir .substr($this->getObjectId(),-2).'/', 0755)) {
				$this->errorcode = $this->ERR_CANNOTMAKEDIR;
				return $this->errorcode;
			}
		}
		if ($_FILES['__uploadfile__']['tmp_name']) {
			if (move_uploaded_file($_FILES['__uploadfile__']['tmp_name'], $uploadfile)) {
				$arr['name'] = $_FILES['__uploadfile__']['name'];
				$arr['mimetype'] = $_FILES['__uploadfile__']['type'];
			}
		} elseif($arr['realfile']) {
			copy($arr['realfile'],$uploadfile);
		} elseif($arr['_makecontent_']) {
			if ($handle = fopen($uploadfile, 'w')) {
				fwrite($handle, $arr['_makecontent_']);
				fclose($handle);
			}
		}
		
		parent::prv_createobject($arr);
	}
	
	function prv_readobject() {
		$uploaddir = $this->getRoot();
		$res['realfile'] = $uploaddir .substr($this->getObjectId(),-2).'/'. $this->getObjectId();
		$res['filesize'] = filesize($res['realfile']);
		return $res;
	}

	function prv_updateobject($arr) {
		$uploaddir = $this->getRoot();
		$uploadfile = $uploaddir .substr($this->getObjectId(),-2).'/'. $this->getObjectId();
		if (!file_exists($uploaddir .substr($this->getObjectId(),-2).'/')) {
			if(!@mkdir($uploaddir .substr($this->getObjectId(),-2).'/', 0755)) {
				$this->errorcode = $this->ERR_CANNOTMAKEDIR;
				return $this->errorcode;
			}
		}
		if ($_FILES['__uploadfile__']['tmp_name']) {
			rename($uploadfile,$uploadfile.".".$arr['revision']);
			$arr['revision'] = $arr['revision'] + 1;
			if (move_uploaded_file($_FILES['__uploadfile__']['tmp_name'], $uploadfile)) {
				$arr['mimetype'] = $_FILES['__uploadfile__']['type'];
			}
		}
		
		parent::prv_updateobject($arr);
	}
		
	function GetRevisions() {
		$objectid = $this->elements[0]['objectid'];
		$uploaddir = $this->getRoot();
		$uploadfile = $uploaddir .substr($objectid,-2).'/'. $objectid;
		$result = array();
		$files = glob($uploadfile.".*");
		if (is_array($files)) {
			foreach ($files as $filename) {
				$a = pathinfo($filename);
				$result[] = $a['extension'];
			}
		}
		return $result;
	}
	
}

?>
