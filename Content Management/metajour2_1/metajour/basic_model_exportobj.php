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
require_once('core/util/class.zip.php');

class basic_model_exportobj extends basic_model {

	function model() {
		$zip = new zipfile;
		foreach($this->objectid as $curid) {
			owExportObj($curid,$zip);
		}
		
		$this->context->clearall(); #TODO
		header("Pragma: no-cache");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');
		header('Content-Transfer-Encoding: none');
		header('Content-Type: application/zip; name="exportobj.zip"'); // This should work for IE & Opera
		header('Content-Disposition: inline; filename="exportobj.zip"');
		echo $zip->file();
		die();
	}

}

?>