<?php
/**
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package eProject
 * @subpackage view
 * $Id: edocument_document_view_list.php,v 1.1 2005/02/15 12:19:25 jan Exp $
 */

require_once($system_path.'document_view_list.php');

class edocument_document_view_list extends document_view_list {

	function edocument_document_view_list() {
		$this->document_view_list();
		$this->_preset = true;
	}
	
}

?>