<?php
/**
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eReklamation
 * @subpackage view
 * $Id: erek_comp_view_combi.php,v 1.4 2005/02/15 12:21:44 jan Exp $
 */

global $system_path;
require_once($system_path.'basic_view_combi.php');

class erek_comp_view_combi extends basic_view_combi {

	function tabSubType() {
	}

	function tabProperties() {
	}
	
	function beforeForm() {
		$result = parent::beforeForm();
		$result .= '<div style="position: absolute; left: 540px;" id="infobox">';
		$result .= '<fieldset style="padding: 5px;"><legend><b>Sagsoverblik</b></legend>';
		$this->_editable = false;
		$this->readElement();
		$this->readCols();
		$result .= $this->makeField('Oprettet',$this->_obj->elements[0]['created']);
		$result .= $this->makeField('Kundenr',$this->_obj->elements[0]['cno']);
		$result .= parent::parseFields();
		$this->_editable = true;
		$result .= '</fieldset>';
		$result .= '</div>';
		return $result;
	}
	
	function afterForm() {
		$result = '<script type="text/javascript">
			var infobox = document.getElementById(\'infobox\');
			var h = infobox.clientHeight;
			var mytabpage = document.getElementById(\'tabPage1\');
			if (mytabpage.clientHeight < h) mytabpage.style.height = h;
		</script>';
	}

	function parseFields() {
		if ($this->_obj->elements[0]['status'] == CASE_OPEN || $this->_obj->elements[0]['status'] == CASE_AWAIT) {
			unset($this->_objcols['comment1']);
		}
		return parent::parseFields();
	}

	function submitButtons() {
		if ($this->_obj->elements[0]['status'] == CASE_OPEN) {
			$result .= '<div style="padding-bottom: 14px;">';
			$result .= '<input id="submit1" name="submit1" type="submit" class="mformsubmit" value="'.$this->gl('button_save').'">';
			$result .= '<input id="submit2" name="submit2" type="button" onclick="this.form.cmd.value = this.form.cmd.value + \',casedone\'; this.form.submit();" class="mformsubmit" value="Til godkendelse">';
			$result .= '<input id="submit4" name="submit4" type="button" onclick="this.form.cmd.value = this.form.cmd.value + \',caseawait\'; this.form.submit();" class="mformsubmit" value="Parkér">';
			$result .= '</div>';
			return $result;
		} else if ($this->_obj->elements[0]['status'] == CASE_AWAIT) {
			$result .= '<div style="padding-bottom: 14px;">';
			$result .= '<input id="submit1" name="submit1" type="button" onclick="this.form.cmd.value = this.form.cmd.value + \',caseopen\'; this.form.submit();" class="mformsubmit" value="Genaktivér">';
			$result .= '<input id="submit2" name="submit2" type="button" onclick="this.form.cmd.value = this.form.cmd.value + \',casedone\'; this.form.submit();" class="mformsubmit" value="Til godkendelse">';
			$result .= '</div>';
			return $result;
		} else if ($this->_obj->elements[0]['status'] == CASE_DONE) {
			$result .= '<div style="padding-bottom: 14px;">';
			$result .= '<input id="submit1" name="submit1" type="button" onclick="this.form.cmd.value = this.form.cmd.value + \',caseopen\'; this.form.submit();" class="mformsubmit" value="Genaktivér">';
			$result .= '<input id="submit2" name="submit2" type="button" onclick="this.form.cmd.value = this.form.cmd.value + \',caseclose\'; this.form.submit();" class="mformsubmit" value="Afslut">';
			$result .= '</div>';
			return $result;
		} else return '';
	}


	function edit_tree($objectid) {
		return '';
	}

	function edit_list($objectid) {
		$_obj = owRead($objectid);
		switch ($this->_obj->elements[0]['status']) {
			case CASE_OPEN:
					$retlist = 'listactive';
					break;
			case CASE_CLOSED:
					$retlist = 'listclosed';
					break;
			case CASE_DONE:
					$retlist = 'listdone';
					break;
			case CASE_AWAIT:
					$retlist = 'listawait';
					break;
		}
		return $this->button('list.png',$this->gl('img_list'),$this->callgui($this->otype,'','',$retlist,'','',$_obj->getParentId()));
	}
	
}

?>