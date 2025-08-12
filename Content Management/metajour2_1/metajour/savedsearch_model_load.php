<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage model
 */

require_once('basic_model.php');

class savedsearch_model_load extends basic_model {

	function model() {
		$savedsearch = owRead($this->data['savedsearch']);
		$otype = $this->data['parentotype'];
		if ($savedsearch) {
			$_SESSION['gui'][$otype] = unserialize($savedsearch->elements[0]['content']);
		}
	}
}

?>