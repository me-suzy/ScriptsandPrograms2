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

class basic_model_createfuture extends basic_model {

	function model() {
		foreach($this->objectid as $curid) {
			$oldobject = owRead($curid);
			if (!$oldobject->hasFutureRevision()) {
				/* allow only one future revision per object */
				$obj = owNew($this->otype);
				$obj->createObject($oldobject->elements[0],$oldobject->getParentId());
				$obj->setFutureRevisionOf($curid);
				$obj->setApproved(false);
				$id = $obj->getObjectId();
				
				$childs = $oldobject->getChilds();
				foreach ($childs as $order) {
					$oldsectionobj = owRead($order);
					$newsectionobj = owNew($oldsectionobj->getType());
					$newsectionobj->createObject($oldsectionobj->elements[0],$id);
				}
				$this->userhandler->setObjectIdStack($id);
			}
		}
	}
}

?>