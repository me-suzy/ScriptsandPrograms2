<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage extension
 */

require_once($system_path."core/basicclass.php");

class forum extends basic {

function forum() {
	$this->basic();
	$this->setobjecttable('ext_forum');
	$this->setsubtype('forumdata');
	$this->addcolumn('name',0,UI_STRING);
}

}
