<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * $Id: tabpaneclass.php,v 1.1 2004/09/25 22:47:25 jan Exp $
 */

class tabpaneclass {

	var $_tabcount = 0;
	var $_script = '';
	
	function addtab($tabname, $tabhtml) {
		$this->_tabcount++;
		$this->_script .= '<div class="tab-page" id="tabPage'.$this->_tabcount.'">
			<h2 class="tab">'.$tabname.'</h2>
			<script type="text/javascript">tp1.addTabPage( document.getElementById( "tabPage'.$this->_tabcount.'" ) );</script>
			'.$tabhtml.'
		</div>';
	}
	
	function getheader() {
		return '<link type="text/css" rel="stylesheet" href="css/tab.winclassic.css" />
		<script type="text/javascript" src="js/tabpane.js"></script>';
	}
	
	function getscript() {
		return '<div class="tab-pane" id="tabPane1">
		<script type="text/javascript">
		tp1 = new WebFXTabPane( document.getElementById( "tabPane1" ) );
		</script>'.$this->_script.'
		</div>
		<script type="text/javascript">
		setupAllTabs();
		</script>';
	}
	
}

?>