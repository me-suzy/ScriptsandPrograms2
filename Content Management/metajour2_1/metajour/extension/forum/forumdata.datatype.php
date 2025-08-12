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

class forumdata extends basic {

function forumdata() {
	$this->basic();
	$this->setobjecttable('ext_forumdata');
	$this->setsupertype('forum');
	$this->addcolumn('name',0,UI_STRING);
	$this->addcolumn('content',0,UI_TEXT_WRAP);
	$this->addcolumn('uname',0,UI_STRING);
	$this->addcolumn('numread',0,UI_STRING);
	$this->addcolumn('numreply',0,UI_STRING);
	$this->addcolumn('lastreply',0,UI_STRING);
}

}