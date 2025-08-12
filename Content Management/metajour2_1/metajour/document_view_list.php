<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_list.php');

class document_view_list extends basic_view_list {

	function loadLanguage() {
		parent::loadLanguage();
		$this->loadLangFile('document_view_list');
	}

	function view() {
		$h = 	"<script type=\"text/javascript\">
		function revision_with_confirm(url) {
      		ewc=confirm('".$this->gl('text_1')."');
      		if (ewc) {
      			window.open(url,'','top=0,left=0,width=970,height=670,toolbar=0,location=0,status=1,scrollbars=1,resizable=1');
      			return true;
      		} else {
      			return false;
      		}
   		}
		</script>
		";
		$this->context->addHeader($h);
		parent::view();
	}
	
	function oeJavascript() {
		if ($this->userhandler->getRevisionControl()) {
			return "
			if (!revision_with_confirm('gui.php?cmd=createfuture&view=editor&objectid='+o_id)) {
				window.open('gui.php?view=editor&locked=1&objectid=' +  o_id,'','top=0,left=0,width=970,height=670,toolbar=0,location=0,status=1,scrollbars=1,resizable=1'); return false;
			}
			";
		} else {
			return "window.open('gui.php?view=editor&objectid=' +  o_id,'','top=0,left=0,width=970,height=670,toolbar=0,location=0,status=1,scrollbars=1,resizable=1'); return false;";
		}
	}

}
?>