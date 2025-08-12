<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

function smarty_function_mostpopular($params, &$smarty) {
	$uh = &getUserHandler();
	$db = &getDbConn();
	
	$res = $db->getRow('SELECT unix_timestamp(lasttableupdate) as lastunix, unix_timestamp() as nowunix from document_count limit 0,1');
	if (empty($res) || $res['lastunix'] < $res['nowunix'] - 86400) {
		$db->Execute('REPLACE INTO document_count select pageid,count(*),now() from document_statistics group by pageid');
		$db->Execute('UPDATE document_count set lasttableupdate = now()');
	}
	$obj = owNew('document');
	$obj->filter_specialpurpose = 'doccount';
	$obj->setsort_col('numviews');
	$obj->setsort_way('desc');
	$obj->listobjects();
	for ($i=0;$i<5 && $i<$obj->elementscount;$i++) {
		$arr[] = $obj->elements[$i];
	}
	if (!empty($params['assign'])) {
		$smarty->assign($params['assign'],$arr);
	}
}

?>