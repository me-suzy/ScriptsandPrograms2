<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

/*
* Smarty plugin
* ------------------------------------------------------------- 
* File:     resource.object.php
* Type:     resource
* Name:     filter
* Purpose:  Fetches templates from the METAZO object database 
*           for use in object filters
* Copyright:Jan H. Andersen
* -------------------------------------------------------------
*/

	function smarty_resource_filter_source($tpl_name, &$tpl_source, &$smarty) {
		$obj = owNew('filter');
		if ($obj->readobject($tpl_name)) {
			$tpl_source = $obj->elements[0]['content'];
			return true;
		} else {
			return false;
		}
	}
	
	function smarty_resource_filter_timestamp($tpl_name, &$tpl_timestamp, &$smarty) {
		$obj = owNew('filter');
		if ($obj->readobject($tpl_name)) {
			$tpl_timestamp = strtotime($obj->elements[0]['object']['changed']);
			return true;
		} else {
			return false;
		}
	}
	
	function smarty_resource_filter_secure($tpl_name, &$smarty) {
		// assume all templates are secure
		return true;
	}
	
	function smarty_resource_filter_trusted($tpl_name, &$smarty) {
		// not used for templates
	}

?>