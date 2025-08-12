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

class basic_model_requestapproval extends basic_model {

	function model() {
		foreach($this->objectid as $curid) {
			$obj = owRead($curid);
			if ($obj->hasFutureRevision()) {
				$futurerevisions = $obj->getFutureRevisions();
				$obj = owRead($futurerevisions[0]);
			}
			$obj->setpending(true);
		}
	}

}

?>