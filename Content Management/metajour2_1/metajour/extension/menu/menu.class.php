<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage extension
 */

require_once($system_path."extension/basicextension.class.php");
require_once($system_path."core/structureclass.php");
require_once($system_path."core/structureelementclass.php");
require_once($system_path."core/templateclass.php");

class ext_menu extends basicextension {

	var $structureobj ;
	var $structureread = false;
	var $recursive_read = false;
	
	function ext_menu() {
		global $CONFIG;
		$this->basicextension();
		$this->extname = 'menu';
		$this->addextparam('objectid');
		$this->structureobj = new structure;
		// Usually it is faster to read all structureelements in one query, but for some sites
		// which have many elements which are not used at once, it is faster to read the structure
		// recursively.
		// Toggle the behaviour by setting $CONFIG['recursive_structure'] in config.php
		if (isset($CONFIG['recursive_structure']) && $CONFIG['recursive_structure']) {
			$this->recursive_read = true;
		}
		$this->cnt = 0;
		$this->parentcnt = 0;
		
	}

	function readstructure() {
		if (!$this->structureread) {;
			$objectid = $this->structureobj->locatebyname($this->extconfigset);
			if ($objectid) {
				$this->structureobj->readobject($objectid);
				$this->structureread = true;
			}
		}
	}

	function getParameters($params) {
		if (isset($params['cf'])) $this->extconfigset = $params['cf'];
		if (isset($params['objectid'])) $this->extconfig['objectid'] = $params['objectid'];
		if (isset($params['cmd'])) $this->extcmd = $params['cmd'];
	}

	function insertVirtualItems($section, $level) {
		global $system_path;
		if ($section['extension']) {
							
			require_once($this->userhandler->getSystemPath().'/extension/'.$section['extension'].'/'.$section['extension'].'.class.php');
			$s = 'ext_' . $section['extension'];
			$configset = $section['configset'];
			if (empty($configset)) $configset = 'cfg' . $section['objectid'];

			$ext = new $s();
			$ext->extconfigset = $configset;
			$ext->MeUrl = $this->viewer_url .'showpage.php?pageid=' . $section['parentid'];
			$ext->getDefaultConfig();
			$ext->readConfig();
			if (method_exists($ext, 'getContentTree')) {
				$menus = $ext->getContentTree();
				if (is_array($menus)) {
					foreach ($menus as $menu) {
						$this->cnt++;
						$this->extresult[$this->cnt]['name'] = $menu['name'];
						$this->extresult[$this->cnt]['url'] = $menu['url'];
						$this->extresult[$this->cnt]['level'] = $level;
						$this->extresult[$this->cnt]['level0'] = $level - 1;
					}
				}
			}
		}
	}
	
