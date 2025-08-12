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

class basic_model_createvariant extends basic_model {

	function model() {
		foreach($this->objectid as $curid) {
			$obj = owRead($curid);
			if ($obj->isVariant()) {
				$curid = $obj->getVariantOf();
			}
			$obj->createObject(array());
			$obj->setVariantOf($curid);
			$obj->setLanguage($this->data['language']);
			$this->userhandler->setLastVariantLanguage($this->data['language']);
		}
	}
}

?>