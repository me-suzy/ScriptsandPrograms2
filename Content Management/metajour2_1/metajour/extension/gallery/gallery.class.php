<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage extension
 */

require_once($system_path.'extension/basicextension.class.php');
require_once('gallery.datatype.php');

class ext_gallery extends basicextension {

	function ext_gallery() {
		$this->basicextension();
		$this->extname = 'gallery';
		$this->addextparam('templatename_index');
		$this->addextparam('templatename_medium');
		$this->addextparam('templatename_full');
	}

	function _do() {
		if ($this->extconfig['thumbsize'] == 0) $this->extconfig['thumbsize'] = 150;
		if ($this->extconfig['mediumsize'] == 0) $this->extconfig['mediumsize'] = 600;
		if ($this->extconfig['xnum'] == 0) $this->extconfig['xnum'] = 3;
		if ($this->extconfig['ynum'] == 0) $this->extconfig['ynum'] = 3;
		switch ($this->extcmd) {

		case "medium":
		case "full":
			if ($this->extcmd == 'medium')
				$this->useTemplate('templatename_medium','templateid_medium','standard_gallery_medium');
			if ($this->extcmd == 'full')
				$this->useTemplate('templatename_full','templateid_full','standard_gallery_full');
			
			$binfiletype = 'binfile';
			$tobj = owRead($this->extconfig['folderid']);
			if ($tobj->getType() == 'stimgfolder') {
				$binfiletype = 'stimgbinfile';
			}
			
			$obj = owNew($binfiletype);
			
			$obj->listobjects($this->extconfig['folderid']);
			if (is_array($obj->elements) && is_numeric($_REQUEST['_ext_objectid'])) {
				
				$idx = false;
				foreach ($obj->elements as $key => $elem) {
					if ($elem['objectid'] == $_REQUEST['_ext_objectid'] ) $idx = $key;
				}
				$this->extresult['nextid'] = 0;
				$this->extresult['previd'] = 0;
				if ($idx) {
					$this->extresult['index'] = floor($idx / ($this->extconfig['xnum'] * $this->extconfig['ynum']));
					if ($idx < $obj->elementscount-1) 
						$this->extresult['nextid'] = $obj->elements[$idx+1]['objectid'];
					if ($idx > 0) 
						$this->extresult['previd'] = $obj->elements[$idx-1]['objectid'];
				}
			}
			break;
		
		default:
			$this->useTemplate('templatename_index','templateid_index','standard_gallery_index');
			
			$binfiletype = 'binfile';
			$tobj = owRead($this->extconfig['folderid']);
			if ($tobj->getType() == 'stimgfolder') {
				$binfiletype = 'stimgbinfile';
			}
			
			$obj = owNew($binfiletype);
			
			$obj->listobjects($this->extconfig['folderid']);
			if (is_array($obj->elements)) {
				$numprpage = $this->extconfig['xnum'] * $this->extconfig['ynum'];
				$curpage = $_REQUEST['_ext_index'];
				if (!is_numeric($curpage)) $curpage = 0;
				$arr = array_slice($obj->elements, $curpage * $numprpage, $numprpage);
				$this->extresult['images'] = $arr;

				$total = sizeof($obj->elements) / $numprpage;
				if ($total < 1) $total = 1;
				for ($x = 0; $x < $total; $x++) {
					$this->extresult['indexlist'][] = $x;
				}
			}
		}
	}
}
?>