	function getstructure($parent, $level=0, $overruleparent=0, $parentids) {
		global $_documents;
		if (!$this->recursive_read) {
			global $_structure;
		}

		# Note that this method is addressed directly by showpage.php
		# when using documents with attached structures
		
		# Moved here to enable that showpage can call this method
		# directly without calling _do method

		if ($this->recursive_read) {
			$_structure = new structureelement;
			$_structure->listobjects($parent);
			
		} else {
			if (!isset($_structure)) {
				$_structure = new structureelement;
				$_structure->readallobjectsbyparentid();
			}
		}
	
		if (!isset($_documents)) {
			$_documents = new document;
			$_documents->readallobjectsbyobjectid(0);
		}

		
		if ($this->recursive_read) {
			if ($_structure->elementscount) {
				$this->extresult[0]['parentlist'][$this->parentcnt] = $parent;
				$this->parentcnt++;
				if ($this->cnt - 1 >= 0) {
					$this->extresult[$this->cnt-1]['children'] = sizeof($_structure->elements);
				}
			}
		} else {
			if (sizeof($_structure->elements[$parent]) > 0) {
				$this->extresult[0]['parentlist'][$this->parentcnt] = $parent;
				$this->parentcnt++;
				if ($this->cnt-1 >= 0) {
					$this->extresult[$this->cnt-1]['children'] = sizeof($_structure->elements[$parent]);
				}
			}
		}

		if ($this->recursive_read) {
			$total = $_structure->elementscount;
		} else {
			$total = sizeof($_structure->elements[$parent]);
		}
		
		$z = 0;
		if ($total == 0) {
			$this->cnt--;
			$obj = owRead($parent);
			if ($obj->getType() == 'structureelement') {
				$doc =& $_documents->elements[$obj->elements[0]['pageid']];
				if ($doc['hascontenttree']) {
					$sections = owNew('documentsection');
					$sections->listobjects($doc['objectid']);
					foreach ($sections->elements as $section) {
						$this->insertVirtualItems($section, 1);
					}
				}
			}
		} else {
			while ($z < $total) {
				
				$extresult =& $this->extresult[$this->cnt];
			
				if ($this->recursive_read) {
					$element =& $_structure->elements[$z];
				} else {
					$element =& $_structure->elements[$parent][$z];
				}
			
				$extresult = $element;
				$extresult['level'] = $level;
				$extresult['level0'] = $level - 1;
	
				if (
				('' == $element['name'] || !isset($element['name'])) && '' <> $element['pageid']) {
					$extresult['name'] = $_documents->elements[$element['pageid']]['name'];
				}
				# 'description' is obsolete, but we keep it for compatibility
				$extresult['description'] = $extresult['name'];
				$extresult['docaccess'] = 1;
				$extresult['urltype'] = 0;
	
				## 'urltype' == 0 if structureelement is "empty", e.g. a node element
	
				if (($element['pageid'] != '') AND ($element['pageid'] != '0')) {
					## 'urltype' == 1 if 'url' points to an internal page
					$extresult['url'] = 'showpage.php?pageid=' . $element['pageid'];
					$extresult['docmodified'] = $_documents->elements[$element['pageid']]['changed'];
					if (!isset($_documents->elements[$element['pageid']])) {
						$extresult['docaccess'] = 0;
					} else {
						$doc =& $_documents->elements[$element['pageid']];
						if ($doc['hascontenttree']) {
							$sections = owNew('documentsection');
							$sections->listobjects($doc['objectid']);
							foreach ($sections->elements as $section) {
								$this->insertVirtualItems($section, $level + 1);
							}
						}
						
					}
					
					$extresult['urltype'] = 1;
				}
	
				if (($element['binfile1'] != '') AND ($element['binfile1'] != '0')) {
					## 'urltype' == 1 if 'url' points to an internal file
					$extresult['url'] = 'getfile.php?objectid=' . $element['binfile1'];
					$extresult['urltype'] = 1;
				}
	
				if ($element['url'] != "") {
					## 'urltype' == 2 if 'url' points to an external page
					$extresult['url'] = str_replace(' ', '%20', $extresult['url']);
					$extresult['urltype'] = 2;
				}
	
				if (!in_array($parent, $parentids))
					$parentids[] = $parent;
	
				$extresult['parent'] = $parent;
				$extresult['parents'] = $parentids;
	
				if ($overruleparent != 0) $extresult['parent'] = $overruleparent;
	
				$extresult['children'] = 0;
				#$extresult['id'] = $element['objectid'];
	
				$this->cnt++;
	
	
				if ($element['structureid'] <> 0) {
					$this->getstructure($element['structureid'], $level+1, $element['objectid'], $parentids);
				} else {
					if ($element['haschild']) $this->getstructure($element['objectid'], $level+1, 0, $parentids);
				}
	
				$z++;
			}
		}
	}


	function _do() {
		if (!$this->structureread) {
			$objectid = $this->structureobj->locatebyname($this->extconfigset);
			if ($objectid) {
				$this->structureobj->readobject($objectid);
				$this->structureread = true;
			}
		}

		switch ($this->extcmd) {

			default:

				$id = false;
							
				if (isset($_REQUEST['only'])) {
						$id = $_REQUEST['only'];
				} else {
					if (isset($this->extconfig['objectid'])) {
						$id = $this->extconfig['objectid'];
					} else {
						$structureobj = new structure;
						$id = $structureobj->locatebyname($this->extconfigset);
					}
				}
				if ($id) $this->getstructure($id, 1, 0, array());

				if (isset($this->extresult[0]))
					$this->extresult[0]['parentlist'][] = NULL;  #strange error : temporary fix
				
		} #switch
	}
}

?>
