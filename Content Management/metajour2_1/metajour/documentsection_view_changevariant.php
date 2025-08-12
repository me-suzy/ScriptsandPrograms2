<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('document_view_editor.php');

class documentsection_view_changevariant extends document_view_editor {

	function view() {
		$this->objectid[0] = $this->data['nextparam'];
		parent::view();
	}

}