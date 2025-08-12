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

class splitselectfield extends field {
	var $_showthumb = false;
	
	function setShowThumb($bool) {
		$this->_showthumb = $bool;
	}
	
	function formOut() {
		## HIDDEN FIELD HOLDING THE VALUE
		$s = '<input type="hidden" name="'.$this->_fieldname.'" value="'.$this->_fieldvalue.'">';

		## THUMBNAIL
		if ($this->_showthumb) {
			if ($this->_fieldvalue) {
				$s .= '<img name="_img'.$this->_fieldname.'" src="'.$this->userhandler->getSystemUrl().'getfilethumb.php?auto=100&objectid='.$this->_fieldvalue.'">';
			} else {
				$s .= '<img name="_img'.$this->_fieldname.'" src="'.$this->userhandler->getSystemUrl().'image/nothing.gif" style="display: none">';
			}
		}

		## OPEN BUTTON
		$s .= '<img src="'.$this->userhandler->getSystemUrl().'image/Open.GIF" class="mButton" onmouseover="this.className=\'mButtonOver\'" onmouseout="this.className=\'mButton\'" style="float: left;"
		onclick = "
		var src = '.$this->view->ListDialog($this->_fieldrelation,'','','splitdialog').'
		if (src) {
			document.forms[0].'.$this->_fieldname.'.value = src.id;
			var element = document.getElementById(\'listdialog_'.$this->_fieldname.'\');
			if (element) element.innerText = src.name;';
		if ($this->_showthumb) {
			$s .= '
			var element = document.getElementById(\'_img'.$this->_fieldname.'\');
			if (element) element.style.display = \'block\';
			if (element) element.src = \''.$this->userhandler->getSystemUrl().'getfilethumb.php?auto=100&objectid=\' + src.id;';
		}			
		$s .= '}">';
		################

		## DELETE BUTTON
		$s .= '<img src="'.$this->userhandler->getSystemUrl().'image/delete.gif" class="mButton" onmouseover="this.className=\'mButtonOver\'" onmouseout="this.className=\'mButton\'" style="float: left; margin-right: 5px;"
		onclick = "
			document.forms[0].'.$this->_fieldname.'.value = 0;
			var element = document.getElementById(\'listdialog_'.$this->_fieldname.'\');
			if (element) element.innerText = \'\';
		';
		if ($this->_showthumb) {
			$s .= '
			var element = document.getElementById(\'_img'.$this->_fieldname.'\');
			if (element) element.style.display = \'none\';
			if (element) element.src = \''.$this->userhandler->getSystemUrl().'image/nothing.gif\';
			';
		}		
		$s .= '">';
		################

		## TEXT DIV
		$s .= '<div name="listdialog_'.$this->_fieldname.'" id="listdialog_'.$this->_fieldname.'" style="margin-top: 3px;'.$this->_fieldstyle.'">'.owReadName($this->getValueOutput()).'</div>';
		return $s;
	}
	
	function listOut() {
		return substr(owReadName($this->getValueOutput()),0,70);
	}

	function viewOut() {
		if ($this->_showthumb && $this->_fieldvalue) {
			return '<img name="_img'.$this->_fieldname.'" src="'.$this->userhandler->getSystemUrl().'getfilethumb.php?auto=100&objectid='.$this->_fieldvalue.'">';
		} else {
			return owReadName($this->getValueOutput());
		}
	}
	
}
?>