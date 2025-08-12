<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage extension
 */

require_once($system_path.'core/basicclass.php');

class search extends basic {

function search() {
	$this->basic();
	$this->setobjecttable('ext_search');
	$this->addcolumn('name',0,UI_STRING);
	$this->addcolumn('pageid_result',0,UI_RELATION_NODEFAULT,'document');
	$this->addcolumn('templateid_search',0,UI_RELATION_NODEFAULT,'template');
	$this->addcolumn('templateid_result',0,UI_RELATION_NODEFAULT,'template');
}

}
