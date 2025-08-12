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
 * Name:     assign
 * Purpose:  assign a value to a template variable
 * -------------------------------------------------------------
 */
function smarty_function_getsizeof($params, &$smarty)
{
    extract($params);

    if (empty($assign)) {
        $smarty->trigger_error("assign: missing 'assign' parameter");
        return;
    }

    if (!in_array('value', array_keys($params))) {
        $smarty->trigger_error("assign: missing 'value' parameter");
        return;
    }
    $smarty->assign($assign, sizeof($value));
}

/* vim: set expandtab: */

?>
