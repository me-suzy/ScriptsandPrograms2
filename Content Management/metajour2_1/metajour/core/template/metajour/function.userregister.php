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
 * Name:     userpassword
 * Purpose:  handle userpasswords
 * -------------------------------------------------------------
 */
function smarty_function_userregister($params, &$smarty) {

	$error = false;
	
	$params['name'] = trim($params['name']);
	$params['password'] = trim($params['password']);
	$params['passwordagain'] = trim($params['passwordagain']);
	
	if (empty($params['name'])){
		if (!empty($params['assign'])){
			$smarty->assign($params['assign'],1);
		}
		$error = true;
	}
	if (empty($params['email'])){
		if (!empty($params['assign'])){
			$smarty->assign($params['assign'],2);
		}
		$error = true;
	}
	if (empty($params['password'])){
		if (!empty($params['assign'])){
			$smarty->assign($params['assign'],3);
		}
		$error = true;
	}
	if ($params['password'] != $params['passwordagain']){
		if (!empty($params['assign'])){
			$smarty->assign($params['assign'],4);
		}
		$error = true;
	}

	if (!empty($params['objectid']) && !$error) {
		$userhandler =& getUserHandler();

		$ug = owNew('usergroup');
		$id = $ug->locateByName('USER');

		$obj = owRead($params['objectid']);
		$groups = $obj->getGroupMemberships($params['objectid']);
		if (in_array($id, $groups)) {
			if (!empty($params['assign'])){
				$smarty->assign($params['assign'],6);
			}
			$error = true;
		}
	}
	if (!empty($params['name']) && !$error){
		$obj = owNew('user');
		if ($obj->locateByName($params['name'])) {
			if (!empty($params['assign'])) {
				$smarty->assign($params['assign'],5);
			}
			$error = true;
		}
	}
		
	if (!empty($params['objectid']) && !$error) {
		$obj = owRead($params['objectid']);

		$obj->setsyshidden(false);

		$ug = owNew('usergroup');
		$id = $ug->locateByName('USER');
		$ug->readObject($id);
		$ug->addMember($obj->getObjectId());

		$obj->updateObject($params);
		$extra = owDatatypeExtraCols('user');
		if (!empty($extra)) {
			$arr = packData($extra, $params);
			$obj->setExtraData($arr);
		}
		
		if (!empty($params['assign'])){
			$smarty->assign($params['assign'],0);
		}
		
		$userhandler =& getUserHandler();
		$userhandler->recognizeUser(true);
	}	
}

?>
