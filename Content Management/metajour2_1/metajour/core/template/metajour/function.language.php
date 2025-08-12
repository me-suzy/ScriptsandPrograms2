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
 * Name:     language
 * Purpose:  
 * -------------------------------------------------------------
 */
function smarty_function_language($params, &$smarty) {
	if (!empty($params['assign'])) {
		$db =& getDbConn();
		$userhandler =& getUserHandler();
		$r = $db->getcol("select distinct language from object where site = '".$userhandler->getSite()."' and deleted = 0 and language <> ''");
		$res = '';
		foreach ($r as $language) {
			$select = '';
			if ($userhandler->getLanguage() == $language) $select = ' SELECTED';
			$res .= '<option value="'.$language.'"'.$select.'>'.$language.'</option>'."\n";
		}
		$smarty->assign($params['assign'],$res);
	}	
}

?>
