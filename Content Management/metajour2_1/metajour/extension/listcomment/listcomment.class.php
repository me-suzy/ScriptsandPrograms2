<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage extension
 */

require_once($system_path . "extension/basicextension.class.php");

class ext_listcomment extends basicextension {

	function ext_listcoment() {
		$this->basicextension();
		$this->extname = 'listcomment';
		$this->addextparam('templatename');
		$this->addextparam('objectid');
	}

	function _do() {
		if ($this->extcmd == 'add') {
			$obj = owNew('comment');
			$obj->createObject(array('name' => $_REQUEST['_ext_name'],'subject' => $_REQUEST['_ext_subject'],'content' => $_REQUEST['_ext_content']),$this->extconfig['objectid']);
		}
		$this->useTemplate('templatename','templateid','standard_listcomment_list');
		$obj = owNew('comment');
		$obj->listobjects($this->extconfig['objectid']);
		$this->extresult = $obj->elements;
	}

}
?>