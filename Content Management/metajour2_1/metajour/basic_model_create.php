<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage model
 */

require_once('basic_model.php');

class basic_model_create extends basic_model {
	var $_obj;

	function model() {
		$this->_obj = owNew($this->otype);
		
		if ($this->parentid) {
			$this->_obj->createObject($this->data,$this->parentid);
		} else {
			$this->_obj->createObject($this->data,0);	
		}

		$this->userhandler->setObjectIdStack($this->_obj->getObjectId());
		
		if ($this->otype == 'document') {
			$sectionobj = owNew('documentsection');
			$sectionobj->createObject(array("name" => ""),$this->_obj->getObjectId());
		}
		
	if (is_array($_SESSION['userupload'])) {
		foreach($_SESSION['userupload'] as $ufile) {
			$addfile = array();
			$addfile['name'] = $ufile['filename'];
			$addfile['realfile'] = $ufile['path'];
			$addfile['mimetype'] = $ufile['mimetype'];
			$binobj = owNew('binfile');
			$binobj->createObject($addfile,$this->_obj->getObjectId());
		}
		unset($_SESSION['userupload']);
	}
		

		if (isset($this->data['__categories__'])) {
			$this->_obj->setCategory($this->data['__categories__']);
		}

		if (isset($this->data['__webaccess__']) || isset($this->data['__sysaccess__'])) {
			$this->_obj->setAccess($this->data['__webaccess__'],$this->data['__sysaccess__']);
		}

		$extra = owDatatypeExtraCols($this->otype);
		if (!empty($extra)) {
			$arr = packData($extra, $this->data);
			$this->_obj->setExtraData($arr);
		}
	}	
}

?>