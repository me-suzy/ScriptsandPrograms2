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

class user_view_combi extends basic_view_combi {

	function customFields() {
		$result .= '<div class="mformfieldset" style=""><div class="mformlabel" style="">'.$this->gl('_label_memberof').'</div><div class="mformfield" style="">';
		$field = new basic_field($this);
		$result .= $field->usergroupSelection($this->objectid[0],'__membership__');
		$result .= '</div></div>';
		return $result;
	}

	function webAccessSelection() {
	}

	function sysAccessSelection() {
	}
			
}

?>