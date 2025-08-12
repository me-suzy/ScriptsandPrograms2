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
 * Type:     function
 * Name:     samecategory
 * Purpose:  return objects of same type in the same categories
 * -------------------------------------------------------------
 */
function smarty_function_samecategory($params, &$smarty) {
	if (!empty($params['objectid'])){
		$obj = owRead($params['objectid']);
		$arr = $obj->getcategory();
		$res = array();
		foreach($arr as $cur) {
			$obj->setfilter_category($cur['categoryid']);
			$obj->listobjects();
			$z = 0;
			while ($z < $obj->elementscount) {
				if ($obj->elements[$z]['objectid'] != $params['objectid']) $res[] = $obj->elements[$z]['objectid'];
				$z++;
			}
		}
		$res = array_unique($res);
		
		$z = 0;
		while ($z < sizeof($res)) {
			$obj->readobject($res[$z]);
			$res1[$z]['name'] = $obj->elements[0]['name'];
			$res1[$z]['objectid'] = $obj->elements[0]['objectid'];
			$z++;
		}
		if (!empty($params['assign'])){
			$smarty->assign($params['assign'],$res1);
		}
	}
}

?>