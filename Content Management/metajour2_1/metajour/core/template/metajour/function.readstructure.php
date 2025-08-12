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
require_once($system_path . "extension/menu/menu.class.php");

function smarty_function_readstructure($params, &$smarty) {

	if (isset($params['name'])) {
		$obj = owNew('structure');
		$params['parentid'] = $obj->locateByName($params['name']);
	}

	if (isset($params['parentid'])) {
		$tree = new ext_menu();
		$tree->cnt = 0;
		$tree->parentcnt = 0;
		$tree->getstructure($params['parentid'], 1, 0, array());
		if (!empty($params['assign'])){
			$smarty->assign($params['assign'],$tree->extresult);
		}
	}
	
}

?>