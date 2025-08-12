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

class user extends basic {

	function user() {
		$this->basic();
		$this->allowduplicate = false;
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('password',0,UI_PASSWORD);
		$this->addcolumn('realname',0,UI_STRING);
		$this->addcolumn('email',F_LITERAL,'email');
		$this->addcolumn('country',0,UI_COUNTRY);
		$this->addcolumn('rootdir',0,UI_HIDDEN);
		$this->addcolumn('profileid',0,UI_RELATION,'profile');
		$this->addcolumn('objectlanguage',0,UI_LANGUAGE);
		$this->addcolumn('guilanguage',0,UI_LANGUAGE);
		$this->addcolumn('guilistlanguage',0,UI_LANGUAGE);
		$this->addcolumn('locale',0,UI_LANGUAGE);
		$this->addcolumn('restrictlanguage',0,UI_CHECKBOX);
		$this->addcolumn('oldeditor',0,UI_CHECKBOX);
		$this->addcolumn('app',0,UI_APP);
		$this->addcolumn('appavail',0,UI_APP_MULTIPLE);
		$this->addcolumn('exstr1',0,UI_HIDDEN);
		$this->addcolumn('exstr2',0,UI_HIDDEN);
		$this->addcolumn('exstr3',0,UI_HIDDEN);
		$this->addcolumn('exstr4',0,UI_HIDDEN);
		$this->addcolumn('exstr5',0,UI_HIDDEN);
		$this->addcolumn('exstr6',0,UI_HIDDEN);
		$this->addcolumn('exstr7',0,UI_HIDDEN);
		$this->addcolumn('exstr8',0,UI_HIDDEN);
	
		$this->removeview('createvariant');
	}

	function tableUpdate() {
		if (!colExists($this->objecttable, 'oldeditor')) {
			$db =& getDbConn();
			$db->execute('ALTER TABLE `'.$this->objecttable.'` ADD `oldeditor` INT(11) NOT NULL');
		}
	}

	function stdListCol() {
		$arr[] = 'name';
		$arr[] = 'realname';
		$arr[] = 'email';
		$arr[] = 'profileid';
		$arr[] = 'createdbyname';
		$arr[] = 'changed';
		$arr[] = 'objectid';
		return $arr;
	}
	
	function prv_createobject($arr) {
		$arr['password'] = $this->_adodb->getone("select MD5('" . $arr['password'] . "')");
		basic::prv_createobject($arr);
		$tarr = $arr['__membership__'];
		if (is_array($tarr)) {
			$objectid = $this->getObjectId();
			foreach ($tarr as $curr) {
				if (!empty($curr)) 
					// It's not possible to add the SYSTEM account to any groups
					if ($objectid != $this->userhandler->getSystemAccountId())
						$this->_adodb->execute("insert into usergroupmember (groupid , userid) values ('$curr', '$objectid')");
			}
		}
	}
	
	function prv_updateobject($arr) {
		$tmp = $this->_adodb->getone("select password from user where objectid = ".$this->getObjectId());
		if ($tmp != $arr['password']) {
			$arr['password'] = $this->_adodb->getone("select MD5('" . $arr['password'] . "')");
		}
		parent::prv_updateobject($arr);
		if (isset($arr['__membership__'])) {
			$this->_adodb->execute("delete from usergroupmember where userid = ".$this->getObjectId());
			$tarr = $arr['__membership__'];
			if (is_array($tarr)) {
				foreach ($tarr as $curr) {
					if (!empty($curr)) 
						// It's not possible to add the SYSTEM account to any groups
						if ($this->getObjectId() != $this->userhandler->getSystemAccountId())
							$this->_adodb->execute("insert into usergroupmember (groupid , userid) values ('$curr', ".$this->getObjectId().")");
				}
			}
		}
	}

	function deleteobject() {
		// It's not possible to delete the SYSTEM user
		if ($this->getName() != 'SYSTEM')
			parent::deleteobject();
	}

	function prv_deleteobject() {
		$this->_adodb->execute("delete from usergroupmember where userid=".$this->getObjectId());
		basic::prv_deleteobject();
	}
	
	function getusername($objectid) {
		$tmp = $this->_adodb->getone("select name from user where objectid = $objectid");
		return $tmp;
	}

	function getgroupmemberships($userid) {
		return $this->_adodb->getcol("select groupid from usergroupmember where userid='$userid'");
	}

}
