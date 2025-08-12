<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage field
 */

class field {
	var $_fieldvalue = '';
	var $_fieldname = '';
	var $_fieldstyle = '';
	var $_fieldvalidate = '';
	var $_fieldrelation = '';
	var $_fieldvisible = true;
	var $_readonly = false;
	var $_fielddisabledonvalue = false;
	var $_fieldcomboarray = array();
	var $_contextmethod = array();
	var $_viewmethod = array();
	var $_context = NULL;
	var $emptyisnull = false;
	var $view = NULL;
	var $userhandler = NULL;
	var $_fieldonfocus = '';
	var $_fieldonchange = '';
	
	function field() {
		$this->userhandler =& getUserhandler();
		$this->_contextmethod[IN_FORM] = 'formOut';
		$this->_contextmethod[IN_VIEW] = 'viewOut';
		$this->_contextmethod[IN_LIST] = 'listOut';
	}
	
	function setVisibility($bool) {
		$this->_fieldvisible = $bool;
	}
	
	function visible() {
		return $this->_fieldvisible;
	}

	function setDisabledOnValue($bool) {
		$this->_fielddisabledonvalue = $bool;
	}
	
	function disabledOnValue() {
		return $this->_fielddisabledonvalue;
	}
	
	function setReadonly($bool) {
		$this->_readonly = $bool;
	}
	
	function readonly() {
		return $this->_readonly;
	}
	
	function setEmptyIsNull($value) {
		$this->emptyisnull = $value;
	}

	function setComboArray($value) {
		$this->_fieldcomboarray = $value;
	}
	
	function setName($value) {
		// assign the name of the field
		$this->_fieldname = $value;
	}
	
	function setValue($value) {
		// assign the current value of the field
		$this->_fieldvalue = $value;
	}
	
	function setValidate($value) {
		// assign validation rules (fValidate)
		$this->_fieldvalidate = $value;
	}

	function setRelation($value) {
		$this->_fieldrelation = $value;
	}

	function setOnChange($value) {
		$this->_fieldonchange = $value;
	}

	function setOnFocus($value) {
		$this->_fieldonfocus = $value;
	}
	
	function addStyle($value) {
		// assign styles to the field to override/extend the 
		// default styles
		$this->_fieldstyle .= $value;
	}
	
	function setView(&$view) {
		// assign pointer to current view
		$this->view = &$view;
	}
	
	function setViewMethod($view,$methodname) {
		// assign methods for output in certain views
		$this->_viewmethod[$view] = $methodname;
	}
	
	function setContextMethod($context,$methodname) {
		// override default logic IN_FORM, IN_LIST, IN_VIEW
		$this->_contextmethod[$context] = $methodname;
	}
	
	function getValueOutput() {
		// return the 'human-readable' equivalent of _fieldvalue
		// used when converting decimalnumbers, dates etc from
		// the database format to the current locale
		return $this->_fieldvalue;
	}
	
	function convertToDatabase($value) {
		// convert the 'human-readable' value to the database equivalent
		// primarily used when converting decimalnumbers, dates etc
		// will be accessed by createobject and updateobject
		return $value;
	}
	
	function noneOut() {
		return '';
	}
	
	function formOut() {
		return $this->getValueOutput();
	}
	
	function listOut() {
		return $this->getValueOutput();
	}
	
	function viewOut() {
		return '<div name="'.$this->_fieldname.'" id="'.$this->_fieldname.'" style="padding-top: 3px;'.$this->_fieldstyle.'">'.nl2br($this->getValueOutput()).'</div>';
	}
	
	function output($context) {
		$this->_context = $context;
		if (!empty($this->_viewmethod) && isset($this->_viewmethod[$this->view->view])) {
			$method = $this->_viewmethod[$this->view->view];
		} else {
			$method = $this->_contextmethod[$context];
		}
		return $this->$method();
	}
	
}

?>