<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_view.php');

class basic_view_viewprint extends basic_view_view {

	function buttonBar() {
		$this->context->addHeader('
	<style type="text/css">
	<!--
.metabox, .metawindow {      
	background:		none;
	border: none;
}
		-->
		</style>
		');


		#return '<img src="/img/Schur.jpg" align="right">';
	}

}

?>