<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 * $Id: structureelement_view_init.php,v 1.2 2004/10/17 22:04:19 jan Exp $
 */

require_once('basic_view_split.php');

class product_view_init extends basic_view_split {
	
	function view() {
		return $this->splitView('product','productgroup');
	}
	
}

?>
