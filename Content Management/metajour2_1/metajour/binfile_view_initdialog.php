<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_split.php');

class binfile_view_initdialog extends basic_view_split {
		
	function view() {
		$this->context->clearall();
		
		$result = '
		<frameset cols="352, *" border="0" name="menudum" framespacing="0">
			<frame name="tree" src="'.$_SERVER['PHP_SELF'].'?otype=folder&view=hierarchydialogsplit" noresize="yes">
			<frame name="dialog" src="'.$_SERVER['PHP_SELF'].'?otype='.$this->otype.'&view=listdialog" noresize="yes" border=0 noborder>
		</frameset>
		';
		return $result;
	}

}
?>