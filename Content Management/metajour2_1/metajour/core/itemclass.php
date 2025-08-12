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

class item extends basic {

	function item() {
		$this->basic();
		$this->addcolumn('name',F_LITERAL,'string');
		$this->addcolumn('content1',F_LITERAL,'text');
		$this->addcolumn('content2',F_LITERAL,'text');
		$this->addcolumn('content3',F_LITERAL,'hidden');
		$this->addcolumn('price',F_LITERAL,'decimal');
		$this->addcolumn('vatid',F_REL,'relation','vat');
		$this->addcolumn('image1',F_REL,'splitselect','binfile');
		$this->addcolumn('image2',F_REL,'splitselect','binfile');
		$this->addcolumn('image3',F_REL,'hidden');
		$this->addcolumn('exstr1',F_REL,'hidden');
		$this->addcolumn('exstr2',F_REL,'hidden');
		$this->addcolumn('exstr3',F_REL,'hidden');
		$this->addcolumn('exstr4',F_REL,'hidden');
		$this->addcolumn('exstr5',F_REL,'hidden');
		$this->addcolumn('exstr6',F_REL,'hidden');
		$this->addcolumn('exstr7',F_REL,'hidden');
		$this->addcolumn('exstr8',F_REL,'hidden');
		
		$this->removeview('category');
		$this->removeview('access');
	}

	function initLayout() {
		parent::initLayout();
		$this->addcolumnstyle('name','width: 100px;');
		$this->addcolumnstyle('price','width: 100px;');
		$this->addcolumnstyle('content1','width: 400px; height: 80px');
		$this->addcolumnstyle('content2','width: 400px; height: 80px');
		$this->addcolumnstyle('content3','width: 400px; height: 80px');
		$this->clearRelationDatatypes();
		$this->clearChildDatatypes();
	}
	
	function stdListCol() {
		$result = array();
		$result[] = 'name';
		$result[] = 'content1';
		$result[] = 'price';
		$result[] = 'changed';
		return $result;
	}

	function stdListInfocol() {
		$result = parent::stdListInfocol();
		$result[] = '_image1';
		return $result;
	}

	function hasaccess() {
		return true;
	}
}