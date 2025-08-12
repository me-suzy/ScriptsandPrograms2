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

class datefield extends field {
	
	function formOut() {
		$system_url = $this->userhandler->getSystemUrl();
		static $calendarjsadded = false;
		if (!$calendarjsadded) {
			$calendarjsadded = true;
			$this->view->context->addHeader("<style type=\"text/css\">@import url(".$system_url."js/calendar/calendar-system.css);</style>");
			$this->view->context->addHeader("<script type=\"text/javascript\" src=\"".$system_url."js/calendar/calendar.js\"></script>\n");
			$this->view->context->addHeader("<script type=\"text/javascript\" src=\"".$system_url."js/calendar/lang/calendar-da.js\"></script>\n");
			$this->view->context->addHeader("<script type=\"text/javascript\" src=\"".$system_url."js/calendar/calendar-setup.js\"></script>\n");
		}
		$res = '<input type="text" validate="'.$this->_fieldvalidate.'" name="'.$this->_fieldname.'" id="'.$this->_fieldname.'" value="'.$this->_fieldvalue.'" style="width: 65px; '.$this->_fieldstyle.'" readonly calendar>&nbsp;';
		$res .= '<img src="'.$system_url.'image/cal/cal.gif" class="mButton" onmouseover="this.className=\'mButtonOver\'" onmouseout="this.className=\'mButton\'" style="vertical-align: top;" id="button_' . $this->_fieldname . '">';
		if (!$this->disabledOnValue() || ($this->disabledOnValue() && ($this->_fieldvalue == '' || $this->_fieldvalue == '0000-00-00'))) {
			$this->view->context->addFooter("<script type=\"text/javascript\">
				Calendar.setup(
				{
					inputField : \"" . $this->_fieldname . "\",
					ifFormat : \"%Y-%m-%d\",
					button : \"button_" . $this->_fieldname . "\"
				}
				);
				</script>");
		}
		if ($this->disabledOnValue() && $this->_fieldvalue != '' && $this->_fieldvalue != '0000-00-00') {
			$res .= '<script type="text/javascript">';
			$res .= 'document.getElementById(\''.$this->_fieldname.'\').disabled = true;';
			$res .= '</script>';
		}
		return $res;
	}
	
}

?>