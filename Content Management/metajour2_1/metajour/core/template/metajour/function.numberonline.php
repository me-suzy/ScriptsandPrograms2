<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

function smarty_function_numberonline($params, &$smarty) {
	$uh = &getUserHandler();
	$db = &getDbConn();
	$res = $db->Execute('SELECT DISTINCT userid FROM document_statistics WHERE site = '.$uh->getSite().' AND timestamp > date_sub(now(), INTERVAL 10 MINUTE )');
	if (!empty($params['assign'])) {
		$smarty->assign($params['assign'],$res->RecordCount());
	}
}

?>