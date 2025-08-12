<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage field
 */

require_once('string.field.php');

class urlfield extends stringfield {

	function listOut() {
		return '<a href="http://'.$this->getValueOutput().'" target="_blank">'.$this->getValueOutput().'</a>';
	}
	
}

?>