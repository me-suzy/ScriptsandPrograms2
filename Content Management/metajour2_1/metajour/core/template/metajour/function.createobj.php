<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

function smarty_function_createobj($params, &$smarty) {

	if (isset($params['type'])) {
		
		$obj = owNew($params['type']);
		if (isset($params['parentid'])) {
			$obj->createobject($params,$params['parentid']);
		} else {
			$obj->createobject($params);
		}
		$extra = owDatatypeExtraCols($params['type']);
		if (!empty($extra)) {
			$arr = packData($extra, $params);
			$obj->setExtraData($arr);
		}
		if ($params['category']) {
			$categories = explode(',',$params['category']);
			$obj->setCategory($categories);
		}

		if (!empty($params['assign'])) {
			$obj = owRead($obj->getobjectid());
			$smarty->assign($params['assign'],$obj->elements[0]);
		}

	}
}

?>