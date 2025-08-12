<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

function smarty_function_usersonline($params, &$smarty) {
	$uh = &getUserHandler();
	$db = &getDbConn();
	$userids = $db->getCol('SELECT DISTINCT userid FROM document_statistics WHERE site = '.$uh->getSite().' AND timestamp > date_sub(now(), INTERVAL 10 MINUTES )');
	$arr = array();
	if (is_array($userids)) {
		foreach ($userids as $id) {
			$obj = owRead($id);
			$arr = $obj->elements[0];
		}
	}
	if (!empty($params['assign'])) {
		$smarty->assign($params['assign'],$arr);
	}
}

?>