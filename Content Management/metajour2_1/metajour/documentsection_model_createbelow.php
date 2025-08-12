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

class documentsection_model_createbelow extends basic_model_create {

	function model() {
		$obj = owRead($this->data['nextparam']);
		$this->data = array();
		parent::model();
		$this->_obj->moveto($obj->getChildOrder());
	}

}

?>