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

class basic_model_createcopy extends basic_model {
	
	function copyvariants($sourceid, $targetid) {
		$source = owRead($sourceid);
		$arr = $source->getVariants();
		if (!empty($arr)) {
			foreach ($arr as $cur) {
				$variant = owRead($cur);
				$targetvariant = owNew($variant->getType());
				$targetvariant->createObject($variant->elements[0]);
				$targetvariant->setVariantOf($targetid);
				$targetvariant->setLanguage($variant->getLanguage());

				$targetvariant->setVariantFields($variant->getVariantFields());
			}
		}
	}
	
	function recurse($sourceid, $targetid) {
		$source = owRead($sourceid);
		$arr = $source->getchilds();
		if (!empty($arr)) {
			foreach ($arr as $order) {
				$sourcechild = owRead($order);
				$targetchild = owNew($sourcechild->getType());
				$targetchild->createObject($sourcechild->elements[0],$targetid);
				if ($sourcechild->hasVariant()) $this->copyVariants($sourcechild->getObjectId(),$targetchild->getObjectId());
				if ($sourcechild->haschild()) {
					$this->recurse($sourcechild->getObjectId(),$targetchild->getObjectId());
				}
			}
		}
	}
	
	function model() {
		foreach($this->objectid as $curid) {
			$source = owRead($curid);
			$source->elements[0]['name'] = "Copy of ".$source->elements[0]['name'];
			$target = owNew($source->getType());
			$target->createObject($source->elements[0],$source->getParentId());
			if ($source->hasVariant()) $this->copyVariants($source->getObjectId(),$target->getObjectId());
			if ($source->haschild()) {
				$this->recurse($source->getObjectId(),$target->getObjectId());
			}
		}
	}
	
}
?>