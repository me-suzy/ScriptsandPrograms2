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

class basic_model_move extends basic_model {

	function model() {
		foreach($this->objectid as $curid) {
			$obj = owRead($curid);
			$p = explode(',',$_REQUEST['moveobjectparams']);
			$newparentid = $p[0];
			$newchildorder = $p[1];
			$oldparentid = $p[2];
			$oldchildorder = $p[3];
			if ($newparentid != $curid) {
				if ($oldparentid <> $newparentid) $obj->setparentid($newparentid);
				$obj->movebefore($newchildorder);
			}
		}
	}
	
}

?>