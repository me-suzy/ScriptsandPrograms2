<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage model
 */

require_once('basic_model_update.php');

class documentsection_model_update extends basic_model_update {

	function model() {
		$this->data['content'] = str_replace('&gt;', '>', $this->data['content']);
		$this->data['content'] = str_replace('&lt;', '<', $this->data['content']);
		$this->data['content'] = str_replace('&amp;', '&', $this->data['content']);
		parent::model();
	}
		
}

?>