<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_edit.php');
require_once('js/tabpaneclass.php');

class basic_view_combi extends basic_view_edit {
	var $_obj;
	var $_tab;

	function loadLanguage() {
		basic_view_edit::loadLanguage();
		$this->loadLangFile('basic_view_combi');
	}
	
	function titleBar() {
		/* clear the title inherited from edit view */
	}
	
	function buttonBar() {
		/* clear the button bar inherited from edit view */
	}

	function viewStart() {
		/* clear enherited DIV */
	}
	
	function viewEnd() {
		/* clear enherited DIV */
	}
	
	function beforeForm() {
		/* clear enherited DIV */
	}

	function afterForm() {
		/* clear enherited DIV */
	}
	
	function combiTitleName() {
		return $this->_obj->getName();
	}
	
	function combiTitleFull() {
		return $this->gl('title').' :: '.$this->gl('name').' :: '.$this->combiTitleName();
	}
	
	function combiTitle() {
		$result .= '<div class="metatitle">';
		$result .= '
		<div style="float: right"><a href="'.$this->returnMeUrl().'&view=viewprint" target="_blank"><img src="image/view/title_print.gif" border="0"></A></div>
		';
		$result .= $this->shadowtext($this->combiTitleFull());
		$result .= '</div>';
		return $result;
	}

	function combiButtonBar() {
		return parent::buttonBar();
	}

	function tabSubType() {
		if ($this->_obj->getSubType()) $this->_tab->addTab($this->gl('tab_childobjects'),'<iframe width=100% height=50% src="gui.php?view=list&otype='.$this->_obj->getSubtype().'&_parentid='.$this->objectid[0].'"></iframe>');
	}

	function tabProperties() {
		$controller = getcontrol($this->otype,$this->objectid,$this->context);
		$this->_tab->addTab($this->gl('tab_properties'),$controller->view(array('properties')));
	}

	function view() {
		$stack = $this->userhandler->getObjectIdStack();
		if (!empty($stack) && empty($this->objectid)) $this->objectid = $stack;
		$this->readElement();
		if ($this->_obj) {
			$this->_obj->initLayout();
			$childs = $this->_obj->getChildDatatypes();
			$this->_tab = new tabpaneclass;
			$this->context->addHeader($this->_tab->getHeader());
			echo '<div class="metawindow">';
			echo $this->combiTitle();
			echo $this->combiButtonBar();
			
			$this->_tab->addTab($this->gl('tab_general'),basic_view_edit::view());
			$relations = $this->_obj->getRelationDatatypes();
			foreach ($relations as $cur) {
				$relobj = owNew($cur['datatype']);
				$relobj->setfilter_data($cur['foreigncolumn'],$this->_obj->elements[0][$cur['column']]);
				$relobj->listObjects();
				$num = '';
				if ($relobj->elementscount > 0) $num = ' ('.$relobj->elementscount.')';
				$this->_tab->AddTab(owDatatypeDesc($cur['datatype']).$num,'<iframe width=98% height=50% src="gui.php?view=list&otype='.$cur['datatype'].'&_relcol='.$cur['foreigncolumn'].'&_relval='.$this->_obj->elements[0][$cur['column']].'"></iframe>');
			}		
	
			foreach ($childs as $cur) {
				$childobj = owNew($cur);
				$childobj->listObjects($this->objectid[0]);
				$num = '';
				if ($childobj->elementscount > 0) $num = ' ('.$childobj->elementscount.')';
				$this->_tab->addTab(owDatatypeDesc($cur).$num,'<iframe width=98% height=50% src="gui.php?view=list&otype='.$cur.'&_parentid='.$this->objectid[0].'"></iframe>');		
			}
			$this->tabSubType();
			if ($this->_obj->hasOldRevision()) $this->_tab->addTab($this->gl('tab_oldrevision'),'<iframe width=98% height=50% src="gui.php?view=listhistory&otype='.$this->otype.'&_relval='.$this->objectid[0].'"></iframe>');
			if ($this->_obj->hasFutureRevision()) $this->_tab->addTab($this->gl('tab_futurerevision'),'<iframe width=98% height=50% src="gui.php?view=listfuture&otype='.$this->otype.'&_relval='.$this->objectid[0].'"></iframe>');
			$this->tabProperties();
			echo $this->_tab->getScript();
			echo '</div>';
			echo '<script type="text/javascript">';
			if (!$this->_obj->isVariant()) echo 'if( (document.getElementById(\'tabPage1\').style.display = \'block\') && document.getElementById(\'name\') ) {
				var field = document.getElementById(\''.$this->_obj->prv_column[0]['name'].'\');
				try { field.focus(); } catch (e) {}
			}';
			echo '</script>';
		}
	}	
}

?>