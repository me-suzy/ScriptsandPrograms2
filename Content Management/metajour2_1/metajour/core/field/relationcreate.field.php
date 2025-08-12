<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage field
 */

require_once('relation.field.php');

class relationcreatefield extends relationfield {

	function convertToDatabase($value) {
		if (!is_numeric($value) && !empty($value)) {
			$obj = owNew($this->_fieldrelation);
			$obj->createObject(array('name' => $value));
			$value = $obj->getObjectId();
			// creation of documentsections are not yet a part of the
			// document create-method. So we manually create 1 section
			if ($this->_fieldrelation == 'document') {
				$sectionobj = owNew('documentsection');
				$sectionobj->createObject(array("name" => ""),$value);
			}
		}
		return $value;
	}	

	function _listAllObjects($type, $value, $emptynone = false) {
		$s = '<OPTION value="">INDTAST NY POST ELLER VÆLG</OPTION>';
		$s .= parent::_listAllObjects($type, $value, $emptynone);
		return $s;
	}

	function events() {
		return 'onKeyDown="fnKeyDownHandler(this, event);" onKeyUp="fnKeyUpHandler_A(this, event); return false;" onKeyPress = "return fnKeyPressHandler_A(this, event);"  onChange="fnChangeHandler_A(this, event);"';
	}
		
	function formOut() {
		$this->_disablenone = true;
		static $reljsadded = false;
		if (!$reljsadded) {
			$reljsadded = true;
  		$this->view->context->addHeader('<script type="text/javascript">var vEditableOptionText_A = "INDTAST NY POST ELLER VÆLG";</script>');
			$this->view->context->addHeader("<script type=\"text/javascript\" src=\"".$this->userhandler->getSystemUrl()."js/editablecombo.js\"></script>\n");
		}
		return parent::formOut();
	}
	
}

?>