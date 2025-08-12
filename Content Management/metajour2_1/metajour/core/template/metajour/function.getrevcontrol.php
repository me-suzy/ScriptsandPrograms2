<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

function smarty_function_getrevcontrol($params, &$smarty) {
	//determine if revision control is activated,
	global $CONFIG;
	$result = 0;
	
	if (isset($params['objectid']) && !empty($params['assign'])) {

		$obj = owRead($params['objectid']);
		
		if ($obj) {
			$uh =& getUserHandler();
			if (!is_array($CONFIG['revisioncontrol'])) {
				if ($obj->elements[0]['object']['useapp'] == 'edocument' && $uh->getRevisionControl())
					$result = 1;
			} else {
				if (in_array($obj->elements[0]['object']['useapp'],$CONFIG['revisioncontrol']));
			}
		}
		$smarty->assign($params['assign'],$result);
	}
}

?>