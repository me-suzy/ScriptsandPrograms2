<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */

require_once('basic_view_create.php');

class binfile_view_create extends basic_view_create {

	function customfields() {
		return $this->makeField($this->gl('label_upload'),'<input type="file" name="__uploadfile__" style="width: 400px;">');
	}
		
}
?>