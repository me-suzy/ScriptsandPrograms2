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

class ext_article extends basicextension {

	function ext_article() {
		$this->basicextension();
		$this->extname = 'article';
		$this->addextparam('templatename');
	}

	function getDefaultConfig() {
		// default configuration
		$this->extconfig['categoryid'] = 0;
		$this->extconfig['sortorder'] = 0;
		$this->extconfig['excerptlength'] = 200;
		$this->extconfig['timelimit'] = 0;
		$this->extconfig['numlimit'] = 0;
		$this->extconfig['showheader'] = 1;
		$this->extconfig['showsubheader'] = 0;
		$this->extconfig['showdate'] = 1;
		$this->extconfig['showowner'] = 1;
		$this->extconfig['showexcerpt'] = 1;
		
	}

	function _do() {
		$this->useTemplate('templatename','templateid','standard_article_list');
		
		$obj = owNew('document');
		if ($this->extconfig['categoryid']) $obj->setfilter_category($this->extconfig['categoryid']);
		
		$obj->setsort_col('object.created');
		// sort by create, ascending
		
		if ($this->extconfig['sortorder'] == 0) $obj->setsort_way('ASC');
		
		// sort by create, descending
		if ($this->extconfig['sortorder'] == 1) $obj->setsort_way('DESC');

		if ($this->extconfig['timelimit']) $obj->setfilter_rawWhere(' AND created > DATE_SUB(NOW(),INTERVAL '.$this->extconfig['timelimit'].' DAY) ');
		
		$obj->setfilter_getname(true);
		$obj->listobjects();
		
		if ($obj->elementscount > 0) {
			foreach ($obj->elements as $cur) {
				if ($this->extconfig['numlimit']) {
					if ($this->extconfig['numlimit'] <= count($this->extresult)) break;
				}
				$sectionobj = owNew('documentsection');
				$sectionobj->listobjects($cur['objectid']);
				$cur['section'] = $sectionobj->elements;
				$this->extresult[] = $cur;
			}
		}
		
	}

}
?>