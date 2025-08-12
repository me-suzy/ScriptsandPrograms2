<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

function smarty_function_getpermissions($params, &$smarty) {

	if (isset($params['objectid']) && !empty($params['assign'])) {

		$obj = owRead($params['objectid']);
		if ($obj) {
			$result = array();
			$result[0]['readers'] = array();
			$result[0]['writers'] = array();

			$userobj = owNew('user');
			$access = $obj->getAccess();
			if (is_array($access)) {
				foreach($access as $row) {
					if ($row['user_read']) {
						$userobj->readObject($row['user_read']);
						if (!empty($userobj->elements[0]['realname'])) {
							$name = $userobj->elements[0]['realname'];
						} else {
							$name = $userobj->getName();
						}
						$result[0]['readers'][] = $name;
					}

					if ($row['user_write']) {
						if (!empty($userobj->elements[0]['realname'])) {
							$name = $userobj->elements[0]['realname'];
						} else {
							$name = $userobj->getName();
						}
						$result[0]['writers'][] = $name;
					}
				}
			}
			
			if (count($result[0]['writers']) == 0) {
				$userobj->readobject($obj->getCreatedBy());
				if (!empty($userobj->elements[0]['realname'])) {
							$name = $userobj->elements[0]['realname'];
						} else {
							$name = $userobj->getName();
						}
				$result[0]['writers'][] = $name;
			}
			
			$smarty->assign($params['assign'],$result);
		}
	}
}

?>