<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_combi.php');

class profile_view_combi extends basic_view_combi {

	function listicombinedheader($allviews) {
		$result = "<tr><td></td>";
		foreach($allviews as $view) {
			$result .= '<td><img src="profile_image.php?txt='.viewDescription($view).'"></td>';
		}
		$result .= "</tr>";	
		return $result;		
	}

	function listicombined($arr,$type,$allviews,$desc) {
		$result = "<tr><td>".$desc."</td>";
		$arr = array_flip($arr);
		foreach($allviews as $view) {
			if (isset($arr[$view])) {
				$checked = '';
				if ($this->_obj->elements[0][$type][$view]) $checked = ' CHECKED';
				$result .= '<td><input type="checkbox" name="fielddata[]" value="'.$type.'#'.$view.'" '.$checked.'></td>';
			} else {
				$result .= "<td></td>";				
			}
		}
		$result .= "</tr>";	
		return $result;		
	}

	function listi($arr,$type,$allviews) {
		$result = "<table id=$type><tr><td><strong>".owDatatypeDesc($type)."</strong></td></tr>";
		$arr = array_flip($arr);
		foreach($allviews as $view) {
			if (isset($arr[$view])) {
				$checked = '';
				if ($this->_obj->elements[0][$type][$view]) $checked = ' CHECKED';
				$result .= '<tr><td>'.viewDescription($view).'</td><td><input type="checkbox" name="fielddata[]" value="'.$type.'#'.$view.'" '.$checked.'></td></tr>';
			} else {
				#$result .= "<tr><td></td></tr>";				
			}
		}
		$result .= "</table>";	
		return $result;		
	}
	
	function customFields() {

		$arrdtmp = owListExtensions(true);
		foreach ($arrdtmp as $type) {
			if (owIsExtendedDatatype($type)) $arrdt[] = $type;
		}
		$arr = array_merge(owListCore(true), $arrdt);

		$allviews = array();
		foreach ($arr as $type) {
			if (owTry($type)) {
				$obj = owNew($type);
				$allviews = array_merge($obj->getviews(),$allviews);
			}
		}
		
		$allviews = array_unique($allviews);
		
		$arr = array_flip($arr);
		foreach ($arr as $key => $val) {
			$appdesc = owGetAppDescription(owGetDatatypeApp($key));
			if ($appdesc != '') $appdesc = ' ('.$appdesc.')';			
			$arr[$key] = owDatatypeDesc($key).$appdesc;
		}
		asort($arr);
		if ($this->data['combined'] == 1) {
			$result .= '<script type="text/javascript">function hideall() {';
			foreach ($arr as $type => $desc) {
				$result .= "document.getElementById('$type').style.display = 'none';";
			}
			$result .= '}</script>';
			$result .= '<script type="text/javascript">function showall() {';
			foreach ($arr as $type => $desc) {
				$result .= "document.getElementById('$type').style.display = 'block';";
			}
			$result .= '}</script>';

			$result .= '<select onchange="if (this.selectedIndex != 0) { hideall(); document.getElementById(this.options[this.selectedIndex].value).style.display = \'block\';} else {showall();}">';
			$result .= '<option value="">VIS ALLE</option>';
			foreach ($arr as $type => $desc) {
				$result .= '<option value="'.$type.'">'.$desc.'</option>';
			}
			$result .= '</select>';

			foreach ($arr as $type => $desc) {
				if (owTry($type)) {
					$obj = owNew($type);
					$result .= $this->listi($obj->getviews(),$type,$allviews);
				}
			}
		} else {
			$result .= "<table>";
			$result .= $this->listicombinedheader($allviews);
			foreach ($arr as $type => $desc) {
				if (owTry($type)) {
					$obj = owNew($type);
					$result .= $this->listicombined($obj->getviews(),$type,$allviews,$desc);
				}
			}
			$result .= $this->listicombinedheader($allviews);
			$result .= "</table>";
		}
		#echo '<a href="'.$this->returnMeUrl().'combined=1">Kompakt</A><br>';
		return $result;
	}

}

?>