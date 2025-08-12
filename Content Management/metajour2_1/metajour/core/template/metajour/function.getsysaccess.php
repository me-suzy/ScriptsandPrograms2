<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

function smarty_function_getsysaccess($params, &$smarty) {
	//determine if the current user has access to this object,
	//when and if he logs into the system
	
	$result = 0;
	
	if (isset($params['objectid']) && !empty($params['assign'])) {

		$obj = owRead($params['objectid']);
		
		if ($obj) {
			//force check as system-user - not as webuser
			$res = $obj->resolveAccess(true);
			$uh =& getUserHandler();
			if (in_array($uh->getObjectId(),$res)) {
				$result = 1;
			}
		}
		$smarty->assign($params['assign'],$result);
	}
}

?>