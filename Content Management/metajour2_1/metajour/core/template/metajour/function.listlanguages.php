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
 * Lists all different languages used for objects and variants in METAjour
 */
function smarty_function_listlanguages($params, &$smarty) {

	if (!empty($params['assign'])) {
		$smarty->assign($params['assign'],owGetLanguages());
	}
}

?>