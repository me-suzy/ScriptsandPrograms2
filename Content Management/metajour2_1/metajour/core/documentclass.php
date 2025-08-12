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

class document extends basic {

	function document() {
		$this->basic();
		$this->setsubtype('documentsection');
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('alias',F_LITERAL,'string');
		$this->addcolumn('templateid',F_REL,'relation','template');
		$this->addcolumn('stylesheetid',F_REL,'relation','stylesheet');
		$this->addcolumn('metadataid',F_REL,'relation','metadata');
		$this->addcolumn('structureid',0,UI_HIDDEN);#UI_RELATION,'structure'
		$this->addcolumn('nolist',F_LITERAL,'checkbox');
		$this->addcolumn('nosearch',F_LITERAL,'checkbox');
		$this->addcolumn('hascontenttree', 0, UI_HIDDEN);
		
		$this->setUseApp(true);

		$this->addview('preview');
		$this->addview('createfuture');
		$this->addview('approvepublish');
		$this->addview('requestapproval');
		$this->removeview('createvariant');
	}

	function initLayout() {
		parent::initLayout();
		$field =& $this->getColObj('templateid');
		$field->setfilter_search('tpltype',0,EQUAL);
	}
	
	function stdListCol() {
		if ($this->userhandler->getAppName() == 'edocument') {
			$arr = array();
			$arr[] = 'name';
			$arr[] = 'alias';
			$arr[] = 'createdbyname';
			$arr[] = 'changed';
			$arr[] = 'language';
			$arr[] = 'objectid';
			return $arr;
		} else {
			return parent::stdListCol();
		}
	}
	
	function tableUpdate() {
		if (!colExists('document', 'hascontenttree')) {
			$db =& getdbconn();
			$db->execute('ALTER TABLE document ADD COLUMN hascontenttree TINYINT NOT NULL DEFAULT 0');
		}
	}
	
}

?>