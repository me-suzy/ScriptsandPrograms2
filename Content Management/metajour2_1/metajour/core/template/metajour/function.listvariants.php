<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

/**
 * Smarty plugin
 * Lists all variants and their language for the supplied objectid
 */
function smarty_function_listvariants($params, &$smarty) {

	if (isset($params['objectid'])) {
		
		$obj = owRead($params['objectid']);
		
		if (!empty($params['assign'])) {
			$smarty->assign($params['assign'],$obj->getvariantslang());
		}
	}
}

?>