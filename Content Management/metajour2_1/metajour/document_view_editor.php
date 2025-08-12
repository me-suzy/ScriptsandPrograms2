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

class document_view_editor extends basic_view {

	/*
	 * Find the master of a given object
	 * If $objectid is a variant returns
	 * the objectid this object is a variant of
	 * othervise returns this objectid
	 */
	function getmasterid($objectid) {
		$obj = owRead($objectid);
		$variantof = $obj->getvariantof();
		if ($variantof > 0)
			return $variantof;
		else
			return $objectid;
	}
	
	function loadLanguage() {
		parent::loadLanguage();
		$this->loadLangFile('document_view_editor');
	}
	
	function view() {
		if ($this->userhandler->getOldEditor()) {
			require_once('document_view_editor_triedit.php');
		} else {
			require_once('document_view_editor_tinymce.php');
		}
	}

}
?>