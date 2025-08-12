<?php
/**
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package SSA
 * @subpackage view
 * $Id: edocument_edocform_view_combi.php,v 1.2 2005/03/08 09:18:29 SYSTEM Exp $
 */

require_once(dirname(__FILE__).'/../../basic_view_combi.php');

class edocument_edocform_view_combi extends basic_view_combi {

	function submitButtons() {
		if ($this->_obj->elements[0]['status'] == CASE_OPEN) {
			$result = '<div style="padding-bottom: 14px;">';
			$result .= '<input id="submit1" name="submit1" type="submit" class="mformsubmit" value="'.$this->gl('button_save').'">';
			$result .= '<input id="submit2" name="submit2" type="button" onclick="this.form._ret.value = this.form.view.value; this.form.view.value=\'combi\'; this.form.submit();" class="mformsubmit" value="Anvend">';
			$result .= '<input id="submit3" name="submit2" type="button" onclick="this.form.cmd.value = this.form.cmd.value + \',caseclose\'; this.form.submit();" class="mformsubmit" value="Godkend">';
			$result .= '</div>';
		} else {
			$result = '';
		}
		return $result;
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
		}
		return $this->button('list.png',$this->gl('img_list'),$this->callgui($this->otype,'','',$retlist,'','',$_obj->getParentId()));
	}

}

?>
