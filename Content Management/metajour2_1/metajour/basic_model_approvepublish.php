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

class basic_model_approvepublish extends basic_model {

	function approvepublish($curid) {
		$futureobject = owRead($curid);

		//when doing approvepublish from the document list, we're
		//not working on the future revision, but on the current one.
		//So that's why we need to lookup if there's any
		//future revision of the object, and load an instance
		//of that instead.
		if ($futureobject->hasFutureRevision()) {
			$arr = $futureobject->getFutureRevisions();
			$this->errorhandler->disable();
			$futureobject = owRead($arr[0]);
			$this->errorhandler->enable();
		}

		if (!$futureobject) return;
		if (!$futureobject->elements[0]['object']['futurerevisionof']) return;
		$activeobject = owRead($futureobject->elements[0]['object']['futurerevisionof']);
		
		$previousobject = owNew($futureobject->getType());
		$previousobject->createObject($activeobject->elements[0]);
		$previousobject->setOldRevisionOf($futureobject->elements[0]['object']['futurerevisionof']);
		
		$childs = $activeobject->getChilds();
		foreach ($childs as $order) {
			$childobj = owRead($order);
			$childobj->setParentId($previousobject->getObjectId());
		}

		$childs = $futureobject->getChilds();
		foreach ($childs as $order) {
			$childobj = owRead($order);
			$childobj->setParentId($activeobject->getObjectId());
		}
		
		$previousobject->updateObject($activeobject->elements[0]);
		$activeobject->updateObject($futureobject->elements[0]);
		
		$futureobject->setFutureRevisionOf(0);
		$futureobject->deleteObject();
		$activeobject->setHasFutureRevision(0);
		$activeobject->setApproved(true);
	}
	
	function model() {
		foreach($this->objectid as $curid) {
			$this->approvepublish($curid);
		}
	}

}

?>