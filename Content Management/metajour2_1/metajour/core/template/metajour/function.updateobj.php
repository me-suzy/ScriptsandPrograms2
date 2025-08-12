<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

function smarty_function_updateobj($params, &$smarty) {

	if (isset($params['objectid'])) {
		$obj = owRead($params['objectid']);
		if ($obj) {
			$obj->updateObject($params);
		}
	}
}

?>