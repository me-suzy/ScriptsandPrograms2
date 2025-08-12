<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage model
 */

require_once('basic_model_approvepublish.php');

class documentsection_model_approvepublish extends basic_model_approvepublish {

	function model() {
		$obj = owRead($this->objectid[0]);
		$futuredocument = owRead($obj->getParentId());
		$currentrevision = $futuredocument->getCurrentRevision();
		$this->approvepublish($obj->getParentId());
		$eventhandler =& GetEventHandler();
		$eventhandler->event('approvepublish', 'document', array($currentrevision));
	}

}

?>