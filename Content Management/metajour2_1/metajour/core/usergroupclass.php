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

class usergroup extends basic {

	function usergroup() {
		$this->basic();
		$this->allowduplicate = false;
		$this->addcolumn('name',0,UI_STRING);
		$this->addcolumn('level',0,UI_HIDDEN);
		
		$this->removeview('createvariant');
		$this->removeview('createfuture');
		$this->removeview('approvepublish');
		$this->removeview('requestapproval');
		$this->removeview('category');
	}

	function prv_createobject($arr) {
		$arr['level'] = -1;
		basic::prv_createobject($arr);
		$tarr = $arr['__member__'];
		if (is_array($tarr)) {
			$objectid = $this->getObjectId();
			foreach ($tarr as $curr) {
				if (!empty($curr)) 
					// It's not possible to add the SYSTEM account to any groups
					if ($curr != $this->userhandler->getSystemAccountId())
						$this->_adodb->execute("insert into usergroupmember (groupid , userid) values ($objectid, '$curr')");
			}
		}
	}
		
	function prv_updateobject($arr) {
		parent::prv_updateobject($arr);
		$this->_adodb->execute("delete from usergroupmember where groupid = ".$this->getObjectId());
		$tarr = $arr['__member__'];
		if (is_array($tarr)) {
			foreach ($tarr as $curr) {
				if (!empty($curr)) 
					// It's not possible to add the SYSTEM account to any groups
					if ($curr != $this->userhandler->getSystemAccountId())
						$this->_adodb->execute("insert into usergroupmember (groupid , userid) values (".$this->getObjectId().", '$curr')");
			}
		}
	}

	function deleteobject() {
		// It's not possible to delete the system-usergroups
		if (!in_array($this->getName(),array('ANONYMOUS','USER','EDITOR','MANAGER','ADMINISTRATOR')))
			parent::deleteobject();
	}
	
	function prv_deleteobject() {
		$this->_adodb->execute("delete from usergroupmember where groupid=".$this->getObjectId());
	}
	
	function getsystemgroup($accesslevel) {
		$tmp = $this->_adodb->getone("select usergroup.objectid as res from usergroup, object where usergroup.objectid = object.objectid and site = '$this->site' and level = '$accesslevel'");
		return (is_null($tmp)) ? false : $tmp;
	}
	
	function getmembers() {
		return $this->_adodb->getcol("select userid from usergroupmember where groupid='".$this->prv_options['objectid']."'");
	}

	function addmember($userid) {
		// It's not possible to add the SYSTEM account to any groups
		if ($userid != $this->userhandler->getSystemAccountId())
			$this->_adodb->execute("replace into usergroupmember (groupid , userid) values ('".$this->getObjectId()."', '$userid')");
	}

}

?>
