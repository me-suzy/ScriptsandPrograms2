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

class basic_view_edit extends basic_view {

	var $_obj;
	var $_objcols;
	var $submittop;
	var $_editable = true;
	
	function basic_view_edit() {
		$this->basic_view();
		$this->submittop = 13;
	}

	function loadLanguage() {
		basic_view::loadLanguage();
		$this->loadLangFile('basic_view_edit');
	}

	function edit_create($objectid) {
		$relationurl = '';
		if ($this->relcol) $relationurl = '_relcol=' . $this->relcol . '&_relval=' . $this->relval;
		if ($this->canView('create')) return $this->button('create.png',$this->gl('img_create'),$this->callgui($this->otype,'','','create','','combi',$this->parentid, $relationurl)); else return '';
	}

	function edit_tree($objectid) {
		if ($this->CanView('list') && empty($this->parentid) && empty($this->relcol))
			return $this->button('tree.png',$this->gl('img_hierarchy'),$this->callgui($this->otype,'','','split')); else return '';
	}

	function edit_createvariant($objectid) {
		$_obj = owRead($objectid);
		if ($this->CanView('createvariant') && !$_obj->isVariant()) return $this->buttononclick('createvariant.png',$this->gl('img_createvariant'),$this->ModelessDialog('',$objectid,'','createvariant','jscallerreload,jswindowclose'));
	}

	function edit_delete($objectid) {
		if ($this->CanView('delete')) return $this->buttononclick('delete.png',$this->gl('img_delete'),$this->ModelessDialog('',$objectid,'','delete','jswindowclose'));
	}

	function edit_activate($objectid) {
		if ($this->_obj->isVariant()) {
			if ($this->CanView('delete')) return $this->buttononclick('active.png','',$this->ModelessDialog('',$objectid,'','active','jswindowclose'));
		}
	}

	function edit_list($objectid) {
		$relationurl = '';
		if ($this->relcol) $relationurl = '_relcol=' . $this->relcol . '&_relval=' . $this->relval;
		$_obj = owRead($objectid);
		if ($this->CanView('list')) return $this->button('list.png',$this->gl('img_list'),$this->callgui($this->otype,'','','list','','',$_obj->getParentId(), $relationurl));
	}

	function edit_start($objectid) {
/*		$nextid = $_SESSION['guitemp'][$this->otype]['list'][0];
		if ($this->CanView('list') && empty($this->parentid) && empty($this->relcol)) {
			if (isset($_SESSION['guitemp'][$this->otype]['list'])) {
					return $this->button('start.png',$this->gl('img_start'),$this->callgui('',$nextid,'',$this->view));
			}
		}*/
	}

	function edit_previous($objectid) {
		if ($this->CanView('list') && empty($this->parentid) && empty($this->relcol)) {
			if (isset($_SESSION['guitemp'][$this->otype]['list'])) {
				$mainid = $objectid;
				$_obj = owRead($objectid);
				if ($_obj->isVariant()) $mainid = $_obj->getVariantOf();
				$index = array_search($mainid,$_SESSION['guitemp'][$this->otype]['list']);
				if ($index !== false && $index > 0) {
					$nextid = $_SESSION['guitemp'][$this->otype]['list'][$index-1];
					if ($_obj->isVariant()) {
						$nextobj = owRead($nextid);
						$nextvariant = $nextobj->getVariants($_obj->getLanguage());
						if (!empty($nextvariant)) $nextid = $nextvariant[0];
					}
					return $this->button('prev.png',$this->gl('img_previous'),$this->callgui('',$nextid,'',$this->view,'','',$this->parentid));
				}
			}
		}
	}
	
	function edit_next($objectid) {
		if ($this->CanView('list') && empty($this->parentid) && empty($this->relcol)) {
			if (isset($_SESSION['guitemp'][$this->otype]['list'])) {
				$mainid = $objectid;
				$_obj = owRead($objectid);
				if ($_obj->isVariant()) $mainid = $_obj->getVariantOf();
				$index = array_search($mainid,$_SESSION['guitemp'][$this->otype]['list']);
				if ($index !== false && $index < sizeof($_SESSION['guitemp'][$this->otype]['list'])-1) {
					$nextid = $_SESSION['guitemp'][$this->otype]['list'][$index+1];
					if ($_obj->isVariant()) {
						$nextobj = owRead($nextid);
						$nextvariant = $nextobj->getVariants($_obj->getLanguage());
						if (!empty($nextvariant)) $nextid = $nextvariant[0];
					}
					return $this->button('next.png',$this->gl('img_next'),$this->callgui('',$nextid,'',$this->view,'','',$this->parentid));
				}
			}
		}
	}

