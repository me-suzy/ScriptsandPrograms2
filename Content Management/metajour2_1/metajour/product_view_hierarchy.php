<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 * $Id: structureelement_view_hierarchy.php,v 1.2 2004/10/11 00:25:14 jan Exp $
 */

require_once('basic_view_hierarchy.php');

class product_view_hierarchy extends basic_view_hierarchy {

	function product_view_hierarchy() {
		$this->basic_view_hierarchy();
		$this->onclicktype = 'product';
	}
	
}

?>
