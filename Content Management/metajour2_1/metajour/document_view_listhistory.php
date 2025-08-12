<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_listhistory.php');

class document_view_listhistory extends basic_view_listhistory {

	function loadLanguage() {
		parent::loadLanguage();
		$this->loadLangFile('document_view_list');
	}

	function oeJavascript() {
		return	"window.open('gui.php?view=editor&locked=1&objectid=' +  o_id,'','top=0,left=0,width=970,height=670,toolbar=0,location=0,status=1,scrollbars=1,resizable=1'); return false;";
	}

}
?>