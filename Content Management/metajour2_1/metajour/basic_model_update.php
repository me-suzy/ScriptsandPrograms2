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

class basic_model_update extends basic_model {

	function model() {
		foreach($this->objectid as $curid) {
			$obj = owRead($curid);
			$obj->updateObject($this->data);
			if (!$obj->isVariant()) {
				
				if (isset($this->data['__categories__'])) {
					$obj->setCategory($this->data['__categories__']);
				}
	
				if (isset($this->data['__webaccess__']) || isset($this->data['__sysaccess__'])) {
					$obj->setAccess($this->data['__webaccess__'],$this->data['__sysaccess__']);
				}
	
				$extra = owDatatypeExtraCols($this->otype);
				if (!empty($extra)) {
					$arr = packData($extra, $this->data);
					$obj->setExtraData($arr);
				}
			} else {
				$extra = owDatatypeExtraCols($this->otype);
				if (!empty($extra)) {
					$arr = packData($extra, $this->data);
					$obj->setExtraData($arr);
				}

				$cols = owDatatypeCols($this->otype);
				$varfields = array();
				foreach ($cols as $cur) {
					if (isset($this->data['__var__'.$cur['name']])) {
						$varfields[] = $cur['name'];
					}
				}
				if ($obj->getType() == 'documentsection') {
					if (isset($this->data['content'])) {
						$varfields = $obj->getVariantFields();
					}
					$varfields[] = 'content';
				}
				if (is_array($varfields)) {
					$obj->setVariantFields($varfields);
				}
			}
		}
	}
	
}

?>