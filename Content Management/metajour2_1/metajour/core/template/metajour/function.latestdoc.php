<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

function smarty_function_latestdoc($params, &$smarty) {
	$obj = owNew('document');
	$obj->setsort_col('created');
	$obj->setsort_way('desc');
	$obj->listobjects();
	$arr = array();
	for ($i=0;$i<5 && $i<$obj->elementscount;$i++) {
		$arr[] = $obj->elements[$i];
	}
	if (!empty($params['assign'])) {
		$smarty->assign($params['assign'],$arr);
	}
}

?>