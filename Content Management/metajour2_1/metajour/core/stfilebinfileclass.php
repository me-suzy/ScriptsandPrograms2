<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage core
 */

require_once('staticbinfileclass.php');

class stfilebinfile extends staticbinfile {
	var $ERR_CANNOTMAKEDIR = 8;

	function stfilebinfile() {
		$this->staticbinfile();
	}

	function getRoot() {
		return $this->userhandler->getDirStfilebinfile();
	}

	function createProbeFile($name) {
		$uploaddir = $this->getpath($this->getParentId());
		$file = $uploaddir .$name;
		$h = fopen($file,'w');
		echo $file;
		if (!fwrite($h,'dummy')) die('error');
		fclose($h);
	}

}

?>