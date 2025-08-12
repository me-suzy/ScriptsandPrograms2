<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eReklamation
 * @subpackage model
 * $Id: erek_comp_model_caseawait.php,v 1.4 2005/03/23 04:27:24 jan Exp $
 */

global $system_path;
require_once($system_path.'basic_model.php');

class erek_comp_model_caseawait extends basic_model {

	function model() {
		foreach($this->objectid as $curid) {
			$obj = owRead($curid);
			$obj->updateObject(array('status' => CASE_AWAIT));
		}
	}
	
}

?>