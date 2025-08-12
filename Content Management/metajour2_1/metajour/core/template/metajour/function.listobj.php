<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage smarty
 */

function smarty_function_listobj($params, &$smarty) {

	// taking care of silly inconsistence in parameter naming convention
	if (isset($params['otype'])) $params['type'] = $params['otype'];

	if (isset($params['type'])) {
		
		$obj = owNew($params['type']);
		$obj->setfilter_getname(true);
		
		if (isset($params['searchcol'])) {
			$cols = explode(',',$params['searchcol']);
			$texts = explode(',',$params['search']);
			$types = explode(',',strtoupper($params['searchcomp']));
			$i = 0;
			foreach ($cols as $col) {
				$typval = LIKE;
				if (isset($types[$i])) {
					switch ($types[$i]) {
						case "LIKE" : $typval = LIKE; break;
						case "LIKESTART" : $typval = LIKESTART; break;
						case "LIKEEND" : $typval = LIKEEND; break;
						case "GREATER" : $typval = GREATER; break;
						case "GREATEREQUAL" : $typval = GREATEREQUAL; break;
						case "LESS" : $typval = LESS; break;
						case "LESSEQUAL" : $typval = LESSEQUAL; break;
						case "EQUAL": $typval = EQUAL; break;
						case "NOTEQUAL": $typval = NOTEQUAL; break;
					}
				}
				$obj->setfilter_advsearch($col,$texts[$i],$typval);
				$i++;
			}
		}
		
		if (isset($params['sortcol'])) {
			$obj->setsort_col($params['sortcol']);
			$obj->setsort_way($params['sort']);
		}
		
		if (isset($params['category'])) {
			$cobj = owNew('category');
			if ($cobj->readObjectByName($params['category'])) {
				$obj->setfilter_category($cobj->getObjectId());
			}
		}
		
		if (isset($params['categoryid'])) {
			$obj->setfilter_category($params['categoryid']);
		}

		if (isset($params['createdby'])) {
			$obj->setfilter_createdby($params['createdby']);
		}
		
		if (isset($params['byparentid'])) {
			$obj->readallobjectsbyparentid();
		} else {
			if (isset($params['parentid'])) {
				$obj->listObjects($params['parentid']);
			} else {
				$obj->listObjects();			
			}
		}

		if (!empty($params['assign'])) {
			$smarty->assign($params['assign'],$obj->elements);
		}

		if (!empty($params['otypedesc'])) {
			$smarty->assign($params['otypedesc'],owDatatypeColsDesc($params['type']));
		}
	}
}

?>