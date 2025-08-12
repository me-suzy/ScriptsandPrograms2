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

function smarty_function_view($params, &$smarty) {

	if (isset($params['otype']) || isset($params['objectid'])) {

		if (isset($params['app'])) {
			$uh = &getUserHandler();
			$oldapp = $uh->getAppName();
			$uh->setAppName($params['app']);
		}
		$context = getcontext($params['otype']);
		$controller = getcontrol($params['otype'],array($params['objectid']),$context);

		if (!empty($params['assign'])) {
			$smarty->assign($params['assign'],$controller->view(array($params['view'])));
		}

		if (!empty($params['header'])) {
			$smarty->assign($params['header'],$controller->context->header_content());
		} else {
			$uh =& getUserHandler();
			$uh->addHeaderCache(-255,$controller->context->header_content());
		}
		
		if (!empty($params['footer'])) {
			$smarty->assign($params['footer'], $controller->context->footer_content());	
		}
		
		if (isset($params['app'])) {
			$uh->setAppName($oldapp);
		}
	}
}

?>