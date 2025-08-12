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

class documentsection extends basic {

	function documentsection() {
		$this->basic();
		$this->setsupertype('document');
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('subname',0,UI_STRING);
		$this->addcolumn('extension',0,UI_COMPONENT);
		$this->addcolumn('configset',0,UI_STRING);
		$this->addcolumn('params',0,UI_STRING);
		$this->addcolumn('script',0,UI_CHECKBOX);
		$this->addcolumn('file', F_REL, 'splitselect', 'binfile');
		$this->addcolumn('content',0,UI_TEXT);

		$this->addview('createfuture');
		$this->addview('approvepublish');
		$this->addview('requestapproval');
	}

	function prv_UpdateObject($arr) {
		if (isset($arr['extension']) && !empty($arr['extension'])) {
			require_once($this->userhandler->getSystemPath().'/extension/'.$arr['extension'].'/'.$arr['extension'].'.class.php');
			$s = 'ext_'.$arr['extension'];
			$extension = new $s();
			if ($extension->hasConfigSet()) {
				$name = 'cfg'.$this->getObjectid();
				$arr['configset'] = $name; //same auto-naming as used in showpage.php
				$obj = owNew($arr['extension']);
				$id = $obj->locateByName($name);
				if (!$id) {
					$obj->createObject(array('name' => $name));
				}
			}
			
			if ($extension->hasContentTree()) {
				$parentid = $this->prv_options['parentid'];
				$document = owRead($parentid);
				$document->updateObject(array('hascontenttree'=>1));
			}
		}
		parent::prv_UpdateObject($arr);
	}

	function hasAccess() {
		$total = (isset($this->prv_options['access'])) ? sizeof($this->prv_options['access']) : 0;
		//if no permissions are set for the section, and we're a system-user
		//then we grant access to the section
		//fixes (maybe not permanent) the problem with editors that don't
		//have access to the sections, but do have access to the document
		if (!$this->webuser && !$total) return true;
		return parent::hasAccess();
	}

	function tableUpdate() {
		if (!colExists($this->objecttable, 'file')) {
			$db =& getdbconn();
			$db->execute("ALTER TABLE documentsection ADD COLUMN file int not null default 0");
		}
	}
}

?>
