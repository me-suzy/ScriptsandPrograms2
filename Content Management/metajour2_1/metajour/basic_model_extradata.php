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

class basic_model_extradata extends basic_model {

	function model() {
		foreach($this->objectid as $curid) {
			$obj = owRead($curid);
			$edobj = owNew('extradata');
			$id = $edobj->locatebyname($obj->type);
			if ($id) {
				$edobj->readobject($id);
	
				foreach ($edobj->elements[0]['fieldname'] as $val) {
					if (isset($this->data[$val])) {
						$arr[$val] = $this->data[$val];
					}
				}
				$obj->setmetadata($arr);
			}
		}
	}
}

?>