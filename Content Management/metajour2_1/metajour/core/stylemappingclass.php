<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage core
 */

require_once('basicclass.php');

class stylemapping extends basic {

	function stylemapping() {
		$this->basic();
		$this->addcolumn('name',F_LITERAL, 'string');
		$this->addcolumn('mapping',F_LITERAL, 'string');
		$this->addcolumn('orderby', F_LITERAL, 'string');
	}

	function tableupdate() {
		if (!tableExists($this->objecttable)) {
			$db =& getdbconn();
			$db->execute("CREATE TABLE " . $this->objecttable . " (
				objectid INT NOT NULL,
				name VARCHAR(255) NOT NULL DEFAULT '',
				mapping VARCHAR(255) NOT NULL DEFAULT '',
				orderby INT NOT NULL DEFAULT 0,
				PRIMARY KEY (objectid)
			)");
		}
	}
}

?>
