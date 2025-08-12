<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

global $system_path;
require_once($system_path.'basic_context.php');
require_once($system_path.'basic_control.php');

function smarty_function_model($params, &$smarty) {

	if (isset($params['otype']) || isset($params['objectid'])) {

		if (isset($params['app'])) {
			$uh = &getUserHandler();
			$oldapp = $uh->getAppName();
			$uh->setAppName($params['app']);
		}
		$context = getcontext($otype);		
		$controller = getcontrol($params['otype'],array($params['objectid']),$context);
		
		$models = strpos($params['model'],',') !== false ? explode(',', $params['model']) : array($params['model']);
		$controller->model($models);
		
		if (isset($params['result'])) {
			$uh = &getUserHandler();
			$oid = $uh->getObjectIdStack();
			if (is_array($oid)) $smarty->assign($params['result'],$oid[0]);
		}
		
		if (isset($params['app'])) {
			$uh->setAppName($oldapp);
		}
	}
}

?>