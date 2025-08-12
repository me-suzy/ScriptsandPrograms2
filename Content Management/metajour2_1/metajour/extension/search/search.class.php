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

class ext_search extends basicextension {

	var $db;
	
	function ext_search() {
		$this->basicextension();
		$this->extname = 'search';
		$this->addextparam('structureid');
		$this->addextparam('templatename_result');
		$this->addextparam('templatename_search');
		$this->addextparam('boolean');
		$this->db =& getdbconn();
	}

	function getDefaultConfig() {
		# misc hardcoded variable assignments
		$this->extconfig['pageid_result'] = $_REQUEST['pageid'];
	}
	
	function initState() {
		# set the next possible command if applicable
		$this->next_extcmd = "search";
		$this->useTemplate('templatename_search','templateid_search','standard_search_dialog');
	}

	function getSectionsBefore($parentid, $childorder) {
		return $this->db->getCol("SELECT objectid FROM object WHERE site = $this->site and parentid = $parentid and deleted = 0 and variantof = 0 and childorder < $childorder and type='documentsection'");
	}
	
	function getCandidateSections($keyword, $lang = '', $app = '') {
		
		$langselect = "";
		if ($lang != '') {
			$langselect = " and o.language = '" . $lang . "' ";
		}
		
		$appselect = "";
		if ($app != '') {
			$appselect = " and p.useapp = '" . $app . "' ";
		}
		
		$query = "select o.objectid
				from 
				documentsection, document, object o, object p, user u
				where 
				(documentsection.content like '%$keyword%' or
				documentsection.name like '%$keyword%' or
				documentsection.subname like '%$keyword%' or
				document.name like '%$keyword%' ) and 
				o.site = $this->site and 
				o.objectid = documentsection.objectid and 
				p.objectid = o.parentid and 
				document.objectid = p.objectid and
				document.nosearch = 0 and 
				p.deleted = 0 and
				p.createdby = u.objectid
				$appselect
				$langselect 
				group by o.parentid";
			
		return $this->db->getCol($query);
	}
	
	function doSearch() {
		$this->useTemplate('templatename_result','templateid_result','standard_search_result');

		$keyword = $_REQUEST['keyword'];

		$db =& $this->db;
		$lang = (isset($_SESSION['lang'])) ? $_SESSION['lang'] : '';
		$eh =& getErrorHandler();
		$eh->disable();
		$cobj = owRead($_REQUEST['pageid']);
		$eh->enable();
		
		if ($cobj) {
			$app = $cobj->elements[0]['useapp'];
		}

		// $foundsections contains list of potential hits
		$foundsections = $this->getCandidateSections($keyword, $lang, $app);
		
		// "Filter" $foundsections through listobjects 
		// which then takes care of validating access,
		// resolving variants and so on...
		$documentsections = owNew('documentsection');
		$documentsections->setFilter_getName(true);
		$documentsections->listObjects(null,$foundsections);
		
		// Just get the name. Speeds up operation
		$document = owNew('document');
		$document->setFilter_nameOnly(true);
		
		// Just get the name. Speeds up operation
		$siblings = owNew('documentsection');
		$siblings->setFilter_nameOnly(true);
		
		$resultcount = 0;
		
		for ($i = 0; $i < $documentsections->elementscount; ++$i) {
			$document->readobject($documentsections->elements[$i]['parentid']);
			if ($document) {
				$row =& $documentsections->elements[$i];
				// Both section and document are available
				$content = strip_tags($row['content']);
				$this->extresult[$resultcount]["pageid"] = $row['parentid'];
				$this->extresult[$resultcount]["url"] = "showpage.php?pageid=".$row['parentid'];
				$this->extresult[$resultcount]["content"] = $content;
				$this->extresult[$resultcount]["contentraw"] = $row['content'];
				$this->extresult[$resultcount]["name"] = $document->getName();
				$this->extresult[$resultcount]["sectionname"] = $row['name'];
				$this->extresult[$resultcount]["sectionsubname"] = $row['subname'];
				$this->extresult[$resultcount]["sectionobjectid"] = $row['objectid'];
				$this->extresult[$resultcount]["changed"] = $row['changed'];
				$this->extresult[$resultcount]["createdbyname"] = $row['createdbyname'];
				
				// $siblingids is a "raw" list of siblings placed "before" the
				// found section.
				$siblingids = $this->getSectionsBefore($row['parentid'], $row['childorder']);
				
				// We again filter our raw list through basicclass to
				// filter deleted objects and similar stuff
				$siblings->listObjects(null, $siblingids);
				
				// Now we can use $siblings to find the sectionnumber typically used
				// be templates for showing newsdocuments.
				$this->extresult[$resultcount]['sectionnum'] = $siblings->elementscount;
				++$resultcount;
			}
				
		}

	}
	
	function _do() {
		# actions in accordance to the supplied $this->extcmd
		
		if ($this->extconfig['pageid_result'] == 0) $this->extconfig['pageid_result'] = $_REQUEST['pageid'];
		switch ($this->extcmd) {

			case "search" :
				$this->doSearch();
				break;
	
			default:
				$this->initState();
		}
	}
}
?>
