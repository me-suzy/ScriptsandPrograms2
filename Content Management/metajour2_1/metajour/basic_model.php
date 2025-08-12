<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage model
 */
require_once('basic_error.php');
require_once('basic_user.php');

class basic_model {
	var $context;
	var $otype;
	var $errorhandler;
	var $userhandler;
	var $data;
	var $objectid;
	var $parentid;
	
	function basic_model() {
		$this->errorhandler =& GetErrorHandler();
		$this->userhandler =& GetUserHandler();
	}

}

?>