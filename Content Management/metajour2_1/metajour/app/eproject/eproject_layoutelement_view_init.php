<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eProject
 * @subpackage view
 * $Id: eproject_layoutelement_view_init.php,v 1.2 2005/01/12 03:23:48 jan Exp $
 */

require_once($system_path.'basic_view_split.php');

class eproject_layoutelement_view_init extends basic_view_split {
	
	function view() {
		return $this->splitView('layoutelement','layout');
	}
	
}

?>