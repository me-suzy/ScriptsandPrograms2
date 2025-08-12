<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

function smarty_function_contains($params, &$smarty) {

	extract($params);

	static $retval = null;

	if (empty($needle)) {
		$smarty->trigger_error("contains: missing 'needle' parameter");
        return;
    }

	if (!in_array('haystack', array_keys($params))) {
        $smarty->trigger_error("contains: missing 'haystack' parameter");
        return;
    }

	$retval = in_array($needle, $haystack);

	if (isset($assign)) {
		$smarty->assign($assign, $retval);
		$retval = null;
	}

	return $retval;

}

?>
