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

class sys_view_import extends basic_view {

	function loadLanguage() {
		basic_view::loadLanguage();
		$this->loadLangFile('sys_view_import');
	}

	function titleBar() {
		return '<div class="metatitle">'.$this->shadowtext($this->gl('title')).'</div>';
	}

	function viewStart() {
		return '<div class="metawindow">';
	}
	
	function viewEnd() {
		return '</div>';
	}

	function beforeForm() {
		return '<div class="metabox">';
	}
	
	function afterForm() {
		return '</div>';
	}

	function categorySelection() {
		$field = new basic_field($this);
		$result = $this->makeField($this->gl('_label_category'),$field->categorySelection(NULL,'__categories__',$this->data['datatype']));
		return $result;
	}

	function webAccessSelection() {
		$field = new basic_field($this);
		$result = $this->makeField($this->gl('_label_webaccess'),$field->webAccessSelection(NULL,'__webaccess__'));
		return $result;
	}

	function sysAccessSelection() {
		$field = new basic_field($this);
		$result = $this->makeField($this->gl('_label_sysaccess'),$field->sysAccessSelection(NULL,'__sysaccess__'));
		return $result;
	}

	function view() {
		$result .= $this->viewStart();
		$result .= $this->titleBar();
		$result .= $this->beforeForm();
		$result .= '<form name="metaform" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" onsubmit="return validateForm(this);" style="margin: 0px; padding: 0px;">';
		$result .= '<input type="hidden" name="_DONTCONVERT_" value="1">';
		$result .= $this->returnMePost();
		if ($this->data['step'] == '2') {
			$result .= '<input type="hidden" name="step" value="3">';
		} elseif(!isset($this->data['step'])) {
			$result .= '<input type="hidden" name="step" value="2">';
		}

		if ($this->data['step'] == '3') {
			if ($_FILES['__uploadfile__']['tmp_name']) {
				$obj = owNew($this->data['datatype']);
				$cols = $obj->getColumns();
				$handle = fopen($_FILES['__uploadfile__']['tmp_name'], "r");
				while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
					$i = 0;
					$arr = array();
					foreach ($cols as $col) {
						$arr[$col['name']] = $data[$i];
						$i++;
					}
					$obj->createObject($arr);
					if (isset($this->data['__categories__'])) {
						$obj->setcategory($this->data['__categories__']);
					}
			
					if (isset($this->data['__webaccess__']) || isset($this->data['__sysaccess__'])) {
						$obj->setaccess($this->data['__webaccess__'],$this->data['__sysaccess__']);
					}
				}
				fclose($handle);
				@unlink($_FILES['__uploadfile__']['tmp_name']);
			}			
		} elseif ($this->data['step'] == '2') {
			$obj = owNew($this->data['datatype']);
			$arr = $obj->getColumns();
			$s = '';
			foreach ($arr as $cur) $s .= $cur['name'].';';
			$result .= $this->makeField($this->gl('text_1'),$s);
			$result .= '<input type="hidden" name="datatype" value="'.$this->data['datatype'].'">';
			$result .= $this->makeField($this->gl('text_2'),'<input type="file" name="__uploadfile__">');
		} elseif(!isset($this->data['step'])) {
			$field = new basic_field($this);
			$result .= $field->parsefield(array('name' => 'datatype', 'inputtype' => UI_CLASS, 'label' => 'Tabel'),'',true);
		}
		$result .= $this->returnviewpost($this->view);
		$result .= '<div style="padding-bottom: 14px;">';
		$result .= '<input id="submit1" name="submit1" type="submit" class="mformsubmit" value="'.$this->gl('text_3').'">';
		$result .= '</div>';
		if ($this->data['step'] == '2') {
			$result .= $this->categorySelection();
			$result .= $this->webAccessSelection();
			$result .= $this->sysAccessSelection();
		}
		$result .= '</form>';
		$result .= '<br><br><br>';
		$result .= $this->afterForm();
		$result .= $this->viewEnd();;
		return $result;
	}
}

?>