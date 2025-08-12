<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

function smarty_function_sql($params, &$smarty) {
	if (isset($params['driver'])) {
		$conn = &ADONewConnection($params['driver']); 
		$conn->PConnect($params['host'],$params['userid'],$params['password'],$params['database']);
		$conn->SetFetchMode(ADODB_FETCH_ASSOC);
		$arr = &$conn->GetAll($params['sql']); 
		if (!empty($params['assign'])) {
			$smarty->assign($params['assign'],$arr);
		}
	}
}

?>