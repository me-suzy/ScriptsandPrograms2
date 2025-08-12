<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package SSA
 * @subpackage model
 * $Id: edocument_edocform_model_caseclose.php,v 1.3 2005/03/23 04:26:51 jan Exp $
 */

require_once(dirname(__FILE__) . '/../../basic_model.php');

class edocument_edocform_model_caseclose extends basic_model {

	function model() {
		foreach($this->objectid as $curid) {
			$obj = owRead($curid);
			$obj->updateObject(array('status' => CASE_CLOSED));
		}
	}
	
}

?>