	function edit_end($objectid) {
/*		$index = count($_SESSION['guitemp'][$this->otype]['list']);
		$nextid = $_SESSION['guitemp'][$this->otype]['list'][$index-1];
		if ($this->CanView('list') && empty($this->parentid) && empty($this->relcol)) {
			if (isset($_SESSION['guitemp'][$this->otype]['list'])) {
					return $this->button('end.png',$this->gl('img_end'),$this->callgui('',$nextid,'',$this->view));
			}
		}*/
	}
	
	function edit_variants($objectid) {
		$_obj = owRead($objectid);
		$str = '';
		if ($_obj->hasVariant() || $_obj->getVariantOf() != 0) {
		$str = '<select style="position: relative; top: -15px; margin-left: 10px; width: 150px;" onChange="location.href = \''.$this->ReturnMeUrl().'objectid=\' + this.options[this.selectedIndex].value;">';
		if ($_obj->getVariantOf() != 0) {
			$origobj = owRead($_obj->getVariantOf());
			$str .= '<option value="'.$origobj->getObjectId().'">'.languageName($origobj->getLanguage()).' ('.$origobj->getLanguage().')</option>';
			$arr = $origobj->getVariantsLang();
			foreach ($arr as $cur) {
				$s = '';
				if ($objectid == $cur['objectid']) $s = ' SELECTED';
				$str .= '<option value="'.$cur['objectid'].'"'.$s.'>'.languageName($cur['language']).' ('.$cur['language'].')</option>';
			}
		}
		if ($_obj->hasVariant()) {
			$str .= '<option value="'.$_obj->getObjectId().'">'.languageName($_obj->getLanguage()).' ('.$_obj->getLanguage().')</option>';
			$arr = $_obj->getVariantsLang();
			foreach ($arr as $cur) {
				$s = '';
				if ($objectid == $cur['objectid']) $s = ' SELECTED';
				$str .= '<option value="'.$cur['objectid'].'"'.$s.'>'.languageName($cur['language']).' ('.$cur['language'].')</option>';
			}
		}
		$str .= '</select>';
		}
		if ($this->CanView('list')) return $str;
	}
	
	function titleBar() {
		$result .= '<div class="metatitle">';
		$result .= '
		<div style="float: right"><a href="'.$this->returnMeUrl().'&view=viewprint" target="_blank"><img src="image/view/title_print.gif" border="0"></A></div>
		';
		$result .= $this->shadowtext($this->gl('title').' :: '.$this->gl('name').' :: '.$this->_obj->getName());
		$result .= '</div>';
		return $result;
	}
	
	function buttonBar() {
		$result .= '<div id="buttonbar" class="metabuttonbar" style="margin-bottom: 5px;">';
		if ($this->canView('list')) $result .= $this->edit_list($this->objectid[0]);
		if ($this->canView('tree')) $result .= $this->edit_tree($this->objectid[0]);
		if ($this->canView('create')) $result .= $this->edit_create($this->objectid[0]);
		if ($this->canView('createvariant')) $result .= $this->edit_createvariant($this->objectid[0]);
		if ($this->canView('delete')) $result .= $this->edit_delete($this->objectid[0]);
		if ($this->canView('active')) $result .= $this->edit_activate($this->objectid[0]);
		$result .= $this->edit_start($this->objectid[0]);
		$result .= $this->edit_previous($this->objectid[0]);
		$result .= $this->edit_next($this->objectid[0]);
		$result .= $this->edit_end($this->objectid[0]);
		if ($this->canView('createvariant')) $result .= $this->edit_variants($this->objectid[0]);
		$result .= '</div>';
		return $result;
	}

	function retViewInitVal() {
		return '';
	}
	
	function customFields() {
	}
	
	function beforeForm() {
		return '<div class="metabox">';
	}
	
	function startForm() {
	}
	
	function endForm() {
	}
	
	function afterForm() {
		return '</div>';
	}

	function viewStart() {
		return '<div class="metawindow">';
	}
	
	function viewEnd() {
		return '</div>';
	}
	
	function customButtons() {
		return '';
	}
	
	function submitButtons() {
		$result .= '<div style="padding-bottom: 14px;">';
		$result .= '<input id="submit1" name="submit1" type="submit" class="mformsubmit" value="'.$this->gl('button_save').'">';
		$result .= '<input id="submit2" name="submit2" type="button" onclick="this.form._ret.value = this.form.view.value; this.form.view.value=\'combi\'; this.form.submit();" class="mformsubmit" value="'.$this->gl('button_apply').'">';
		$result .= $this->customButtons();
		$result .= '</div>';
		return $result;
	}
	
	function categorySelection() {
		$field = new basic_field($this);
		$result .= '<div class="mformfieldset" id="fieldset__categories__" style=""><div class="mformlabel" style="">'.$this->gl('_label_category').'</div><div class="mformfield" style="">';
		$result .= $field->categorySelection($this->objectid[0],'__categories__');
		$result .= '</div></div>';
		return $result;
	}

