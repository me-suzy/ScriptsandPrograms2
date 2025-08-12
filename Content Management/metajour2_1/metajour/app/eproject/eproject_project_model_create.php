<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eProject
 * @subpackage model
 * $Id: eproject_project_model_create.php,v 1.5 2005/04/07 06:06:52 jan Exp $
 */

require_once($system_path.'basic_model_create.php');

class eproject_project_model_create extends basic_model_createobject {

	function model() {
		parent::model();

		for ($i = 0; $i < sizeof($this->data['aktiv']); $i++) {
			$index = $this->data['aktiv'][$i];
			$cobj = owNew('projectelement');
			$arr['name'] = $this->data['navn'][$index];
			$arr['dato1'] = $this->data['afsluttet'][$index];
			$arr['dato2'] = '';
			$arr['messageto'] = $this->data['messageto'][$index];
			$cobj->createobject($arr,$this->_obj->getObjectId());
			unset($cobj);
		}
	}

}

?>
