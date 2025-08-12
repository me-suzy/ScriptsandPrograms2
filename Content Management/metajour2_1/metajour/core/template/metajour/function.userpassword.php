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
function smarty_function_userpassword($params, &$smarty) {
	if (empty($params['cmd'])) {
		$smarty->trigger_error("userpassword: missing cmd parameter");
		return;
	}

	if ($params['cmd'] == 'sendnew') {
		$obj = owNew('user');
		$obj->setfilter_search('email',$params['email'],EQUAL);
		$obj->listobjects();
		if (!empty($params['assign'])){
			$smarty->assign($params['assign'],1);
		}
		$newpass = substr(md5(date("l dS of F Y h:i:s A")),0,8);
		foreach($obj->elements as $curobj) {
			if (!empty($params['assign'])){
				$smarty->assign($params['assign'],0);
			}
			if (!empty($params['userobj'])){
				$smarty->assign($params['userobj'],$curobj);
			}
			if (!empty($params['newpassword'])){
				$smarty->assign($params['newpassword'],$newpass);
			}
			$nobj = owRead($curobj['objectid']);
			$nobj->updateobject(array('password' => $newpass));
		}
	} elseif ($params['cmd'] == 'change') {
		if (empty($params['username'])) {
			$smarty->trigger_error("userpassword: missing username parameter");
			return;
		}
		$obj = owNew('user');
		$id = $obj->locateByName($params['username']);
		if (!empty($params['assign'])){
			$smarty->assign($params['assign'],1);
		}
		if ($id > 0) {
			$obj->readObject($id);
			if (!empty($params['assign'])){
				$smarty->assign($params['assign'],0);
			}
			
			$userhandler =& getUserHandler();
			if (!$userhandler->correctLogIn($userhandler->getSite(), $params['username'], $params['password'])) {
				if (!empty($params['assign'])){
					$smarty->assign($params['assign'],2);
				}
			}
			
			if ($params['newpass1'] == $params['newpass2'] && $params['newpass1'] != "") {
				$obj->updateObject(array('password' => $params['newpass1']));
			} else {
				if (!empty($params['assign'])){
					$smarty->assign($params['assign'],3);
				}
			}
		}
	}	
}

?>
