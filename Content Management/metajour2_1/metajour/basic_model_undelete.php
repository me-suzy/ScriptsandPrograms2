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

class basic_model_undelete extends basic_model {

	function model() {
		$eh =& getErrorHandler();
		foreach($this->objectid as $curid) {
			$eh->disable();
			$obj = owNew(owGetDatatype($curid));
			$obj->setfilter_deleted(true);
			$obj->readobject($curid);
			$eh->enable();
			$obj->undeleteobject();
		}
	}

}

?>