<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view.php');
require_once('basic_field.php');

class template_view_config extends basic_view {
	
	function view() {
		$obj = owRead($this->objectid[0]);
		$fields = array();
		$result = '';
		$arr = explode("\n",$obj->elements[0]['param']);
		$values = array();
		if ($obj->elements[0]['setting'])
			$values = unserialize($obj->elements[0]['setting']);
			
		foreach ($arr as $curarr) {
			$c = sizeof($fields);
			$t = array();
			$t = explode(";",$curarr);
			foreach ($t as $curt) {
				$pair = explode("=",$curt);
				$fields[$c][trim($pair[0])] = trim($pair[1]);
				if (trim($pair[0]) == 'name') $fieldstr .= trim($pair[1]).";";
			}
		}

		$result .= '<div class="metawindow">';
		$result .= '<div class="metatitle">'.$this->shadowtext('OPSÆTNING AF SKABELON').'</div>';
		$result .= '<form name="metaform" method="post" action="template_parse_config.php" enctype="multipart/form-data">';
		$result .= '<input type="hidden" name="fields" value="'.substr($fieldstr,0,-1).'">';

		$cnt = sizeof($fields);
		$i = 0;
		while ($i < $cnt) {
			if (is_string($fields[$i]['inputtype'])) {
				require_once('core/field/'.$fields[$i]['inputtype'].'.field.php');
				$s = $fields[$i]['inputtype'].'field';
				$fields[$i]['obj'] = new $s;
				$fields[$i]['type'] = F_LITERAL;
				$fields[$i]['obj']->setName($fields[$i]['name']);
				if (!empty($fields[$i]['relation'])) $fields[$i]['obj']->setRelation($fields[$i]['relation']);
			}

			$fieldobj = new basic_field($this);
			$result .= $fieldobj->parseField($fields[$i],$values[$fields[$i]['name']]);
			$i++;
		}

		$result .= '<input name="submit" type="submit" class="mformsubmit" value="GEM">';
		$result .= '</form>';
		$result .= '</div>';
		
		return $result;
	}
}
?>