	function webAccessSelection() {
		$field = new basic_field($this);
		$result .= '<div class="mformfieldset" id="fieldset__webaccess__" style=""><div class="mformlabel" style="">'.$this->gl('_label_webaccess').'</div><div class="mformfield" style="">';
		$result .= $field->webAccessSelection($this->objectid[0],'__webaccess__');
		$result .= '</div></div>';
		return $result;
	}

	function sysAccessSelection() {
		$field = new basic_field($this);
		$result .= '<div class="mformfieldset" id="fieldset__sysaccess__" style=""><div class="mformlabel" style="">'.$this->gl('_label_sysaccess').'</div><div class="mformfield" style="">';
		$result .= $field->sysAccessSelection($this->objectid[0],'__sysaccess__');
		$result .= '</div></div>';
		return $result;
	}

	function parseFields() {
		$fieldobj = new basic_field($this);
		if ($this->_editable) {
			$result .= $fieldobj->parseFieldsForm($this->_objcols,$this->_obj->elements[0],$this->_obj->isVariant(),$this->_obj->getVariantFields());
		} else {
			$result .= $fieldobj->parseFieldsView($this->_objcols,$this->_obj->elements[0],$this->_obj->isVariant(),$this->_obj->getVariantFields());
		}
		return $result;
	}

	function numNoneHiddenCols() {
		$res = 0;
		foreach ($this->_objcols as $cur) {
			if ($cur['inputtype'] != UI_HIDDEN) $res++;
		}
		return $res;
	}

	function readElement() {
		$this->errorhandler->disable();
		$this->_obj = owRead($this->objectid[0]);
		$this->errorhandler->enable();
	}
	
	function readCols() {
		#$this->_objcols = owDatatypeColsDesc($this->objectid[0]);
		$this->_objcols = owDatatypeColsDesc($this->otype);
	}

	function view() {
		$stack = $this->userhandler->getObjectIdStack();
		$system_url = $this->userhandler->getSystemUrl();
		if (!empty($stack) && empty($this->objectid)) $this->objectid = $stack;
		
		$system_url = $this->userhandler->getSystemUrl();
		$this->readElement();
		if (!$this->_obj->hasFieldType('html')) 
			$this->context->addheader('
			<script type="text/javascript" src="'.$system_url.'js/fValidate.config.js"></script>
			<script type="text/javascript" src="'.$system_url.'js/fValidate.core.js"></script>
			<script type="text/javascript" src="'.$system_url.'js/fValidate.lang-enUS.js"></script>
			<script type="text/javascript" src="'.$system_url.'js/fValidate.validators.js"></script>
			');
		$this->context->addHeader('<script type="text/javascript">'.$this->relatedFieldsHeader().'</script>');
		$this->_obj->initLayout();
		$result .= $this->viewStart();
		$result .= $this->titleBar();
		$result .= $this->buttonBar();
		$result .= $this->beforeForm();
		$result .= '<form name="metaform" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data" onsubmit="return validateForm(this);" style="spacing: 0px; margin: 0px; padding: 0px;">';
		$result .= '<input type="hidden" name="objectid" value="'.$this->objectid[0].'">';
		$result .= '<input type="hidden" name="cmd" value="update">';
		$result .= '<input type="hidden" name="_ret" value="'.$this->retViewInitVal().'">';
		if ($this->userhandler->getWebUser()) {
			if ($_REQUEST['pageid']) $result .= '<input type="hidden" name="pageid" value="'.$_REQUEST['pageid'].'">';
		}
		if ($this->parentid) $result .= '<input type="hidden" name="_parentid" value="'.$this->parentid.'">';
		if ($this->relcol) $result .= '<input type="hidden" name="_relcol" value="'.$this->relcol.'">';
		if ($this->relval) $result .= '<input type="hidden" name="_relval" value="'.$this->relval.'">';
		$result .= $this->returnviewpost($this->view);
		$this->readCols();
		if ($this->numNoneHiddenCols() > $this->submittop) 
			if ($this->_editable) $result .= $this->submitButtons();
		$result .= $this->startForm();
		$result .= $this->parseFields();
		$result .= $this->customFields();
		#$result .= $this->extraFields();
		$result .= '<br>';
		$result .= $this->endForm();
		if ($this->_editable) $result .= $this->submitButtons();
		if ($this->CanView('category') && $this->_editable && !$this->_obj->isVariant()) $result .= $this->categorySelection();
		if ($this->CanView('access') && $this->_editable && !$this->_obj->isVariant()) $result .= $this->webAccessSelection();
		if ($this->CanView('access') && $this->_editable && !$this->_obj->isVariant()) $result .= $this->sysAccessSelection();
		$result .= '</form>';
		$result .= $this->afterForm();
		$result .= '<br><br><br>';
		$result .= $this->viewEnd();;

		$result .= '<script type="text/javascript">';
		$fieldobj = new basic_field($this);
		if ($this->_obj->isVariant()) $result .= $fieldobj->GetStatusJs($this->_obj->prv_column);
		$result .= '</script>';
		
		return $result;
	}
}

?>