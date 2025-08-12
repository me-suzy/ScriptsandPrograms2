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

class documentsection_view_combidialog extends basic_view_combi {

/*	function combiButtonBar() {
		if (!$this->_obj->isVariant()) return '';
		$result .= '<div id="buttonbar" style="margin-bottom: 5px;">';
		$result .= $this->edit_activate($this->objectid[0]);
		$result .= '</div>';
		return $result;
	}*/

	function parseFields() {
		$fieldobj = new basic_field($this);
		$cols = owDatatypeColsDesc($this->otype);
		unset($cols['content']);
		$result .= $fieldobj->parseFieldsForm($cols,$this->_obj->elements[0],$this->_obj->isVariant(),$this->_obj->getVariantFields());
		return $result;
	}

	function afterForm() {
		$result = '
		<script type="text/javascript">
		var extensiondropdown = document.getElementById("extension");
		extensiondropdown.onchange = function() {
			this.form._ret.value = this.form.view.value;
			this.form.view.value="combidialog";
			this.form.submit();
		}
		</script>
		';
		return $result;
	}
	
	function submitButtons() {
		$result .= '<div style="padding-bottom: 14px;">';
		$result .= '<input id="submit1" name="submit1" type="submit" class="mformsubmit" value="'.$this->gl('button_save').'">';
		$result .= '<input id="submit2" name="submit2" type="button" onclick="this.form._ret.value = this.form.view.value; this.form.view.value=\'combidialog\'; this.form.submit();" class="mformsubmit" value="'.$this->gl('button_apply').'">';
		$result .= $this->customButtons();
		$result .= '</div>';
		return $result;
	}
	
	function customButtons() {
		$result = '';
		if ($this->_obj->elements[0]['extension'] != '' && $this->_obj->elements[0]['configset'] != '' && owIsExtendedDatatype($this->_obj->elements[0]['extension'])) {
			$obj = owNew($this->_obj->elements[0]['extension']);
			$id = $obj->locateByName($this->_obj->elements[0]['configset']);
			if ($id) $result = '<input class="mformsubmit" value="Opsætning" type="button" onclick="'.$this->ModalWindowLarge('',$id,'','combidialog','jswindowclose').'">';
		}
		return $result;
	}	

}

?>