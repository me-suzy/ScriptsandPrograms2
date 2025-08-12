<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view.php');

class basic_view_splitdialog extends basic_view {
	
	function splitView($lefttype, $righttype) {
		$this->context->clearall();
		$result = '
		<frameset cols="302, *" border="0" name="menudum" framespacing="0">
			<frame name="tree"   src="'.$_SERVER['PHP_SELF'].'?otype='.$lefttype.'&view=hierarchydialogsplit" noresize="yes">
			<frame name="dialog" src="'.$_SERVER['PHP_SELF'].'?otype='.$righttype.'&view=listdialog" noresize="yes" border=0 noborder>
		</frameset>
		';
		return $result;
	}
	
	function view() {
		return $this->splitView($this->otype, $this->otype);
	}
	
}

?>