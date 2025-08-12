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

class basic_model_delete extends basic_model {

	function model() {
		foreach($this->objectid as $curid) {
			$obj = owRead($curid);
			/* If we have a future revision, only delete the future revision */
			if ($obj->hasFutureRevision()) {
				$futureids = $obj->getFutureRevisions();
				foreach ($futureids as $futureid) {
					$futureobj = owRead($futureid);
					$futureobj->deleteobject();
				}
			} else {
				$obj->deleteobject();
			}
		}
	}

}

?>