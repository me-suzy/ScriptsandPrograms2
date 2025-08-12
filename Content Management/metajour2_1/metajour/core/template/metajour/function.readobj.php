<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

function smarty_function_readobj($params, &$smarty) {

	if (isset($params['objectid'])) {
		
		if ($params['expanded']) {
			if (!empty($params['assign'])) {
				$tmp = owReadTextual($params['objectid']);
				foreach ($tmp as $c) {
					$arr[$c['name']] = $c;
				}
				$smarty->assign($params['assign'],$arr);
			}
		} else {
			$obj = owRead($params['objectid']);
			
			if (!empty($params['assign'])) {
				$res = $obj->elements[0];
				$res['object']['category'] =  $obj->getCategory();
				$smarty->assign($params['assign'],$res);
			}
		}
		
	/**
	 * @todo Make option to read sub-elements
	 */
	}
}

?>