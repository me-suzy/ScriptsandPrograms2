<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

function smarty_function_getdocument($params, &$smarty) {

	if (isset($params['objectid'])) {
		
		$obj = owRead($params['objectid']);
		
		$arr = $obj->elements[0];
		
		$sections = owNew('documentsection');
		$sections->listobjects($params['objectid']);
		
		$arr['section'] = $sections->elements;
		
		if (!empty($params['assign'])) {
			$smarty->assign($params['assign'],$arr);
		}
		
	/**
	 * @todo Make option to read sub-elements
	 */
	}
}

?>