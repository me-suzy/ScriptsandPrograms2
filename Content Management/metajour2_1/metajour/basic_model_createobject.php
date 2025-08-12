<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage model
 */

require_once('basic_model_create.php');

class basic_model_createobject extends basic_model_create {

	function model() {
		$_obj = owNew($this->otype);
		$_obj->listobjects();
		if ($_obj->elementscount < 4) {
			parent::model();
		} else {
			$this->userhandler->setObjectIdStack($_obj->elements[0]['objectid']);
		}
	}

}

?>