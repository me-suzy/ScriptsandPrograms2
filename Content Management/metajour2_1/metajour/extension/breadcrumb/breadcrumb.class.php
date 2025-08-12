<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage extension
 */

require_once($system_path . 'extension/basicextension.class.php');
require_once($system_path . 'core/structureclass.php');
require_once($system_path . 'core/structureelementclass.php');
require_once($system_path . 'core/documentclass.php');

class ext_breadcrumb extends basicextension {

	function ext_breadcrumb() {
		$this->basicextension();
		$this->extname = 'breadcrumb';
		$this->structureobj =& new structure;

		$this->_adodb =& getdbconn();
		$this->addextparam('document');
		$this->addextparam('structure');
		$this->addextparam('structureid');
	}
	
	function containedin($objectid, $structureid) {
		$parentid = $this->_adodb->getone("select parentid from object where objectid=$objectid");
		if ($parentid == 0) return false;
		if ($parentid == $structureid) return true;
		return $this->containedin($parentid, $structureid);
	}

	function findposition() {

		$structureid = $this->structureobj->locatebyname($this->extconfigset);
		if ($this->extconfig['structure']) $structureid = $this->structureobj->locatebyname($this->extconfig['structure']);
		if ($this->extconfig['structureid']) $structureid = $this->extconfig['structureid'];

		$result = array();
		$query = "SELECT se.objectid FROM structureelement se INNER JOIN object o USING(objectid) WHERE site=" .
		          $this->site . " AND deleted=0 AND type='structureelement' AND pageid=" . $this->extconfig['document'];
		$res = $this->_adodb->query($query);
		$objectid = 0;
		while(($row = $res->fetchrow()) && !$objectid) {
			if ($this->containedin($row['objectid'], $structureid)) {
				$objectid = $row['objectid'];
			}
		}

		$query = "select o.parentid, o.objectid, se.name from object o inner join structureelement se " .
		         "on o.objectid = se.objectid where o.objectid=$objectid";
		$row = $this->_adodb->getrow($query);

		while(count($row) && $row['parentid']) {
			
			$se =& new structureelement();
			$se->readobject($row['objectid']);
			
			$result[] = array(
			                  'objectid'=>$se->getobjectid(),
			                  'name'=>$se->getname(),
			                  'documentid'=>$se->elements[0]['pageid']);
			
			$query = "select parentid from object where objectid=" . $row['parentid'];
			$query = "select o.parentid, o.objectid, se.name from object o inner join structureelement se " .
			          "on o.objectid = se.objectid where o.objectid=" . $row['parentid'];

			$row = $this->_adodb->getrow($query);
		}
		$this->extresult = array_reverse($result);
	}

	function _do() {

		if (!$this->extconfig['document']) return;

		switch ($this->extcmd) {

			default:
				$this->findposition();
		}
	}


}
