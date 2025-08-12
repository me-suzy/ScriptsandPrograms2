<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eProject
 * @subpackage model
 * $Id: eproject_projectelement_model_update.php,v 1.3 2005/03/23 04:27:08 jan Exp $
 */

require_once($system_path.'basic_model.php');

class eproject_projectelement_model_update extends basic_model_update {

	function model() {
		parent::model();
		
		$pobj = owRead($this->objectid[0]);
		$pobj->setfilter_search('dato2', '', EQUAL);		
		$pobj->listobjects($pobj->getParentId());
		if ($pobj->elementscount == 0) {
			$parobj = owRead($pobj->getParentId());
			$parobj->updateobject(array('status'=>CASE_CLOSED));
		}
	}
}

?>
