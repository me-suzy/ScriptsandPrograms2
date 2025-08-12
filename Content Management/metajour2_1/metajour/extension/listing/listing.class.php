<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage extension
 */

require_once($system_path . "extension/basicextension.class.php");

class ext_listing extends basicextension {

	function ext_listing() {
		$this->basicextension();
		$this->extname = 'listing';
		$this->addextparam('templatename');
		$this->addextparam('classname');
		$this->addextparam('limitcol');
		$this->addextparam('limitto');
	}
	
	function getContentTree() {
		if (!empty($this->extconfig['limitcol'])) {
			$fields = array();
			$obj = owNew($this->extconfig['classname']);
			$obj->listobjects();
			
			for ($i = 0; $i < $obj->elementscount; $i++) {
				$obj2 = owReadTextual($obj->elements[$i]['objectid'], $this->extconfig['limitcol']);
				$name = $obj2[0]['fieldrep'];
				if (!isset($fields[$name])) $fields[$name] = $name;
			}
			asort($fields);
			$returnarray = array();
			foreach ($fields as $name) {
				$url = $this->MeUrl . "&_ext=" . $this->extname . "&_extcf=" . $this->extconfigset . "&limitto=" . urlencode($name);
				$returnarray[] = array('name'=>$name, 'url'=>$url);
			}
			return $returnarray;
		}
		
	}

	function _do() {
		$this->useTemplate('templatename','templateid','standard_listing_list');
	}

}
?>