<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage model
 */

require_once('basic_model_create.php');

class savedsearch_model_create extends basic_model_create {
	
	function model() {
		$this->data['class'] = $this->data['parentotype'];
		$this->data['content'] = serialize($_SESSION['gui'][$this->data['class']]);
		parent::model();
	}	
}

?>