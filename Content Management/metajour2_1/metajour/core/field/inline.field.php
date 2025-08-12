<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage field
 */

require_once('field.php');

class inlinefield extends field {
	
	function formOut() {
		$result = '';
		static $jsadded = false;
		if (!$jsadded) {
$result = "
		<script type=\"text/javascript\">
		function createrow(name) {
			var table = document.getElementById(name);
			var tbody = table.getElementsByTagName('TBODY').item(0);
			var firstrow = table.getElementsByTagName('TR');
			var newrow = firstrow.item(1).cloneNode(true);
			var aReturn=newrow.getElementsByTagName('TD');
			var calendarid = '';
				
			for (i=0;i<aReturn.length;i++) {
				if (typeof(aReturn[i].childNodes(0).calendar) != 'undefined') {
					var uid = new Date().getTime();
					calendarid = aReturn[i].childNodes(0).id + uid;
					aReturn[i].childNodes(0).id = calendarid;
					aReturn[i].childNodes(2).id = 'button_' + calendarid;
				}
				
				if (aReturn[i].childNodes(0).tagName == 'INPUT') {
					aReturn[i].childNodes(0).value = '';
				}
				if (i == 0) {
					aReturn[i].childNodes(1).value = ''; //objectid
					aReturn[i].childNodes(2).value = 'N'; //status
				}
				if (aReturn[i].childNodes(0).tagName == 'SELECT') {
					aReturn[i].childNodes(0).value = 0;
				}
			}
			
			tbody.appendChild(newrow);
			
			if (calendarid != '') {
				Calendar.setup(
					{
						inputField : calendarid,
						ifFormat : \"%Y-%m-%d\",
						button : \"button_\" + calendarid
					}
				);
			}
		}

		function removerow(button) {
			var td = button.parentElement;
			var tr = td.parentElement;
			var tbody = tr.parentElement;
			if (tbody.children.length > 2) tbody.removeChild(tr);
		}
		</script>
		";
		$jsadded = true;
		}

		
		$obj = owNew($this->_fieldrelation);
		$obj->initLayout();
		
		if ($this->view->objectid[0]) {
			$obj->listobjects($this->view->objectid[0]);
		}
		if (empty($obj->elements)) $obj->elements[0] = array();

		$result .= '<table id="__'.$this->_fieldname.'__" style="" cellspacing="0" cellpadding="0">
		<tr>';
		foreach ($obj->prv_column as $field) {
			if ($field['obj'] && $field['obj']->visible()) {
				$result .= '<td align="center"><b>'.owLabel($this->_fieldrelation,$field['name']).'</b></td>';
			}
		}
		$result .= '</tr>';

		foreach ($obj->elements as $cur) {
			$result .= '<tr>';
			$i = 0;
			foreach ($obj->prv_column as $field) {
				$result .= '<td>';
				if ($field['obj']) {
					$field['obj']->setName('__'.$this->_fieldname.'__'.$field['obj']->_fieldname.'[]');
					$field['obj']->setValue($cur[$field['name']]);
					$field['obj']->setView($this->view);
					if (!empty($field['relation'])) $field['obj']->setRelation($field['relation']);
					if (!empty($field['validate'])) $field['obj']->setValidate($field['validate']);
					if (!empty($field['style'])) 		$field['obj']->addStyle($field['style']);
					if ($field['inputtype'] == 'checkbox') {
						$field['obj']->addStyle('background-color: ThreeDFace;');
					}
					if ($field['inputtype'] == 'relation') {
						$field['obj']->addStyle('width: 200px;');
					} else {
						$field['obj']->addStyle('width: 100px;');
					}
					if (!empty($field['comboarray'])) $field['obj']->setComboArray($field['comboarray']);
					if ($i == 0) {
						if ($field['inputtype'] == 'relation') {
							$field['obj']->setOnChange("if (fieldbefore == 0 && this.options[this.selectedIndex].value != '0') createrow('__".$this->_fieldname."__')");
							$field['obj']->setOnFocus("fieldbefore=this.options[this.selectedIndex].value");
						} else {
							$field['obj']->setOnChange("if (typeof(this.rowcreated) == 'undefined' || this.rowcreated == false) if (fieldbefore != this.value && this.value != '') { this.rowcreated = true; createrow('__".$this->_fieldname."__') };");
							$field['obj']->setOnFocus("fieldbefore=this.value");

						}
					}
					$result .= $field['obj']->output(IN_FORM);
					if ($i == 0) {
						$status = 'N';
						$oid = '';
						if ($cur['objectid'] != '') {
							$status = 'U';
							$oid = $cur['objectid'];
						}
						$result .= '<input type="hidden" name="__'.$this->_fieldname.'__objectid[]" value="'.$oid.'">';
						$result .= '<input type="hidden" name="__'.$this->_fieldname.'__status[]" value="'.$status.'">';
					}
					$result .= '</td>';
				}
				$i++;
			}
			$result .= '</tr>';
		}
		$result .= '</table>';
		$result .= '<hr>';
		return $result;
	}
	
	function listOut() {
		return '';
	}
	
	function viewOut() {
		$result = '';
		$obj = owNew($this->_fieldrelation);
		$obj->initLayout();
		$obj->listobjects($this->view->objectid[0]);
		if (empty($obj->elements)) $obj->elements[0] = array();

		$result .= '<table id="__'.$this->_fieldname.'__" style="" cellspacing="5" cellpadding="0">
		<tr>';
		foreach ($obj->prv_column as $field) {
			if ($field['obj'] && $field['obj']->visible()) {
				$result .= '<td align="left"><b>'.owLabel($this->_fieldrelation,$field['name']).'</b></td>';
			}
		}
		$result .= '</tr>';

		foreach ($obj->elements as $cur) {
			$result .= '<tr>';
			$i = 0;
			foreach ($obj->prv_column as $field) {
				$result .= '<td valign="bottom">';
				if ($field['obj'] && $field['obj']->visible()) {
					$field['obj']->setName('__'.$this->_fieldname.'__'.$field['obj']->_fieldname.'[]');
					$field['obj']->setValue($cur[$field['name']]);
					$field['obj']->setView($this->view);
					if (!empty($field['relation'])) $field['obj']->setRelation($field['relation']);
					if (!empty($field['validate'])) $field['obj']->setValidate($field['validate']);
					if (!empty($field['style'])) 		$field['obj']->addStyle($field['style']);
					if ($field['inputtype'] == 'relation') {
						$field['obj']->addStyle('width: 200px;');
					} else {
						$field['obj']->addStyle('width: 100px;');
					}
					if (!empty($field['comboarray'])) $field['obj']->setComboArray($field['comboarray']);
					$result .= $field['obj']->output(IN_VIEW);
					$result .= '</td>';
				}
				$i++;
			}
			$result .= '</tr>';
		}
		$result .= '</table>';
		$result .= '<hr>';
		return $result;
	}
	
}

?>