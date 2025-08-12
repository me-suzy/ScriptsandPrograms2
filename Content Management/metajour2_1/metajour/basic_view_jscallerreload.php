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

class basic_view_jscallerreload extends basic_view {

	function view() {
		return '
		<SCRIPT LANGUAGE="JavaScript">
		if (typeof(top.window.dialogArguments) == "undefined") {
			top.window.opener.location.reload();
		} else {
			top.window.dialogArguments.location.reload();
		}
		</script>';
	}
	
}

?>