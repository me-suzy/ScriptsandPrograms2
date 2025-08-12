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
 * setvar
 *
 * Easier way to assign variables in templates.
 * 
 * Insted af {assign var="key" value="value"} do
 * {setvar key="value"}
 */
 
function smarty_function_setvar($params, &$smarty) {
 	$smarty->assign($params);
}

?>